<?php

$filepath = realpath(dirname(__FILE__));
include_once ($filepath. '/../lib/Session.php');
Session::init();

if (isset($_POST["action"])) {

	include("../ajax/ajax_conn.php");

	if ($_POST["action"] == "insert") {

		// print_r($_POST);
		// die();

		$uploader_id = Session::get("id");
		$user_id = Session::get("id");
		$dir = $_SERVER['DOCUMENT_ROOT'] . '/adek/uploads/';

		$action_type = $_POST['action']; 
		$file_name = md5($_FILES['image']['name']);
		$file_type = $_FILES['image']['type'];
		$file_tmp_name = $_FILES['image']['tmp_name'];
		$file_size = $_FILES['image']['size'];
		$file_error = $_FILES['image']['error'];
		
		$pib_nomor = $_POST['pib_nomor'];
		$pib_tanggal_e = $_POST['pib_tanggal'];
		$jalur = $_POST['jalur'];
		$pib_tanggal = date("Y-m-d", strtotime($pib_tanggal_e));
		$importir_nama = strtoupper($_POST['importir_nama']);
		$shipper_nama = strtoupper($_POST['shipper_nama']);
		$type = strtoupper($_POST['doc_type']);
		$id_edit = $_POST['id_edit'];
		$file_info = strtoupper($_POST['file_info']);

		if ($pib_nomor === "") {
			echo "PIB TIDAK BOLEH KOSONG";
			die();
		}

		if ($pib_tanggal_e === "") {
			echo "TANGGAL PIB TIDAK BOLEH KOSONG";
			die();
		}

		if ($jalur === "") {
			echo "BELUM MEMLIIH JALUR PIB";
			die();
		}

		if ($importir_nama === "") {
			echo "NAMA INPORTIR TIDAK BOLEH KOSONG";
			die();
		}

		if ($shipper_nama === "") {
			echo "NAMA SHIPPER TIDAK BOLEH KOSONG";
			die();
		}

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
				echo "JENIS DOKUMEN TIDAK BOLEH KOSONG";
				die();
			}
		}

		// Get the file extension
		$path = $_FILES['image']['name'];
		$ext = pathinfo($path, PATHINFO_EXTENSION);

		if ($action_type == "insert") {

			$id = mysqli_query($conn, "SELECT MAX(id) id FROM dokap");

			while ($r = mysqli_fetch_array($id)) {

				if ($r['id'] == null) {
					$idx = 1;
				}
				if ($r['id'] != null) {
					$idx = $r['id'] + 1;
				}

				$file_name_x = $file_name . '_' . $idx;

				$check = mysqli_query($conn, "SELECT DISTINCT(a.pib_nomor) pib_nomor, a.pib_tanggal, a.`status`, a.uploader_id, a.pendok_id, a.pfpd_id FROM dokap a WHERE a.pib_nomor = '$pib_nomor' AND YEAR(a.pib_tanggal) = YEAR('$pib_tanggal')");
				
				// Get rows count
				$rows = mysqli_num_rows($check);

				if ($rows == 0) {
					$upfile = move_uploaded_file($file_tmp_name, $dir . $file_name_x . '.' . $ext);
					if ($upfile) {
						$query = "INSERT INTO dokap (pib_nomor, pib_tanggal, jalur, filename, uploader_id, upload_timestamp, type, type_desc, file_info, importir, shipper, status) VALUES ('$pib_nomor', '$pib_tanggal', '$jalur', '$file_name_x', '$uploader_id', CURRENT_TIMESTAMP, '$type', '$doc_desc', '$file_info', '$importir_nama', '$shipper_nama', '1')";
						if (mysqli_query($conn, $query)) {
							$history = mysqli_query($conn, "INSERT INTO loginfo (user_id, pib_nomor, pib_tanggal, action, action_detail, action_timestamp) VALUES ('$user_id', '$pib_nomor', '$pib_tanggal', 'UPLOAD', 'UPLOAD DOKUMEN PADA TABEL DOKAP ROW KE $idx', CURRENT_TIMESTAMP) ");
							echo 'DOKUMEN BERHASIL DIUPLOAD';
							die();
						} else {
							echo "GAGAL UPLOAD (db insert failed)";
							die();
						}
					} else {
						echo "GAGAL UPLOAD (file moving failed)";
						die();
					}
				}

				if ($rows > 0) {

					while($y = mysqli_fetch_array($check)) {
						$pib_nomor_check = $y['pib_nomor'];
						$pib_tanggal_check = $y['pib_tanggal'];
						$upId = $y['uploader_id'];
						$status = $y['status'];
						$pendok_idx = (string)$y['pendok_id'];
						$pfpd_idx = (string)$y['pfpd_id'];
					}

					// Cek siapa yang upload
					if ($upId !== $uploader_id) {
						echo "BUKAN DOKUMEN ANDA, CEK LAGI";
						die();
					}

					if ($pib_tanggal !== $pib_tanggal_check) {
						echo "TANGGAL PIB TIDAK SESUAI DENGAN DATABASE";
						die();
					}

					if ($status == '1') {
						$upfile = move_uploaded_file($file_tmp_name, $dir . $file_name_x . '.' . $ext);
						if ($upfile) {
							$query = "INSERT INTO dokap (pib_nomor, pib_tanggal, jalur, filename, uploader_id, upload_timestamp, type, type_desc, file_info, importir, shipper, status) VALUES ('$pib_nomor', '$pib_tanggal', '$jalur', '$file_name_x', '$uploader_id', CURRENT_TIMESTAMP, '$type', '$doc_desc', '$file_info', '$importir_nama', '$shipper_nama', '1')";
							if (mysqli_query($conn, $query)) {
								$history = mysqli_query($conn, "INSERT INTO loginfo (user_id, pib_nomor, pib_tanggal, action, action_detail, action_timestamp) VALUES ('$user_id', '$pib_nomor', '$pib_tanggal', 'UPLOAD', 'UPLOAD DOKUMEN PADA TABEL DOKAP ROW KE $idx', CURRENT_TIMESTAMP) ");
								echo 'DOKUMEN BERHASIL DIUPLOAD';
								die();
							} else {
								echo "GAGAL UPLOAD (db insert failed-1)";
								die();
							}
						} else {
							echo "GAGAL UPLOAD (file moving failed-1)";
							die();
						}
					}

					if ($status == '40') {
						$upfile = move_uploaded_file($file_tmp_name, $dir . $file_name_x . '.' . $ext);
						if ($upfile) {
							$query = "INSERT INTO dokap (pib_nomor, pib_tanggal, jalur, filename, uploader_id, pendok_id, upload_timestamp, type, type_desc, file_info, importir, shipper, status) VALUES ('$pib_nomor', '$pib_tanggal', '$jalur', '$file_name_x', '$uploader_id', '$pendok_idx', CURRENT_TIMESTAMP, '$type', '$doc_desc', '$file_info', '$importir_nama', '$shipper_nama', '40')";
							if (mysqli_query($conn, $query)) {
								$history = mysqli_query($conn, "INSERT INTO loginfo (user_id, pib_nomor, pib_tanggal, action, action_detail, action_timestamp) VALUES ('$user_id', '$pib_nomor', '$pib_tanggal', 'UPLOAD', 'UPLOAD DOKUMEN PADA TABEL DOKAP ROW KE $idx', CURRENT_TIMESTAMP) ");
								echo 'DOKUMEN BERHASIL DIUPLOAD';
								die();
							} else {
								echo "GAGAL UPLOAD";
								die();
							}
						} else {
							echo "GAGAL UPLOAD";
							die();
						}
					}

					if ($status == '5') {
						$getKonf = mysqli_query($conn, "SELECT a.id, a.konf_timestamp ts1, CURRENT_TIMESTAMP ts2, TIMESTAMPDIFF(DAY, a.konf_timestamp, CURRENT_TIMESTAMP) days FROM konfirmasi a WHERE a.pib_nomor = '$pib_nomor' AND a.pib_tanggal = '$pib_tanggal' ORDER BY id DESC LIMIT 1");
						if (mysqli_num_rows($getKonf) > 0) {
							while ($konf_date = mysqli_fetch_array($getKonf)) {
								$time_diff = $konf_date['days'];
								if ($time_diff < 3) {
									$upfile = move_uploaded_file($file_tmp_name, $dir . $file_name_x . '.' . $ext);
									if ($upfile) {
										$query = "INSERT INTO dokap (pib_nomor, pib_tanggal, jalur, filename, uploader_id, pendok_id, pfpd_id, upload_timestamp, type, type_desc, file_info, importir, shipper, status) VALUES ('$pib_nomor', '$pib_tanggal', '$jalur', '$file_name_x', '$uploader_id', '$pendok_idx', '$pfpd_idx', CURRENT_TIMESTAMP, '$type', '$doc_desc', '$file_info', '$importir_nama', '$shipper_nama', '5')";
										if (mysqli_query($conn, $query)) {
											$history = mysqli_query($conn, "INSERT INTO loginfo (user_id, pib_nomor, pib_tanggal, action, action_detail, action_timestamp) VALUES ('$user_id', '$pib_nomor', '$pib_tanggal', 'UPLOAD', 'KONFIRMASI, UPLOAD DOKUMEN PADA TABEL DOKAP ROW KE $idx', CURRENT_TIMESTAMP) ");
											echo 'DOKUMEN BERHASIL DIUPLOAD';
											die();

										} else {
											echo "GAGAL UPLOAD";
											die();
										}
									} else {
										echo "GAGAL UPLOAD";
										die();
									}
								} else {
									echo "KONFIRMASI LEWAT 1 HARI";
									die();
								}
							}
						}					
					}

					if ($y['status'] !== '1' OR $y['status'] !== '5') {
						echo "PIB SUDAH PERNAH DIKIRIM";
						die();
					}
				}					
			}
		}
	}

	if ($_POST["action"] == "update") {

		$uploader_id = Session::get("id");

		$action_type = $_POST['action']; 
		$file_name = md5($_FILES['image']['name']);
		$file_type = $_FILES['image']['type'];
		$file_tmp_name = $_FILES['image']['tmp_name'];
		$file_size = $_FILES['image']['size'];
		$file_error = $_FILES['image']['error'];
		
		$pib_nomor = $_POST['pib_nomor'];
		$pib_tanggal = $_POST['pib_tanggal'];
		$importir_nama = strtoupper($_POST['importir_nama']);
		$shipper_nama = strtoupper($_POST['shipper_nama']);
		$type = strtoupper($_POST['doc_type']);
		$id_edit = $_POST['id_edit'];

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
		}

		// Get file extension
		$path = $_FILES['image']['name'];
		$ext = pathinfo($path, PATHINFO_EXTENSION);

		// print_r($_FILES['image']);
		// print_r($_POST);

		$file_name_edit = $file_name . '_' . $id_edit;

		// echo $file_name_edit;

		$sql = mysqli_query($conn, "SELECT a.id, a.filename FROM dokap a WHERE id = '$id_edit'");

		while ($r = mysqli_fetch_array($sql)) {
			$file_name_del = $r['filename'] . '.' . $ext;
			// echo $file_name_del;
			$dir = $_SERVER['DOCUMENT_ROOT'] . '/adek/uploads/';
			$file_to_del = $dir . $file_name_del;
			// echo $file_to_del;
			$del_file = unlink($file_to_del);

			if ($del_file) {
				$query = "UPDATE dokap SET filename = '$file_name_edit', uploader_id = '$uploader_id', upload_timestamp = CURRENT_TIMESTAMP, type = '$type', type_desc = '$doc_desc' WHERE id = '$id_edit'";
				if (mysqli_query($conn, $query)) {
					move_uploaded_file($file_tmp_name, $dir . $file_name_edit . '.' . $ext);
					$history = mysqli_query($conn, "INSERT INTO loginfo (user_id, pib_nomor, pib_tanggal, action, action_detail, action_timestamp) VALUES ('$uploader_id', '$pib_nomor', '$pib_tanggal', 'EDIT', 'EDIT DOKUMEN PADA TABEL DOKAP ROW KE $id_edit', CURRENT_TIMESTAMP) ");
					echo 'Document updated in the Database';
				} else {
					echo "Ops! failed.";
				}
			}
		}		
	}

	if ($_POST["action"] == "delete") {

		$id_del = $_POST['id'];
		$sql = mysqli_query($conn, "SELECT a.id, a.pib_nomor, a.pib_tanggal, a.filename FROM dokap a WHERE id = '$id_del'");

		while ($r = mysqli_fetch_array($sql)) {
			$file_name_del = $r['filename'] . '.pdf';
			$dir = $_SERVER['DOCUMENT_ROOT'] . '/adek/uploads/';
			$file_to_del = $dir . $file_name_del;
			$del_file = unlink($file_to_del);
			$pib_nomor = $r['pib_nomor'];
			$pib_tanggal = $r['pib_tanggal'];
			$user_id = Session::get("id");

			if ($del_file) {
				$query = "DELETE FROM dokap WHERE id = '$id_del'";
				if (mysqli_query($conn, $query)) {
					$history = mysqli_query($conn, "INSERT INTO loginfo (user_id, pib_nomor, pib_tanggal, action, action_detail, action_timestamp) VALUES ('$user_id', '$pib_nomor', '$pib_tanggal', 'REMOVE', 'REMOVE DOKUMEN PADA TABEL DOKAP ROW KE $id_del', CURRENT_TIMESTAMP) ");
					echo 'Document removed from Database';
				} else {
					echo "Ops! failed.";
				}
			}
		}
	}

	if ($_POST['action'] == 'send') {
		// print_r($_POST);
		$user_id = Session::get("id");
		$pib_nomor = $_POST['pib_nomor'];
		$pib_tanggal = $_POST['pib_tanggal'];
		$pib_tanggal_x = date("Y-m-d", strtotime($pib_tanggal));
		// echo $pib_tanggal_x;
		$sql = "UPDATE dokap a SET a.`status` = '2' WHERE a.pib_nomor = '$pib_nomor' AND a.pib_tanggal = '$pib_tanggal_x'";
		$query = mysqli_query($conn, $sql);

		if ($query) {
			$history = mysqli_query($conn, "INSERT INTO loginfo (user_id, pib_nomor, pib_tanggal, action, action_detail, action_timestamp) VALUES ('$user_id', '$pib_nomor', '$pib_tanggal_x', 'DIKIRIM', 'DOKUMEN DIKIRIM', CURRENT_TIMESTAMP) ");
			echo "Document sent successfully.";
		} else {
			echo "Ops! failed.";
		}
	}
}


