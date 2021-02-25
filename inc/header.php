<?php

$filepath = realpath(dirname(__FILE__));
include_once ($filepath . '/../lib/Session.php');
Session::init();
include_once ($filepath . '/../lib/Database.php');
include_once ($filepath . '/../helpers/Format.php');
include_once ($filepath . '/../classes/Apps.php');
include_once ($filepath . '/../classes/User.php');

spl_autoload_register(function($class) {
	include_once "classes/" . $class . ".php";
});

$db = new Database();
$fm = new Format();
$usr = new User();
$apps = new Apps();

if (isset($_GET['action']) && $_GET['action'] == 'logout') {
	Session::destroy();
	header("Location:index.php");
	exit();
}

?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>ADEK - APLIKASI DOKUMEN PELENGKAP PIB</title>
    <link rel="shortcut icon" type="image/x-icon" href="images/cd-burning-application.png">
    <link rel="stylesheet" type="text/css" href="css/apps.css">
    <link rel="stylesheet" type="text/css" href="css/dokap.css">
    <link rel="stylesheet" type="text/css" href="assets/jquery-easyui-1.5.2/themes/metro/easyui.css">
    <link rel="stylesheet" type="text/css" href="assets/jquery-easyui-1.5.2/themes/icon.css">
    <link rel="stylesheet" type="text/css" href="css/jquery-ui.min.css">
    <link rel="stylesheet" href="assets/font-awesome-4.7.0/css/font-awesome.min.css">
    
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script type="text/javascript">
        var user_level = '<?php echo Session::get("level") ?>';
    </script>
    <script src="assets/jquery-easyui-1.5.2/jquery.min.js"></script>
    <script src="assets/jquery-easyui-1.5.2/jquery.easyui.min.js"></script>
    <script src="js/Chart.bundle.js"></script>
    <script src="js/apps.js"></script>
    
</head>
<body>
    <div class="easyui-layout" style=" width: 99.99%; min-height:650px; margin: auto;">
        <div class="apps-header" data-options="region:'north'" style="padding: 10px 28px 10px 28px; height: 55px; background-color: #f9f9f9;">
            <div class="app-lt">
                <div class="app-lt1 app-lta">
                    <a href="/adek/apps.php">
                    <img class="app-lt-logo" src="images/upload.svg">
                    &nbsp;&nbsp;</a>
                </div>
                <div class="app-lt1 app-ltb">
                    Hi, <b><?php echo Session::get("name") ?></b>! &nbsp;
                </div>
            </div>
        </div>
