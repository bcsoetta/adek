<?php 

$filepath = realpath(dirname(__FILE__));
include_once ($filepath . '/classes/Apps.php');
$apps = new Apps();

$konf_id = $_GET['konf_id'];
$konf = $apps->getKonfByPib($konf_id);
while ($data = mysqli_fetch_array($konf)) {
	$id = $data['id'];
	$pib_nomor = $data['pib_nomor'];
	$pib_tanggal = $data['pib_tanggal'];
	$nama_pfpd = $data['name'];
	$nip_pfpd = $data['nip'];
	$customer_id = $data['customer_id'];
	$konfirmasi = $data['konfirmasi'];
	$konf_timestamp = $data['konf_timestamp'];
};

$stringsKonfirmasi = explode("\n", $konfirmasi);

?>

<style type="text/css">
	#pdf {
		margin: 0;
		padding: 0;
	}
</style>

<title>Sadap - Sistem Informasi Dokumen Pelengkap</title>

<div id="pdf">
  <object width="100%" height="100%" type="application/pdf" data="" id="pdf_content">
    <p>Insert your error message here, if the PDF cannot be displayed.</p>
  </object>
</div>

<script src="js/jquery.js"></script>
<script src="js/jspdf.js"></script>

<script type="text/javascript">

	// Landscape export, 2×4 inches
	var doc = new jsPDF({
	  	orientation: 'p',
	  	unit: 'in',
	  	format: 'a4'
	});

	doc.setFontSize(12);
	doc.setFontType("light");
	doc.text("KONFIRMASI \n\n"
		+ "<?php foreach ($stringsKonfirmasi as $v) {
			echo trim($v) . "\\n";
		} ?> \n\n\n\n"
		+ "Nomor PIB <?php echo $pib_nomor ?> / <?php echo $pib_tanggal ?> \n"
		+ "Nama PFPD <?php echo $nama_pfpd ?> / NIP <?php echo $nip_pfpd?> \n"
		+ "Waktu Konfirmasi <?php echo $konf_timestamp ?> \n" , 1, 1);
	// doc.save("konf_"+'<?php echo $pib_nomor ?>'+".pdf");
	var pdf = doc.output('datauri');
	var data = $("#pdf_content").attr("data", pdf);
	
</script>

