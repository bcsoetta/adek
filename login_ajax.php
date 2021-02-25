<?php

$filepath = realpath(dirname(__FILE__));
include_once ($filepath . '/classes/User.php');
$usr = new User();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	$username = $_POST['username'];
	$password = md5($_POST['password']);
	$userLog = $usr->userLogin($username, $password);
}