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
	#action_detail_wrapper {
		margin-bottom: 8px;
		margin-top: 20px;
		width: 400px;
		border: 0.1px dashed #607D8B;
		padding: 10px;
		display: none;
		position: relative;
	    top: 32%;
	    background-color: #fff;
	}
	#action_detail {
		width: 96.5%;
		padding: 1.5px 4px;
		margin-top: 10px;
		height: 65px;
	}
	#reject_button_process {
		margin-top: 18px;
		margin-bottom: 5px;
		width: 98px;
		background-color: #ffeb3b;
		padding: 5px 7px;
		cursor: pointer;
		font-weight: bolder;
		box-shadow: 1px 1px 1px 1px #a8a7a8;
		color: red;
		display: inline-block;
	}
	#reject_button_cancel {
		position: absolute;
	    top: 10px;
	    right: 10px;
	    border: 1px solid #ddd;
	    padding: 2px 5px;
	    cursor: pointer;
	    background-color: #e4e4e4;
	    box-shadow: 1px 1px 1px 0px #7e7e7e;
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
		<div class="list_doc">[ <?php echo $no ?> ] &nbsp; <?php echo $doc['type_desc'] ?> <a id="filename_link" href="pdf.php?filename=<?php echo $file_name_del ?>" target="_blank">DOWNLOAD or VIEW</a></div>
		<?php $no++; } ?>

		<!-- Upload tanda terima -->
		<div id="tanda_terima_wrapper">
			<div id="pilih_pfpd">UPLOAD TANDA TERIMA</div>
			<form id="tt_form" method="post" enctype="multipart/form-data">
				<input type="file" name="tt_file" id="tt_file" />
			</form>
		</div>

		<!-- List PFPD -->
		<div id="pfpd_wrapper">
			<div id="pilih_pfpd">PILIH PFPD</div>
			<input type="text" name="pfpd_nama" class="pfpd_nama" />
			<input type="text" name="pfpf_nip" class="pfpd_nip" />
			<input type="hidden" name="pfpd_id" class="pfpd_id" value="" />
		</div>

		<div id="action_detail_wrapper">
			<div id="pilih_pfpd">ALASAN REJECT</div>
			<textarea id="action_detail" type="text" name="action_detail"></textarea>
			<div id="reject_button_cancel">x</div>
			<div id="reject_button_process">PROSES REJECT</div>
		</div>

		<input style="margin-top: 15px;" name="npd" type="checkbox" value="npd"> <span style="color:#FF9800;font-weight:800;">TERLAMPIR RESPON NPD</span>
		<div class="clearfix"></div>
		
		<!-- Terima button -->
		<div id="terima_button">TERIMA</div>
		<!-- Reject button -->
		<div id="reject_button">REJECT</div>
		
		<input type="hidden" name="perusahaan_id" value="<?php echo $data['pib_nomor'] ?>">
	</div>
</div>

<div style="margin-top: 15px;">
<a class="load-back_pending_i" href="upload_browse_terima" style="text-decoration: none;">Prev. [ &#8626; ]</a>
</div>

<script type="text/javascript" src="js/jquery_ui.js"></script>
<script type="text/javascript">

	$(document).ready(function() {

		$('.load-back_pending_i').click(function() {
			var page = $(this).attr('href');
			$('#apps-contents').load('contents/' + page + '.php');
			return false;
		});

		$(function() {
			var availableTags = <?php include '../ajax/ajax_user_autocomplete.php' ?>;
			$('.pfpd_nama').autocomplete({
				minlength: 0,
				source: availableTags,
				focus: function(event, ui) {
					$('.pfpd_nama').val(ui.item.value);
					return false;
				},
				select: function(event, ui) {
					$('.pfpd_nama').val(ui.item.value);
					$('.pfpd_nip').val(ui.item.nip);
					$('.pfpd_id').val(ui.item.id);
					return false;
				}
			});
		});

		$("#terima_button").on("click", function() {

			var r = confirm("Are you sure want to accept the documents?");

			if (r == true) {

			    var tt_file = $("#tt_file").val();

				// Get all data from tt_form
				var formElement = document.querySelector("form");
				var dataString = new FormData(formElement);

				var pib_nomor = $(".pib_nomor").val();
				var pib_tanggal = $(".pib_tanggal").val();
				var pfpd_id = $(".pfpd_id").val();
				var npd = $('input[name="npd"]:checked').val();
				if( typeof npd === 'undefined' || npd === null ){
					npd = "";
				}

				// Append another data to dataString
				dataString.append("pib_nomor", pib_nomor);
				dataString.append("pib_tanggal", pib_tanggal);
				dataString.append("pfpd_id", pfpd_id);
				dataString.append('npd', npd);
				
				// if (tt_file.length == '0') {
				// 	alert("Tanda terima belum diupload");
				// }

				var file_name = $("#tt_file").val();
				if (file_name == '') {
					alert("Tanda terima belum diupload");
					return false;
				} else {
					var extension = $("#tt_file").val().split(".").pop().toLowerCase();
					if (jQuery.inArray(extension, ['pdf']) == -1) {
						alert("Invalid document type");
						$("#tt_file").val("");
						return false;
					}
				}

				if (tt_file > '0') {

					if (pfpd_id == "") {
						alert("Belum memilih PFPD");
					}

					if (pfpd_id != "") {
						$.ajax({
							url: "../adek/ajax/ajax_upload_terima.php",
							method: "POST",
							data: dataString,
							contentType: false,
							processData: false,
							success: function(data) {
								console.log(data);
								var pagex = 'upload_browse_terima';
		                    	$('#apps-contents').load('contents/' + pagex + '.php');
							},
							error: function(data) {
								alert("Ops! failed.");
							}
						});
					}
				}

			} else {

			    alert("You pressed Cancel!");
			}

			return false;
		});

		$("#reject_button_cancel").on("click", function() {
			$("#action_detail_wrapper").hide();
			$("#action_detail").val("");
			$("#pfpd_wrapper").show();
			$("#tanda_terima_wrapper").show();
		});
		
		$("#reject_button").on("click", function() {
			$("#action_detail_wrapper").show();
			$("#pfpd_wrapper").hide();
			$("#tanda_terima_wrapper").hide();
		});

		$("#reject_button_process").on("click", function() {

			var r = confirm("Are you sure want to reject the documents?");

			if (r == true) {

				var action_detail = $("#action_detail").val();
				var pib_nomor = $(".pib_nomor").val();
				var pib_tanggal = $(".pib_tanggal").val();
				var dataString = "pib_nomor=" + pib_nomor + "&pib_tanggal=" + pib_tanggal + "&action_detail=" + action_detail;
				if (action_detail == '') {
					alert("ALASAN REJECT TIDAK BOLEH KOSONG");
				}

				if (action_detail != '') {
					$.ajax({
						url: "../adek/ajax/ajax_upload_reject.php",
						method: "POST",
						data: dataString,
						success: function(data) {
							// console.log(data);
							alert(data);
							var pagex = 'upload_browse_terima';
			                $('#apps-contents').load('contents/' + pagex + '.php');
						},
						error: function(data) {
							console.log(data);
						}
					});	
				}

			} else {
				alert("You pressed Cancel!");
			}

			return false;
		});

	});

</script>