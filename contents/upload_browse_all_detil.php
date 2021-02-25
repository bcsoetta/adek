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

$user_id = Session::get("id");

if ($user_id == 0 OR $user_id == null OR $user_id == false) { 
    Session::redirect();
}

$db = new Database();
$fm = new Format();
$usr = new User();
$apps = new Apps();

$pib_nomor = $_GET['pib_nomor'];
$pib_tanggal = $_GET['pib_tanggal'];
$data = $apps->getDocs($pib_nomor, $pib_tanggal);
$docs = $apps->getDocsByPIB($pib_nomor, $pib_tanggal);

?>

<div id="set-imp-wrapper">
	<div id="set-imp">
		<div class="imp1 imp2">[ Nama Importir ] <?php echo $data['importir'] ?></div>
		<div class="imp1 imp2">[ PIB ] <?php echo $data['pib_nomor'] ?> / <?php echo $data['pib_tanggal'] ?></div>
		<br>
		<!-- List documents -->
		<?php $no = 1; foreach ($docs AS $doc) { ?>
			<?php 
				$file_name_del = $doc['filename'] . '.pdf';
				$dir = $_SERVER['DOCUMENT_ROOT'] . '/adek/uploads/';
				$file_to_del = $dir . $file_name_del;
			?>
		<div class="list_doc">[ <?php echo $no ?> ] <?php echo $doc['type_desc'] ?> <a id="filename_link" href="pdf.php?filename=<?php echo $file_name_del ?>" target="_blank">DOWNLOAD or VIEW</a></div>
		<?php $no++; } ?>
		
		<input type="hidden" name="perusahaan_id" value="<?php echo $data['pib_nomor'] ?>">
	</div>
</div>

<div style="margin-top: 15px;">
<a class="link-load-back" href="upload_browse_all" style="text-decoration: none;">Prev. [ &#8626; ]</a>
</div>

<script type="text/javascript">
	$(document).ready(function() {
		$('.link-load-back').click(function() {
			var page = $(this).attr('href');
			$('#apps-contents').load('contents/' + page + '.php');
			return false;
		});
	});

</script>