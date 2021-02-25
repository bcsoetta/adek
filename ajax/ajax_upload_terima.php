<?php

$filepath = realpath(dirname(__FILE__));
include_once ($filepath. '/../lib/Session.php');
Session::init();

$file_name = md5($_FILES['tt_file']['name']);
$file_type = $_FILES['tt_file']['type'];
$file_tmp_name = $_FILES['tt_file']['tmp_name'];
$file_size = $_FILES['tt_file']['size'];
$file_error = $_FILES['tt_file']['error'];

// Get file extension
$path = $_FILES['tt_file']['name'];
$ext = pathinfo($path, PATHINFO_EXTENSION);

// Upload directory
$dir = $_SERVER['DOCUMENT_ROOT'] . '/adek/uploads/';

$pib_nomor = $_POST['pib_nomor'];
$pib_tanggal = $_POST['pib_tanggal'];
$pfpd_id = $_POST['pfpd_id'];
$npd = $_POST['npd'];

$receiver_id = Session::get("id");

// print_r($_FILES['tt_file']);
// print_r($_POST);

include("../ajax/ajax_conn.php");

$id = mysqli_query($conn, "SELECT MAX(id) id FROM tanda_terima");

while ($r = mysqli_fetch_array($id)) {

	if ($r['id'] == null) {
		$idx = 1;
	}
	if ($r['id'] != null) {
		$idx = $r['id'] + 1;
	}

	$file_name_x = $file_name . '_' . $idx . '.' . $ext;
	// echo $file_name_x;

	$check = mysqli_query($conn, "SELECT a.`status` FROM dokap a WHERE a.pib_nomor = '$pib_nomor' AND a.pib_tanggal = '$pib_tanggal' LIMIT 1 ");

	while ($x = mysqli_fetch_array($check)) {
		if ($x['status'] != '3') {
			echo "You can't send this documents!";
		}
		if ($x['status'] == '3') {
			$terima = mysqli_query($conn, "INSERT INTO tanda_terima (pib_nomor, pib_tanggal, filename, receiver_id, receive_timestamp) VALUES ('$pib_nomor', '$pib_tanggal', '$file_name_x', '$receiver_id', CURRENT_TIMESTAMP) ");
			if ($terima) {
				$update_status = mysqli_query($conn, "UPDATE dokap SET `status` = '4', pfpd_id = '$pfpd_id' WHERE pib_nomor = '$pib_nomor' AND pib_tanggal = '$pib_tanggal' ");
				if ($update_status) {
					move_uploaded_file($file_tmp_name, $dir . $file_name_x);
					$history = mysqli_query($conn, "INSERT INTO loginfo (user_id, pib_nomor, pib_tanggal, action, action_detail, action_timestamp) VALUES ('$receiver_id', '$pib_nomor', '$pib_tanggal', 'DITERIMA', 'DOKUMEN DITERIMA', CURRENT_TIMESTAMP) ");
					echo "Dokumen berhasil diterima!";

					if ($npd != "") {
						$qNPD = mysqli_query($conn, "UPDATE npdx SET `status` = 'DITERIMA' WHERE pib_nomor = '$pib_nomor' AND pib_tanggal = '$pib_tanggal' ");
					}
					
				} else {
					echo "Ops! failed.";
				}
				
			}
		}
	}
}



