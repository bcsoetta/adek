<?php

$filepath = realpath(dirname(__FILE__));
include_once ($filepath. '/../lib/Session.php');
Session::init();

include("../ajax/ajax_conn.php");

if (isset($_POST["action"])) {

	if ($_POST['action'] == "edit") {

		$uploader_id = Session::get("id");

		$action_type = $_POST['action']; 
		$file_name = md5($_FILES['files']['name']);
		$file_type = $_FILES['files']['type'];
		$file_tmp_name = $_FILES['files']['tmp_name'];
		$file_size = $_FILES['files']['size'];
		$file_error = $_FILES['files']['error'];
		
		$type = strtoupper($_POST['doc_type']);
		$doc_id = $_POST['doc_id'];

		$pib_nomor = $_POST['pib_nomor'];
		$pib_tanggal_x = $_POST['pib_tanggal'];
		$pib_tanggal = date("Y-m-d", strtotime($pib_tanggal_x));
		$file_info = strtoupper($_POST['file_info']);

		if ($type == '1') {
			$doc_desc = "PACKING LIST";
		}

		if ($type == '2') {
			$doc_desc = "INVOICE";
		}

		if ($type == '3') {
			$doc_desc = "MASTER AIR WAYBILL";
		}

		if ($type == '4') {
			$doc_desc = "HOUSE AIR WAYBILL";
		}

		if ($type == '5') {
			$doc_desc = strtoupper($_POST['doc_type_lain']);
			if ($doc_desc === "") {
				echo "55";
				die();
			}
		}

		// Get file extension
		$path = $_FILES['files']['name'];
		$ext = pathinfo($path, PATHINFO_EXTENSION);

		$file_name_edit = $file_name . '_' . $doc_id;
		// echo $file_name_edit;

		$sql = mysqli_query($conn, "SELECT a.id, a.filename FROM dokap a WHERE id = '$doc_id'");

		while ($r = mysqli_fetch_array($sql)) {
			$file_name_del = $r['filename'] . '.' . $ext;
			// echo $file_name_del;
			$dir = $_SERVER['DOCUMENT_ROOT'] . '/adek/uploads/';
			$file_to_del = $dir . $file_name_del;
			// echo $file_to_del;
			$del_file = unlink($file_to_del);

			if ($del_file) {
				$query = "UPDATE dokap SET filename = '$file_name_edit', uploader_id = '$uploader_id', upload_timestamp = CURRENT_TIMESTAMP, type = '$type', type_desc = '$doc_desc', file_info = '$file_info' WHERE id = '$doc_id'";
				if (mysqli_query($conn, $query)) {
					move_uploaded_file($file_tmp_name, $dir . $file_name_edit . '.' . $ext);
					$history = mysqli_query($conn, "INSERT INTO loginfo (user_id, pib_nomor, pib_tanggal, action, action_detail, action_timestamp) VALUES ('$uploader_id', '$pib_nomor', '$pib_tanggal', 'EDIT', 'EDIT DOKUMEN PADA TABEL DOKAP ROW KE $doc_id', CURRENT_TIMESTAMP) ");
					echo 'Document updated into Database';
				} else {
					echo "Ops! failed.";
				}
			}
		}
	}

	if ($_POST['action'] == 'kirim') {

		$user_id = Session::get("id");
		$pib_nomor = $_POST['pib_nomor'];
		$pib_tanggal = $_POST['pib_tanggal'];
		$pib_tanggal_x = date("Y-m-d", strtotime($pib_tanggal));
		$pernyataan = $_POST['pernyataan'];
		// echo $pib_tanggal_x;

		$sql = "UPDATE dokap a SET a.`status` = '2' WHERE a.pib_nomor = '$pib_nomor' AND a.pib_tanggal = '$pib_tanggal_x'";
		$query = mysqli_query($conn, $sql);
		if ($query) {
			$history = mysqli_query($conn, "INSERT INTO loginfo (user_id, pib_nomor, pib_tanggal, action, action_detail, action_timestamp, pernyataan) VALUES ('$user_id', '$pib_nomor', '$pib_tanggal', 'DIKIRIM', 'DOKUMEN DIKIRIM', CURRENT_TIMESTAMP, '$pernyataan') ");
			echo "Document sent successfully.";
		} else {
			echo "Ops! failed.";
		}
	}

	if ($_POST["action"] == "remove") {

	    $id_del = $_POST['doc_id'];
	    $user_id = Session::get("id");
		$pib_nomor = $_POST['pib_nomor'];
		$pib_tanggal = $_POST['pib_tanggal'];
		$pib_tanggal_x = date("Y-m-d", strtotime($pib_tanggal));

		$sql = mysqli_query($conn, "SELECT a.id, a.filename FROM dokap a WHERE id = '$id_del'");

		while ($r = mysqli_fetch_array($sql)) {
			$file_name_del = $r['filename'] . '.pdf';
			$dir = $_SERVER['DOCUMENT_ROOT'] . '/adek/uploads/';
			$file_to_del = $dir . $file_name_del;
			$del_file = unlink($file_to_del);

			if ($del_file) {
				$query = "DELETE FROM dokap WHERE id = '$id_del'";
				if (mysqli_query($conn, $query)) {
					$history = mysqli_query($conn, "INSERT INTO loginfo (user_id, pib_nomor, pib_tanggal, action, action_detail, action_timestamp) VALUES ('$user_id', '$pib_nomor', '$pib_tanggal', 'REMOVE', 'REMOVE DOKUMEN PADA TABEL DOKAP ROW KE $id_del', CURRENT_TIMESTAMP) ");
					echo 'Document removed from database';
				} else {
					echo "Ops! failed.";
				}
			}
		}
	}
}