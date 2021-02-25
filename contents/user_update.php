<style>
	.clearfix:after {
		visibility: hidden;
		display: block;
		font-size: 0;
		content: " ";
		clear: both;
		height: 0;
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
		margin-bottom: 10px;
	}
	.uc1 {
		display: inline-block;
		margin-right: 10px;
	}
	.uc2 {
		padding: 3px 5px;
		width: 200px;
	}
	#uc-update {
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
	#level_id {
		//width: 160px;
	}
	.status_active {
        color: green;
    }
    .status_inactive {
        color: red;
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

$id = $_GET['id'];
$data = $usr->getUser($id);

?>

<div id="user-create-wrapper1">
	<div id="user-create-wrapper2">
		<p style="font-weight: 700;"><i class="fa fa-tags" aria-hidden="true"></i>&nbsp;&nbsp;~ Update User</p>
		<br>
		<div class="uc1x">
			<div class="uc1">Username</div>
			<div><input class="uc2" type="text" id="username" value="<?php echo $data['username'] ?>" disabled></div>
		</div>
		<div class="uc1x">
			<div class="uc1">Password</div>
			<div><input class="uc2" type="password" id="password" value="<?php echo $data['password'] ?>"></div>
		</div>

		<div class="clearfix"></div>

		<div class="uc1">
			<div class="uc1">Nama</div>
			<div><input style="width: 250px;" class="uc2" type="text" id="name" value="<?php echo $data['name'] ?>"></div>
		</div>
		<div class="uc1">
			<div class="uc1">NIP</div>
			<div><input class="uc2" type="text" id="nip" value="<?php echo $data['nip'] ?>"></div>
		</div>

		<div class="clearfix"></div>

		<div class="uc1">
			<div class="uc1">Unit / Seksi</div>
			<div>
				<input style="width: 400px;" class="uc2" type="text" id="unit" value="<?php echo $data['unit_long'] ?>">
				<input id="unit_id" value="<?php echo $data['unit_id'] ?>" type="hidden">
			</div>
		</div>

		<div class="uc1">
			<div class="uc1">Pangkat / Gol.</div>
			<div>
				<input class="uc2" type="text" id="pangkat" value="<?php echo $data['pangkat_golx'] ?>">
				<input class="uc2" type="hidden" id="pangkat_id" value="<?php echo $data['pangkat_gol'] ?>">
			</div>
		</div>

		<div class="clearfix"></div>

		<div class="uc1">
			<div class="uc1">Jabatan</div>
			<div>
				<input style="width: 400px;" class="uc2" type="text" id="jabatan" value="<?php echo $data['jabatan'] ?>">
				<input id="jabatan_id" value="<?php echo $data['jabatan_id'] ?>" type="hidden">
			</div>
		</div>
		<div class="uc1">
			<div class="uc1">Level</div>
			<input id="level_idx" value="<?php echo $data['level'] ?>" type="hidden">
			<div>
				<select id="level_id">
					<option value="1">Administrator</option>
					<option value="2">Pengguna Jasa</option>
					<option value="3">Petugas Pendok</option>
					<option value="4">Pemeriksa Dokumen</option>
				</select>
			</div>
		</div>
		<div class="clearfix"></div>
		<div class="uc1">
			<div>
				STATUS:  
				<?php if ($data['status_id'] == 100) { ?>
				<span style="color: green;"><?php echo $data['status'] ?> <i class="fa fa-wifi status_active" aria-hidden="true"></i></span>
				<?php } elseif($data['status_id'] == 400) { ?>
				<span style="color: red;"><?php echo $data['status'] ?> <i class="fa fa-wifi status_inactive" aria-hidden="true"></i></span>
				<?php } ?>
				&nbsp;&nbsp;&nbsp;
				<select id="status_update">
					<option value="<?php echo $data['status_id'] ?>"> -- UPDATE STATUS -- </option>
					<?php if ($data['status_id'] == 400) { ?>
					<option value="100">ACTIVATE</option>
					<?php } if ($data['status_id'] == 100) { ?>
					<option value="400">DEACTIVATE</option>
					<?php } ?>
				</select>
			</div>
		</div>
	</div>
	<br>
	<div id="uc-update">UPDATE</div>
</div>

<br>
<a class="link-load-back" href="user" style="text-decoration: none;">Prev. [ &#8626; ]</a>

<script type="text/javascript" src="js/jquery_ui.js"></script>
<script type="text/javascript">

	$(document).ready(function() {
	    $('.link-load-back').click(function() {
			var page = $(this).attr('href');
			$('#apps-contents').load('contents/' + page + '.php');
			return false;
		});
	});

	$(function() {
		var availableTags = <?php include '../ajax/ajax_unit_seksi.php' ?>;
		$('#unit').autocomplete({
			minlength: 3,
			source: availableTags,
			focus: function(event, ui) {
				$('#unit').val(ui.item.value);
				return false;
			},
			select: function(event, ui) {
				$('#unit').val(ui.item.value);
				$('#unit').val(ui.item.unit_long);
				$('#unit_id').val(ui.item.id);
				return false;
			}
		});
	});

	$(function() {
		var availableTags = <?php include '../ajax/ajax_jabatan.php' ?>;
		$('#jabatan').autocomplete({
			minlength: 3,
			source: availableTags,
			focus: function(event, ui) {
				$('#jabatan').val(ui.item.value);
				return false;
			},
			select: function(event, ui) {
				$('#jabatan').val(ui.item.value);
				$('#jabatan').val(ui.item.jabatan);
				$('#jabatan_id').val(ui.item.jabatan_kode);
				return false;
			}
		});
	});

	$(function() {
		var availableTags = <?php include '../ajax/ajax_pangkat.php' ?>;
		$('#pangkat').autocomplete({
			minlength: 3,
			source: availableTags,
			focus: function(event, ui) {
				$('#pangkat').val(ui.item.value);
				return false;
			},
			select: function(event, ui) {
				$('#pangkat').val(ui.item.value);
				$('#pangkat').val(ui.item.pangkat_gol);
				$('#pangkat_id').val(ui.item.id);
				return false;
			}
		});
	});

	$(document).ready(function() {
		var level_idx = $("#level_idx").val();
		$('#level_id option[value="'+level_idx+'"]').attr("selected", "selected");
	});

	$(document).ready(function() {
		$("#uc-update").on("click", function() {
			var id = "<?php echo $id ?>";
			var username = $("#username").val();
			var password = $("#password").val();
			var name = $("#name").val();
			var nip = $("#nip").val();
			var unit_id = $("#unit_id").val();
			var pangkat_id = $("#pangkat_id").val();
			var jabatan_id = $("#jabatan_id").val();
			var level_id = $('#level_id').val();
			var status_update = $("#status_update").val();
			var dataString = 'username=' + username + '&password=' + password + '&name=' + name + '&nip=' + nip + '&unit_id=' + unit_id + '&jabatan_id=' + jabatan_id + '&pangkat_id=' + pangkat_id + '&jabatan_id=' + jabatan_id + '&level_id=' + level_id + '&status_update=' + status_update + '&id=' + id;

			$.ajax({
				url: "ajax/ajax_user_update.php",
				type: "POST",
				data: dataString,
				success: function(data) {
					console.log(data);
					$('#apps-contents').load('contents/' + 'user' + '.php');
				},
				error: function(data) {
					console.log(data);
				}
			});
			return false;
		});
	});

</script>

