<?php

$filepath = realpath(dirname(__FILE__));
include_once ($filepath. '/../lib/Session.php');
Session::init();

$user_id = Session::get("id");

if ($user_id == 0 OR $user_id == null OR $user_id == false) { 
    Session::redirect();
}

?>

<p id="pageTitle" hidden>&nbsp;&nbsp;Update Password</p>

<style type="text/css">
	#update_pass_wrapper {
		padding: 10px;
		border: 1px solid #ddd;
	}
	#update_pass_btn {
		border: 1px solid #ddd;
	    padding: 2px 5px;
	    cursor: pointer;
	    background-color: #e4e4e4;
	    box-shadow: 1px 1px 1px 0px #7e7e7e;
	    display: inline-block;
	}
	#pass_new, #pass_confirm, #update_pass_btn {
		margin-top: 10px;
	}
</style>

<div id="update_pass_wrapper">
	<div>Password Baru &nbsp;&nbsp;<input id="pass_new" type="password" name="pass_new"></div>
	<div>Konfirmasi Password Baru &nbsp;&nbsp;<input id="pass_confirm" type="password" name="pass_confirm"></div>
	<input type="hidden" name="user_id" value="<?php echo $user_id ?>" id="user_id">
	<div id="update_pass_btn">UPDATE</div>
</div>

<script type="text/javascript">

	$(document).ready(function() {
		$("#update_pass_btn").on("click", function() {

			var pass_new = $("#pass_new").val();
			var pass_confirm = $("#pass_confirm").val();
			var user_id = $("#user_id").val();

			if (pass_new == "") {
				alert("Password baru belum diisi!");
			}

			if (pass_confirm == "") {
				alert("Konfirmasi password baru belum diisi!");
			}
			
			if (pass_confirm !== pass_new) {
				alert("Password tidak sama!");
			}

			if (pass_new !== "" && pass_confirm !== "" && (pass_confirm === pass_new)) {

				var dataString = "pass_new=" + pass_new + "&pass_confirm=" + pass_confirm + "&user_id=" + user_id;
				var r = confirm("Are you sure want to update your password?");

				if (r == true) {
					
					$.ajax({
						url: "../adek/ajax/ajax_user_update_pass.php",
						method: "POST", 
						data: dataString,
						success: function(data) {
							alert(data);
							window.location.href = '?action=logout';
						},
						error: function(data) {

						}
					});

				} else {
				    alert("You pressed Cancel!");
				}
			}

			

			return false;

		});
	});

</script>
