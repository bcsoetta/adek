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
	a {
		text-decoration: none;
	}

	/*dfdfd*/
	.dokap-wrapper-erk {
		display: none;
	    width: 500px;
	    top: 10%;
	    padding: 0 10px 10px 10px;
	    border: 1px solid #c5c5c5;
	    margin-top: 15px;
	}

	.button-erk-close {
		position: absolute;
		top: 0;
		right: 1px;
		width: 25px;
		border: 1px solid #ddd;
		padding: 2px 5px;
		cursor: pointer;
		background-color: #e4e4e4;
		box-shadow: 1px 1px 1px 0px #7e7e7e;
	}

	#process_edit {
		border: 1px solid #ddd;
	    padding: 2px 5px;
	    cursor: pointer;
	    background-color: #e4e4e4;
	    box-shadow: 1px 1px 1px 0px #7e7e7e;
	}

	.edit_erk {
		color: #795548;
	}

	.rem_erk {
		color: red;
	}

	#doc_id_hover {
		visibility: hidden;
	}

	#send-dokap_erk {
		border: 1px solid #ddd;
		padding: 2px 5px;
		cursor: pointer;
		background-color: #e4e4e4;
		box-shadow: 1px 1px 1px 0px #7e7e7e;
		margin-top: 8px;
		margin-bottom: 2px;
	}

	#send-dokap_erk {
		color: green;
	}

	#pernyataan {
		border: 1px dashed #ddd;
		padding: 10px;
		margin-top: 20px;
		margin-bottom: 10px;
		width: 600px;
	}

	#text-pernyataan-a, #text-pernyataan-b {
		/*color: red;*/
		/*border: 1px solid red;*/
		display: inline-block;
		vertical-align: top;
	}

	#text-pernyataan-a {
		width: 12px;
		margin-top: 2px;
		margin-right: 5px;
	}

	#text-pernyataan-b {
		width: 92%;
	}

	#file_info {
		width: 95%;
		min-height: 30px;
		margin-top: 10px;
		padding: 7px;
		text-transform: uppercase;
	}
	#file_info_label {
		font-weight: 500;
		margin-top: 15px;
	}
	img {
		width: 20px;
	    /*margin-bottom: 5px;*/
	    float: left;
	}
	.href_file_info {
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

$data = $apps->getDocsByPIB2($pib_nomor, $pib_tanggal);
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
		<div class="list_doc">[ <?php echo $no ?> ] &nbsp; <?php echo $doc['type_desc'] ?> <a id="filename_link" href="pdf.php?filename=<?php echo $file_name_del ?>" target="_blank">_DOWNLOAD or VIEW</a>, <?php if($doc['file_info'] !== "") { ?> <a class="href_file_info" data-file-info="KET: <?php echo $doc['file_info'] ?>" href="#">KETERANGAN</a>, <?php } ?> <a class="edit_erk" data-href-edit="<?php echo $doc['id'] ?>" file-info="<?php echo $doc['file_info'] ?>" data-href-num="<?php echo $no ?>&nbsp; <?php echo $doc['type_desc'] ?>" href="#">EDIT</a>, <a class="rem_erk" doc_id_erk="<?php echo $doc['id'] ?>" data-href-num-erk="[ <?php echo $no ?> ] <?php echo $doc['type_desc'] ?>" href="#">REMOVE</a></div>
		<?php $no++; } ?>
	</div>
</div>

<div class="dokap-wrapper-erk">
	<div id="image-modal" class="modal" role="dialog">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<p class="modal-title"></p>
				</div>
				<form id="files-form" method="post" enctype="multipart/form-data">
					<div class="modal-body">
							<p>
								<input type="file" name="files" id="files" />
								<div class="dokap-jenis-dok">Pilih jenis dokumen: &nbsp;&nbsp;&nbsp;
									<select id="doc_type" name="doc_type">
										<option value="1">Packing List</option>
										<option value="2">Invoice</option>
										<option value="3">Master Air Waybill</option>
										<option value="4">House Air Waybill</option>
										<option value="5">Lain-lain</option>
									</select>
								</div>
								<div>
									<input type="text" name="doc_type_lain" id="doc_type_lain" />
								</div>
							</p>
							<p id="file_info_label"><img src="images/720380.png" title="Keterangan"></p>
							<textarea id="file_info" name="file_info" title="Tambahkan keterangan jika ada"></textarea>
							<br><br>
							<input type="submit" id="process_edit" value="UPLOAD" class="button-info-dokap">
					</div>
				</form>
				<div class="modal-footer">
					<button type="button" class="button-erk-close" data-dismiss="modal">x</button>
				</div>
			</div>
		</div>
	</div>
