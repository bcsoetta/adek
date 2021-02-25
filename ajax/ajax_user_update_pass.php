<?php

$pass_new = md5($_POST['pass_new']);
$pass_confirm = $_POST['pass_confirm'];
$user_id = $_POST['user_id']; 

include("../ajax/ajax_conn.php");

$query = mysqli_query($conn, "UPDATE users SET password = '$pass_new' WHERE id = '$user_id' ");

if ($query) {
	echo "Congrats! Password updated.";
}