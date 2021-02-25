<?php

$filepath = realpath(dirname(__FILE__));
include_once ($filepath. '/../lib/Session.php');
Session::init();

$user_id = Session::get("id");

if ($user_id == 0 OR $user_id == null OR $user_id == false) { 
    Session::redirect();
}

?>

<style type="text/css">
	input::placeholder {
	  	//color: #9C27B0;
	  	color: #444;
	  	font-size: 0.8em;
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
	#upwrapper {
		width: 520px;
		border: 1px solid #eee;
		background-color: #eee;
		margin-top: 10px;
		display: none;

	}
	#upload-progress {
		width: 0%;
		height: 20px;
		background-color: #0099ff;
		transition: all 0.2s;
		-moz-transition: all 0.2s;
		-webkit-transition: all 0.2s;
	}
	.dropdown {
		display: inline-block;
		margin-left: 10px;
	}
	.dropdown p {
		display: inline-block;
	}

	.dropdown select {
		border: 0 !important;  /*Removes border*/
		-webkit-appearance: none;  /*Removes default chrome and safari style*/
		-moz-appearance: none; /* Removes Default Firefox style*/
		background: url('images/drop-down-arrow.png') no-repeat;
		background-position: 130px 6px;  /*Position of the background-image*/
		background-size: 10px;
		width: 142px; /*Width of select dropdown to give space for arrow image*/
		text-indent: 0.01px; /* Removes default arrow from firefox*/
		text-overflow: "";  /*Removes default arrow from firefox*/
		cursor: pointer;
	}
</style>

<link rel="stylesheet" type="text/css" href="css/datepicker.min.css">
<script src="js/jquery.js"></script>
<script src="js/datepicker.min.js"></script>

<p id="pageTitle" hidden>&nbsp;&nbsp;Upload Dokumen</p>

<form id="image-form" method="post" enctype="multipart/form-data">

	<div id="dokap-pib">
		<div class="dokap-form-title">No. dan Tangal PIB</div>
		<div id="pib-nomor-tanggal">
			<input type="text" name="pib_nomor" id="pib_nomor" maxlength="6" />
			<input data-toggle="datepicker" type="text" name="pib_tanggal" id="pib_tanggal" maxlength="10" placeholder="DD-MM-YYYY" />
			<div class="dropdown">
			    <p> &#10070; </p>
			    <select class="select">
			    	<option style="color: #000;" value="">Jalur PIB</option>
			        <option style="color: green;" value="H">Hijau [H]</option>
			        <option style="color: #9c5d00;" value="K">Kuning [K]</option>
			        <option style="color: #ff0000;" value="M">Merah [M]</option>
			        <option style="color: #a26464;" value="RH">Rush Handling [RH]</option>
			    </select>
			</div> <!-- DropDown -->
			<script type="text/javascript">
				var default_selected = $( ".select option:selected" ).val();
				if (default_selected == '') {
					$('.dropdown p').css({"color": "#000", "font-weight": "bold"});
					$('.select').css('color', '#000');
				}
				if (default_selected == 'H') {
					$('.dropdown p').css({"color": "green", "font-weight": "bold"});
					$('.select').css('color', 'green');
				}
				if (default_selected == 'K') {
					$('.dropdown p').css({"color": "#9c5d00", "font-weight": "bold"});
					$('.select').css('color', '#9c5d00');
				}
				if (default_selected == 'M') {
					$('.dropdown p').css({"color": "#ff0000", "font-weight": "bold"});
					$('.select').css('color', '#ff0000');
				}
				if (default_selected == 'RH') {
					$('.dropdown p').css({"color": "#a26464", "font-weight": "bold"});
					$('.select').css('color', '#a26464');
				}
				$('.select').on('change', function() {
					var selected = $(this).val();
					if (selected == '') {
						$('.dropdown p').css({"color": "#000", "font-weight": "bold"});
						$('.select').css('color', '#000');
					}
					if (selected == 'H') {
						$('.dropdown p').css({"color": "green", "font-weight": "bold"});
						$('.select').css('color', 'green');
					}
					if (selected == 'K') {
						$('.dropdown p').css({"color": "#9c5d00", "font-weight": "bold"});
						$('.select').css('color', '#9c5d00');
					}
					if (selected == 'M') {
						$('.dropdown p').css({"color": "#ff0000", "font-weight": "bold"});
						$('.select').css('color', '#ff0000');
					}
					if (selected == 'RH') {
						$('.dropdown p').css({"color": "#a26464", "font-weight": "bold"});
						$('.select').css('color', '#a26464');
					}
				})
			</script>
		</div>

		<div class="dokap-form-title-x">Nama Importir</div>
		<div id="importir_nama_wrapper">
			<input type="text" name="importir_nama" id="importir_nama" />
		</div>

		<div class="dokap-form-title-x">Nama Shipper</div>
		<div id="shipper_nama_wrapper">
			<input type="text" name="shipper_nama" id="shipper_nama" />
		</div>

		<div class="dokap-wrapper-b">
			<div id="image-modal" class="modal" role="dialog">
				<div class="modal-dialog">
					<div class="modal-content">
						<div class="modal-header">
							<p class="modal-title"></p>
						</div>
						<div class="modal-body">
								<p>
									<input type="file" name="image" id="image" />
									<div class="dokap-jenis-dok">Pilih jenis dokumen: &nbsp;&nbsp;&nbsp;
										<select id="doc_type" name="doc_type">
											<option value="1">Packing List</option>
											<option value="2">Invoice</option>
											<option value="3">Master Air Waybill</option>
											<option value="4">House Air Waybill</option>
											<option value="5">Lain-lain</option>
											<!-- <option value="6">NPD</option> -->
										</select>
									</div>
									<div>
										<input type="text" name="doc_type_lain" id="doc_type_lain" />
									</div>
								</p>
								<!-- <br> -->
								<p id="file_info_label"><img src="images/720380.png" title="Keterangan"></p>
								<textarea id="file_info" name="file_info" title="Tambahkan keterangan jika ada"></textarea>
						</div>
					</div>
				</div>
			</div>
		</div>

		<div id="upwrapper">
			<div id="upload-progress"></div>
		</div>
		
		<br>
		<input type="hidden" name="action" id="action" value="insert" />
		<input type="submit" name="insert" id="insert" value="UPLOAD" class="button-info-dokap">
	</div>
</form>

<script src="js/dokap.js"></script>

<script type="text/javascript">
	$('[data-toggle="datepicker"]').datepicker({
        format: 'dd-mm-yyyy',
        autoHide: true
    });
</script>