</div>

<div id="pernyataan">
	<div id="text-pernyataan-a">
		<input type="checkbox" name="pernyataan" value="SAYA MENYATAKAN BAHWA SELURUH DATA DAN DOKUMEN YANG SAYA SAMPAIKAN ADALAH BENAR DAN SESUAI DENGAN ASLINYA, DAN SAYA BERSEDIA BERTANGGUNGJAWAB SESUAI KETENTUAN HUKUM YANG BERLAKU JIKA DIKEMUDIAN HARI TERJADI PERMASALAHAN HUKUM TERKAIT KEBENARAN DATA TERSEBUT" id="checkbox-button" />
	</div>
	<div id="text-pernyataan-b">SAYA MENYATAKAN BAHWA SELURUH DATA DAN DOKUMEN YANG SAYA SAMPAIKAN ADALAH BENAR DAN SESUAI DENGAN ASLINYA, DAN SAYA BERSEDIA BERTANGGUNGJAWAB SESUAI KETENTUAN HUKUM YANG BERLAKU JIKA DIKEMUDIAN HARI TERJADI PERMASALAHAN HUKUM TERKAIT KEBENARAN DATA TERSEBUT</div>
</div>
<button id="send-dokap_erk" type="button">KIRIM</button>

<div style="margin-top: 15px;">
<a class="load-back-status-erk" href="upload_edit_rem_kirim" style="text-decoration: none;">Prev. [ &#8626; ]</a>
</div>

<script type="text/javascript" src="js/jquery_ui.js"></script>
<script type="text/javascript">

	$(document).ready(function() {

		$('.load-back-status-erk').click(function() {
			var page = $(this).attr('href');
			$('#apps-contents').load('contents/' + page + '.php');
			return false;
		});

		// SELECT OPTIONS DETECT ON CHANGE
		$("#doc_type").change(function() {
			var selectedType = $('option:selected', this).attr('value');
			if (selectedType == "5") {
				$("#doc_type_lain").show();
			} else {
				$("#doc_type_lain").hide();
				$("#doc_type_lain").val("");
			}
		});

		$(".edit_erk").on("click", function(e) {
			e.stopImmediatePropagation();
			var num = $(this).attr("data-href-num");
			var doc_id = $(this).attr("data-href-edit");
			var file_info = $(this).attr("file-info");

			$(".modal-title").html("<font color='#ff0057'>" + "EDIT NO. " + num + "</font><div id='doc_id_hover' doc_id='"+doc_id+"'></div>");
			$("#file_info").html(file_info);
			$("#send-dokap_erk").hide();
			$("#pernyataan").hide();
			$(".dokap-wrapper-erk").show();
			$("#rem-dokap_erk").hide();
			return false;
		});


		$(".button-erk-close").on("click", function(e) {
			e.stopImmediatePropagation();
			$(".dokap-wrapper-erk").hide();
			$("#send-dokap_erk").show();
			$("#pernyataan").show();
			return false;
		});

		$(".rem_erk").on("click", function(e) {
			// e.stopImmediatePropagation();
			$("#send-dokap_erk").show();
			$(".dokap-wrapper-erk").hide();
			$("#pernyataan").show();
			// return false;
		});

		$(".href_file_info").on("click", function(e) {
			// e.stopImmediatePropagation();
			alert($(this).attr("data-file-info"));
			// return false;
		});

		$("#process_edit").click(function(e) {
			var r = confirm("Are you sure want to edit the documents?");
			if (r == true) {
				e.stopImmediatePropagation();
				var file_name = $("#files").val();
				if (file_name == '') {
					alert("Please select document");
					return false;
				} else {
					var extension = $("#files").val().split(".").pop().toLowerCase();
					if (jQuery.inArray(extension, ['pdf']) == -1) {
						alert("Invalid document type");
						$("#files").val("");
						return false;
					}
				}

				var action = "edit";
		        var doc_id = $("#doc_id_hover").attr("doc_id");
		        var pib_nomor = "<?php echo $data['pib_nomor'] ?>";
		        var pib_tanggal = "<?php echo $data['pib_tanggal'] ?>";
		        var file_info = $("#file_info").val();
		        // Get all data from tt_form
				var formElement = document.querySelector("form");
				var dataString = new FormData(formElement);

				dataString.append('action', action);
				dataString.append('doc_id', doc_id);
				dataString.append('pib_nomor', pib_nomor);
				dataString.append('pib_tanggal', pib_tanggal);
				dataString.append('file_info', file_info);
		        
		        $.ajax({
		        	url: "../adek/ajax/ajax_edit_rem_kirim.php",
		        	method: "POST",
		        	data: dataString,
					contentType: false,
					processData: false,
					success: function(data) {
						// console.log(data);
						if (data == '55') {
							alert("Jenis dokumen tidak boleh kosong");
						} else {
							alert(data);
							$(".edit_erk[data-href-edit='"+doc_id+"']").css({"background-color": "yellow"});
						}
					},
					error: function(data) {
						// console.log(data);
						alert(data);
					}
		        });

			} else {
			    alert("You pressed Cancel!");
			}
			return false;
		});

		$(".rem_erk").click(function(e) {
			var action = "remove";
	        var doc_id = $(this).attr("doc_id_erk");
	        var doc = $(this).attr("data-href-num-erk");
	        var pib_nomor = "<?php echo $data['pib_nomor'] ?>";
	        var pib_tanggal = "<?php echo $data['pib_tanggal'] ?>";
			var r = confirm("Are you sure want to remove number " + doc + "?");

			var me = this;

			if (r == true) {
				e.stopImmediatePropagation();
		        $.ajax({
		        	url: "../adek/ajax/ajax_edit_rem_kirim.php",
		        	method: "POST",
		        	data: {
		        		action: action,
		        		doc_id: doc_id,
		        		pib_nomor: pib_nomor,
		        		pib_tanggal: pib_tanggal
		        	},
					success: function(data) {
						alert(data);
						$(".rem_erk[doc_id_erk='"+doc_id+"']").css({"background-color": "yellow"});
						$(me).closest('div').remove();
					},
					error: function(data) {
						// console.log(data);
						alert(data);
					}
		        });

			} else {
			    alert("You pressed Cancel!");
			}
			return false;
		});

		$("#send-dokap_erk").click(function(e) {
			var r = confirm("Are you sure want to send the documents?");
			if (r == true) {
				e.stopImmediatePropagation();
				var action = "kirim";
		        var pib_nomor = "<?php echo $data['pib_nomor'] ?>";
		        var pib_tanggal = "<?php echo $data['pib_tanggal'] ?>";
		        var pernyataan = $("#checkbox-button").val();

		        if ($('input#checkbox-button').is(':checked')) {
		        	$.ajax({
			        	url: "../adek/ajax/ajax_edit_rem_kirim.php",
			        	method: "POST",
			        	data: {
							action: action,
							pib_nomor: pib_nomor,
							pib_tanggal: pib_tanggal,
							pernyataan: pernyataan
						},
						success: function(data) {
							alert(data);
							var pagex = 'upload_edit_rem_kirim';
			                $('#apps-contents').load('contents/' + pagex + '.php');
							// console.log(data);
						},
						error: function(data) {
							// console.log(data);
							alert(data);
						}
			        });
		        } else {
		        	alert("ANDA BELUM MENYETUJUI PERNYATAAN");
		        }
		        		     
			} else {
			    alert("You pressed Cancel!");
			}
			return false;
		});
		
	});

</script>