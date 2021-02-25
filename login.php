<?php 

include 'inc/header_login.php';
Session::destroy();
Session::checkLogin();

?>

<title>ADEK - APLIKASI DOKUMEN PELENGKAP PIB</title>
<style type="text/css">

	.apps-loginx {
		height: 22px;
		margin-left: auto;
		margin-right: auto;
		//width: 30%;
		padding: 10px;
		//border: 1px solid #ddd;
		text-align: center;
	}

	.apps-loginx input {
		padding: 5px;
		box-shadow: 1px 1px 1px 1px #006120;
	}

	input[name="username"], 
	input[name="password"] {
		width: 200px;
	}

	.apps-loginx-title {
		margin-top: 10%;
		margin-left: auto;
		margin-right: auto;
		width: 35%;
		padding: 10px;
		//border: 1px solid #ddd;
		text-align: center;
	}

	input[type="submit"] {

	}

	.logo-login img {
		width: 28px;
		line-height: 28px;
	}

</style>

<div class="apps-loginx-title">
	<div class="logo-login"><img src="images/upload.svg"></div>
	<br>
	[ Login to <b>ADEK</b> ]
</div>
<div class="apps-loginx">
	<form method="POST">
		<input id="username" type="text" name="username">
	    <input id="password" type="password" name="password">
	    <input id="login-submit" type="submit" value="SUBMIT">
	</form>
	<span class="empty" style="display: none;">Field must not be empty !</span>
	<span class="error" style="display: none;">Email or Password not matched !</span>
	<span class="disable" style="display: none;">User ID disabled !</span>
</div>