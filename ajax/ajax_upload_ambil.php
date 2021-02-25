<?php

$filepath = realpath(dirname(__FILE__));
include_once ($filepath. '/../lib/Session.php');
Session::init();

$action = $_POST['action'];
$pib_nomor = $_POST['pib_nomor'];
$pib_tanggal = $_POST['pib_tanggal'];

$receiver_id = Session::get("id");
$user_id = Session::get("id");

include("../ajax/ajax_conn.php");


if ($action === "ambil_terima") {
	
	$check = mysqli_query($conn, "SELECT a.`status` FROM dokap a WHERE a.pib_nomor = '$pib_nomor' AND a.pib_tanggal = '$pib_tanggal' LIMIT 1 ");

	while ($r = mysqli_fetch_array($check)) {
		if ($r['status'] == '2') {
			$sql = mysqli_query($conn, "UPDATE dokap SET `status` = '3', pendok_id = '$user_id' WHERE pib_nomor = '$pib_nomor' AND pib_tanggal = '$pib_tanggal'");
			if ($sql) {
				$history = mysqli_query($conn, "INSERT INTO loginfo (user_id, pib_nomor, pib_tanggal, action, action_detail, action_timestamp) VALUES ('$user_id', '$pib_nomor', '$pib_tanggal', 'DIAMBIL', 'DOKUMEN DIAMBIL OLEH PEMERIKSA DOKUMEN', CURRENT_TIMESTAMP) ");
				echo "1";
			}
		} else {
			echo "2";
		}
	}

	
} else if ($action === "ambil_pending") {

} else if ($action === "ambil_selesai") {
	
} else if ($action === "status_detil") {
	
} else if ($action === "get_all_selesai_byid") {
	
} else if ($action === "ambil_redist") {
	// print_r($_POST);
	$pfpd_id = $_POST['pfpd_id'];

	// Update tabel dokap
	$query1 = mysqli_query($conn, "UPDATE dokap a SET a.pfpd_id = '$user_id' WHERE a.pib_nomor = '$pib_nomor' AND a.pib_tanggal = '$pib_tanggal'");
	
	if ($query1) {
		// Insert into table redist
		$query2 = mysqli_query($conn, "INSERT INTO redist (pib_nomor, pib_tanggal, pfpd_lama_id, pfpd_baru_id, redistributor_id, redist_timestamp) VALUES ('$pib_nomor', '$pib_tanggal', '$pfpd_id', '$user_id', '$user_id', CURRENT_TIMESTAMP)");
		echo $query2;
		var_dump($query2);
		if ($query2) {
			// Insert into tabel loginfo
			$history = mysqli_query($conn, "INSERT INTO loginfo (user_id, pib_nomor, pib_tanggal, action, action_detail, action_timestamp) VALUES ('$user_id', '$pib_nomor', '$pib_tanggal', 'REDIST', 'DOKUMEN DIREDIST KE PFPD BARU', CURRENT_TIMESTAMP) ");
			echo 'DOKUMEN BERHASIL DIREDIST';
		}
	}
	
} else if ($action === "konfirmasi") {
	// print_r($_POST);
	$konfirmasi = $_POST['konfirmasi'];
	$pfpd_id = $_POST['pfpd_id'];
	$customer_id = $_POST['customer_id'];

	$konf_status = mysqli_query($conn, "UPDATE dokap SET `status` = '5' WHERE pib_nomor = '$pib_nomor' AND pib_tanggal = '$pib_tanggal'");

	if ($konf_status) {

		$konf = mysqli_query($conn, "INSERT INTO konfirmasi (pib_nomor, pib_tanggal, pfpd_id, customer_id, konfirmasi, konf_timestamp, sender_id) VALUES ('$pib_nomor', '$pib_tanggal', '$pfpd_id', '$customer_id', '$konfirmasi', CURRENT_TIMESTAMP, '$user_id')");

		if ($konf) {
			$last_id = mysqli_query($conn, "SELECT LAST_INSERT_ID() id");
			while ($id = mysqli_fetch_array($last_id)) {
				$idx = $id['id'];
			}
			$history = mysqli_query($conn, "INSERT INTO loginfo (user_id, pib_nomor, pib_tanggal, action, action_detail, action_timestamp, konf_id) VALUES ('$user_id', '$pib_nomor', '$pib_tanggal', 'KONFIRMASI', 'KONFIRMASI PERMINTAAN DOKUMEN', CURRENT_TIMESTAMP, '$idx') ");
			echo 'KONFIRMASI BERHASIL DIKIRIM';
		}
	}

} else if ($action === "konf_update_status") {
	$konf_id = $_POST['konf_id'];
	$user_id = $_POST['user_id'];
	$konf = mysqli_query($conn, "UPDATE konfirmasi SET status = '1' WHERE id = '$konf_id'");
	if ($konf) {
		$unread = mysqli_query($conn, "SELECT COUNT(a.id) jum FROM konfirmasi a WHERE a.customer_id = '$user_id' AND a.`status` = 0");
		$read = mysqli_query($conn, "SELECT COUNT(a.id) jum FROM konfirmasi a WHERE a.customer_id = '$user_id' AND a.`status` = 1");
		while ($r = mysqli_fetch_array($unread)) {
			$unread_stat = $r['jum'];
		}
		while ($r = mysqli_fetch_array($read)) {
			$read_stat = $r['jum'];
		}
		$all_stat = $unread_stat + $read_stat;
		echo "Konfirmasi (<b>$unread_stat</b>/$all_stat)";
	}

} else if ($action === "konf_update_reply") {
	$konf_id = $_POST['konf_id'];
	$konf = mysqli_query($conn, "UPDATE konfirmasi SET status = '1' WHERE id = '$konf_id'");
	if ($konf) {
		$unread = mysqli_query($conn, "SELECT COUNT(a.id) jum FROM konfirmasi a WHERE a.`status` = 0");
		$read = mysqli_query($conn, "SELECT COUNT(a.id) jum FROM konfirmasi a WHERE a.`status` = 1");
		while ($r = mysqli_fetch_array($unread)) {
			$unread_stat = $r['jum'];
		}
		while ($r = mysqli_fetch_array($read)) {
			$read_stat = $r['jum'];
		}
		$all_stat = $unread_stat + $read_stat;
		echo "Konfirmasi (<b>$unread_stat</b>/$all_stat)";
	}
	
} else if ($action === "konf_update_reply_process") {
	$konf_id = $_POST['konf_id'];
	$konfirmasi = $_POST['konfirmasi'];
	$pfpd_id = $_POST['pfpd_id'];
	$customer_id = $_POST['customer_id'];

	$reply = mysqli_query($conn, "INSERT INTO konfirmasi_replies (konfirmasi_id, reply, reply_timestamp, sender_id, status) VALUES ('$konf_id', '$konfirmasi', CURRENT_TIMESTAMP, '$user_id', '1')");

	if ($reply) {
		$last_id = mysqli_query($conn, "SELECT LAST_INSERT_ID() id");
		while ($id = mysqli_fetch_array($last_id)) {
			$idx = $id['id'];
		}
	
		$last_reply = mysqli_query($conn, "SELECT a.id, a.konfirmasi_id, a.reply, DATE_FORMAT(a.reply_timestamp, '%d-%m-%Y %H:%i:%s') reply_timestamp, b.name, b.`level` FROM konfirmasi_replies a INNER JOIN users b ON a.sender_id = b.id WHERE a.id = '$idx'");

		$konf = mysqli_query($conn, "UPDATE konfirmasi SET status = '0' WHERE id = '$konf_id'");

		$history = mysqli_query($conn, "INSERT INTO loginfo (user_id, pib_nomor, pib_tanggal, action, action_detail, action_timestamp, konf_id) VALUES ('$user_id', '$pib_nomor', '$pib_tanggal', 'KONFIRMASI_REPLY', 'REPLY KONFIRMASI PERMINTAAN DOKUMEN', CURRENT_TIMESTAMP, '$konf_id') ");

		$data = array();
		while ($r = mysqli_fetch_assoc($last_reply)) {
			$data[] = $r;
		}

		print json_encode($data);
	}
} else if ($action === "konf_update_pfpd_reply") {

}








