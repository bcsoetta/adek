<?php

$filepath = realpath(dirname(__FILE__));
include_once ($filepath. '/../lib/Session.php');
Session::init();

$user_id = Session::get("id");
$pib_nomor = $_POST['pib_nomor'];
$pib_tanggal = $_POST['pib_tanggal'];
$action_detail = strtoupper($_POST['action_detail']);

include("../ajax/ajax_conn.php");

// print_r($_POST);

$query = mysqli_query($conn, "UPDATE dokap SET `status` = '40' WHERE pib_nomor = '$pib_nomor' AND pib_tanggal = '$pib_tanggal' ");

if ($query) {
	$history = mysqli_query($conn, "INSERT INTO loginfo (user_id, pib_nomor, pib_tanggal, action, action_detail, action_timestamp) VALUES ('$user_id', '$pib_nomor', '$pib_tanggal', 'REJECT', 'REJECT, $action_detail', CURRENT_TIMESTAMP) ");
	if ($history) {
		echo "The documents rejected.";
	}
}

