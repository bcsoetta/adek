<style type="text/css">
	#set-imp {
		margin-bottom: 10px;
	}
	.imp1 {
		margin-bottom: 5px;
	}

	.imp2 {
		//padding: 8px 11px;
		//border: 1px solid #ddd;
	}

	#set-kegiatan {
		padding: 10px;
		border: 1px solid #ddd;
	}
	.keg1 {
		width: 130px;
	}
	.keg2 {
		padding: 2px 5px;
	}
	.keg3 {
		cursor: pointer;
	}
	.keg4 {
		border: 1px solid #ddd;
		padding: 2px 5px;
	}
	table {
		margin-bottom: 9px;
	}
	#update1, #update2, #update3, #update4, #update5, #update6, #update7, #update8, #update9, #update10, #update11, #update12 {
		color: blue;
		display: none;
	}
	#edit1, #edit2, #edit3, #edit4, #edit5, #edit6, #edit7, #edit8, #edit9, #edit10, #edit11, #edit12 {
		color: green;
	}
	#enable_all {
		color: green;
	}
	#update_all {
		display: none;
		color: blue;
	}
	#disable_all {
		color: red;
		display: none;
	}
	#filename_link {
		cursor: pointer;
		color: blue;
		display: inline-block;
	}
	.list_doc {
		margin-bottom: 5px;
	}
	#pfpd_wrapper, #tanda_terima_wrapper {
		margin-top: 20px;
		margin-bottom: 5px;
	}
	#tanda_terima_wrapper {
		border: 1px solid #ddd;
		width: 372px;
		padding: 10px;
	}
	#pilih_pfpd {
		margin-bottom: 8px;
		font-size: 1em;
	}
	#terima_button, #reject_button {
		display: inline-block;
	}
	#terima_button {
		margin-top: 15px;
		margin-bottom: 5px;
		width: 49px;
		background-color: #ffeb3b;
		padding: 5px 7px;
		cursor: pointer;
		font-weight: bolder;
		box-shadow: 1px 1px 1px 1px #a8a7a8;
		color: green;
	}
	#reject_button {
		margin-top: 15px;
		margin-bottom: 5px;
		width: 49px;
		background-color: #ffeb3b;
		padding: 5px 7px;
		cursor: pointer;
		font-weight: bolder;
		box-shadow: 1px 1px 1px 1px #a8a7a8;
		color: red;
	}
	.pfpd_nama, .pfpd_nip {
		padding: 2px 5px;
	}
	.pfpd_nama {
		width: 200px;
	}
	#log-wrapper {
		/*border: 1px solid #ddd;
		padding: 5px;*/
		margin-top: 20px;
	}
	#log-title {
		margin-top: 15px;
		margin-bottom: 10px;
		/*border-bottom: 1px solid #ddd;*/
		/*width: 50px;*/
	}
	#log-detil {
		border: 1px dashed #ddd;
		padding: 7px 5px 5px 7px;
		background-color: #f1f1f1;
	}
	.status_file_info {
		color: #4caf50;
	}
</style>

<?php

$filepath = realpath(dirname(__FILE__));
include_once ($filepath. '/../lib/Session.php');
Session::init();
include_once ($filepath.'/../lib/Database.php');
include_once ($filepath.'/../helpers/Format.php');
include_once ($filepath . '/../classes/Apps.php');

spl_autoload_register(function($class) {
	include_once "../classes/" . $class . ".php";
});

$db = new Database();
$fm = new Format();
$usr = new User();
$apps = new Apps();

$pib_nomor = $_GET['pib_nomor'];
$pib_tanggal = $_GET['pib_tanggal'];

$data = $apps->getDocs($pib_nomor, $pib_tanggal);
$docs = $apps->getDocsByPIB($pib_nomor, $pib_tanggal);
$logs = $apps->getLogByPib($pib_nomor, $pib_tanggal);

$user_id = Session::get("id");

if ($user_id == 0 OR $user_id == null OR $user_id == false) { 
    Session::redirect();
}

?>

<div id="set-imp-wrapper">
	<div id="set-imp">
		<div class="imp1 imp2">[ Nama Importir ] <?php echo $data['importir'] ?></div>
		<div class="imp1 imp2">[ Nama Shipper &nbsp;] <?php echo $data['shipper'] ?></div>
		<div class="imp1 imp2">[ PIB ] <?php echo $data['pib_nomor'] ?> / <?php echo $data['pib_tanggal_x'] ?></div>
		<input type="hidden" class="pib_nomor" value="<?php echo $data['pib_nomor'] ?>" />
		<input type="hidden" class="pib_tanggal" value="<?php echo $data['pib_tanggal'] ?>" />
		<br>

		<!-- List documents -->
		<?php $no = 1; foreach ($docs AS $doc) { ?>
			<?php 
				$file_name_del = $doc['filename'] . '.pdf';
				$dir = $_SERVER['DOCUMENT_ROOT'] . '/adek/uploads/';
				$file_to_del = $dir . $file_name_del;
			?>
		<div class="list_doc">[ <?php echo $no ?> ] &nbsp; <?php echo $doc['type_desc'] ?> <a id="filename_link" href="pdf.php?filename=<?php echo $file_name_del ?>" target="_blank">DOWNLOAD or VIEW</a>, <?php if($doc['file_info'] !== "") { ?><a class="status_file_info" data-file-info-st="<?php echo $doc['file_info'] ?>" href="#">KETERANGAN</a><?php }?></div>
		<?php $no++; } ?>
	</div>
</div>

<div id="log-wrapper">
	<div id="log-title">LOG INFO</div>
	<div id="log-detil">

		<?php 
			if ($logs == false) {
				echo "[ STATUS ] &nbsp; BELUM DIKIRIM ...";
			} else { ?>
				<?php $no = 1; foreach ($logs AS $log) { ?>
				<div class="list_doc">[ <?php echo $no ?> ] &nbsp; <?php echo $log['action_timestamp'] ?> &nbsp; <?php echo $log['action_detail'] ?> &nbsp; 
					
					<?php
					if ($log['action'] == 'DITERIMA') { ?>
						<a id="filename_link" href="pdf.php?filename=<?php echo $log['filename'] ?>" target="_blank">CETAK TANDA KIRIM</a>
					<?php }
					?>

					<?php
					if ($log['action'] == 'KONFIRMASI') { ?>
						<a id="filename_link" href="jspdf.php?konf_id=<?php echo $log['konf_id'] ?>" target="_blank">CETAK KONFIRMASI</a>
					<?php }
					?>
						
				</div>
				<?php $no++; } ?>
		<?php } ?>
	</div>
</div>

<div style="margin-top: 15px;">
<a class="load-back-status-pfpd" href="upload_status_pfpd" style="text-decoration: none;">Prev. [ &#8626; ]</a>
</div>

<script type="text/javascript" src="js/jquery_ui.js"></script>
<script type="text/javascript">

	$(document).ready(function() {

		$('.load-back-status-pfpd').click(function() {
			var page = $(this).attr('href');
			$('#apps-contents').load('contents/' + page + '.php');
			return false;
		});

		$(".status_file_info").on("click", function() {
			var ket = $(this).attr("data-file-info-st");
			alert("KET: " + ket);
		});
		
	});

</script>