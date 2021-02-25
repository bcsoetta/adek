<?php

$filepath = realpath(dirname(__FILE__));
include_once ($filepath. '/../lib/Session.php');
Session::init();

$user_id = Session::get("id");

if ($user_id == 0 OR $user_id == null OR $user_id == false) { 
    Session::redirect();
}

?>

<style>
	.clearfix:after {
		visibility: hidden;
		display: block;
		font-size: 0;
		content: " ";
		clear: both;
		height: 0;
        margin-bottom: 5px;
	}
	#user-create-wrapper1 {
		border: 1px solid #ddd;
		padding: 10px;
	}
	#user-create-wrapper2 {
		padding: 10px;
		border: 1px solid #ddd;
	}
	.uc1x, .uc1 {
		margin-bottom: 5px;
	}
	.uc1 {
		display: inline-block;
		margin-right: 10px;
	}
	.uc2 {
		padding: 3px 5px;
		width: 200px;
	}
	#uc-submit {
		border: 1px solid #ddd;
		margin-top: 5px;
		padding: 3px 5px;
		cursor: pointer;
		font-size: 12px;
		display: table-cell;
		font-weight: bold;
		box-shadow: 1px 1px 1px 0px #020101;
	}
	.ui-autocomplete {
		max-height: 300px;
		overflow-y: auto;   /* prevent horizontal scrollbar */
		overflow-x: hidden; /* add padding to account for vertical scrollbar */
		z-index:1000 !important;
	}

</style>

<link rel="stylesheet" type="text/css" href="css/datepicker.min.css">

<div id="user-create-wrapper1">
	<div id="user-create-wrapper2">
		<p style="font-weight: 700;"><i class="fa fa-tags" aria-hidden="true"></i>&nbsp;&nbsp; ~ Form Konf. NPD Manual</p>
        <br>
        
		<div class="uc1">
			<div class="uc1">PIB</div>
			<div><input style="width: 80px;" class="uc2" type="text" id="pib_nomor" maxlength="6"></div>
		</div>
		<div class="uc1">
			<div class="uc1"></div>
			<div><input style="width: 80px;" class="uc2" type="text" id="pib_tanggal" maxlength="10" placeholder="DD-MM-YYYY" data-toggle="datepicker"></div>
		</div>

		<div class="clearfix"></div>

		<div class="uc1">
			<div class="uc1">Importir</div>
			<div>
				<input style="width: 400px;" class="uc2" type="text" id="importir">
				<input id="importir_id" value="" type="hidden">
			</div>
        </div>
        
        <div class="uc1">
			<div class="uc1"></div>
			<div>
                <input class="uc2" type="text" id="importir_npwp" disabled>
			</div>
		</div>

		<div class="clearfix"></div>

		<div class="uc1">
			<div class="uc1">PPJK</div>
			<div>
				<input style="width: 400px;" class="uc2" type="text" id="ppjk">
				<input id="ppjk_id" value="" type="hidden">
			</div>
        </div>
        
        <div class="uc1">
			<div class="uc1"></div>
			<div>
				<input class="uc2" type="text" id="ppjk_npwp" disabled>
			</div>
        </div>
        
	</div>
	<br>
	<div id="uc-submit">Kirim</div>
</div>

<br>
<a class="link-load-back" href="npd_pfpd" style="text-decoration: none;">Prev. [ &#8626; ]</a>

<script src="js/jquery.js"></script>
<script type="text/javascript" src="js/jquery_ui.js"></script>
<script src="js/datepicker.min.js"></script>


<script>

    $('[data-toggle="datepicker"]').datepicker({
        format: 'dd-mm-yyyy',
        autoHide: true
    });

	$(document).ready(function() {
	    $('.link-load-back').click(function() {
			var page = $(this).attr('href');
			$('#apps-contents').load('contents/' + page + '.php');
			return false;
		});
	});

	$(function() {
		var availableTags = <?php include '../ajax/ajax_pengguna_jasa.php' ?>;
		$('#importir').autocomplete({
			minlength: 3,
			source: availableTags,
			focus: function(event, ui) {
				$('#importir').val(ui.item.value);
				return false;
			},
			select: function(event, ui) {
				$('#importir').val(ui.item.value);
				$('#importir_id').val(ui.item.id);
                $('#importir_npwp').val(ui.item.nip);
				return false;
			}
		});
	});

	$(function() {
		var availableTags = <?php include '../ajax/ajax_pengguna_jasa.php' ?>;
		$('#ppjk').autocomplete({
			minlength: 3,
			source: availableTags,
			focus: function(event, ui) {
				$('#ppjk').val(ui.item.value);
				return false;
			},
			select: function(event, ui) {
				$('#ppjk').val(ui.item.value);
				$('#ppjk_id').val(ui.item.id);
                $('#ppjk_npwp').val(ui.item.nip);
				return false;
			}
		});
	});

	$(document).ready(function() {
		$("#uc-submit").on("click", function() {
			var pib_nomor = $('#pib_nomor').val();
            var pib_tanggal = $('#pib_tanggal').val();
            var importir = $('#importir').val();
            var npwp_importir = $('#importir_npwp').val();
            var ppjk = $('#ppjk').val();
            var npwp_ppjk = $('#ppjk_npwp').val();
            var pfpd = "<?php echo Session::get("name"); ?>";
			var pfpd_nip = "<?php echo Session::get("nip"); ?>";
			if (pib_nomor && pib_tanggal && importir && npwp_importir && ppjk && npwp_ppjk && pfpd && pfpd_nip) {
				var dataString = 'pib_nomor=' + pib_nomor + '&pib_tanggal=' + pib_tanggal + '&importir=' + importir + '&importir_npwp=' + npwp_importir + '&ppjk=' + ppjk + '&ppjk_npwp=' + npwp_ppjk + '&pfpd=' + pfpd + '&pfpd_nip=' + pfpd_nip;
				$.ajax({
					url: "ajax/ajax_npd_create.php",
					type: "POST",
					data: dataString,
					success: function(data) {
						console.log(data);
						$('#apps-contents').load('contents/' + 'npd_pfpd' + '.php');
					},
					error: function(data) {
						console.log(data);
					}
				})
			} else {
				alert('Ada data yang belum diisi');
			}
			return false;
		})
	});

</script>

