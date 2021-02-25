<?php

$filepath = realpath(dirname(__FILE__));
include_once ($filepath. '/../lib/Session.php');
Session::init();
include_once ($filepath.'/../lib/Database.php');
include_once ($filepath.'/../helpers/Format.php');
include_once ($filepath . '/../classes/Apps.php');

spl_autoload_register(function($class) {
	include_once "classes/" . $class . ".php";
});

$db = new Database();
$fm = new Format();
$usr = new User();
$apps = new Apps();

?>

<link rel="shortcut icon" type="image/x-icon" href="images/cd-burning-application.png">
<script type="text/javascript" src="assets/jquery-easyui-1.5.2/jquery.min.js"></script>
<script type="text/javascript" src="assets/jquery-easyui-1.5.2/jquery.easyui.min.js"></script>
<script src="js/apps.js"></script>