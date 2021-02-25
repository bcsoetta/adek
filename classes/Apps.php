<?php

$filepath = realpath(dirname(__FILE__));
include_once ($filepath . '/../lib/Session.php');
include_once ($filepath . '/../lib/Database.php');
include_once ($filepath . '/../helpers/Format.php');

$user_id = Session::get("id");

class Apps {
	private $db;
	private $fm;
	public function __construct() {
		$this->db = new Database();
		$this->fm = new Format();
	}

	// GET PIB DATA FROM DOKAP BY PIB AND DATE
	/* UPLOAD_STATUS_DETIL.PHP
	   UPLOAD_BROWSE_TERIMA_DETIL.PHP 
	   UPLOAD_BROWSE_PENDING_DETIL.PHP
	   UPLOAD_BROWSE_SELESAI_DETIL.PHP
	   UPLOAD_BROWSE_ALL_SELESAI_BYID_DETIL.PHP
	   UPLOAD_KONFIRMASI_PFPD_DETIL.PHP
	   UPLOAD_KONFIRMASI_DETIL.PHP */
	   
	function getDocs($pib_nomor, $pib_tanggal_x) {
		$pib_nomor = $_GET['pib_nomor'];
		$pib_tanggal = $_GET['pib_tanggal'];
		$query = "SELECT a.id, a.pib_nomor, a.pib_tanggal, DATE_FORMAT(a.pib_tanggal, '%d-%m-%Y') pib_tanggal_x, a.filename, a.`type`, a.type_desc, a.importir, a.shipper, a.upload_timestamp, a.uploader_id, a.pendok_id, a.pfpd_id, a.`status` FROM dokap a WHERE a.pib_nomor = '$pib_nomor' AND a.pib_tanggal = '$pib_tanggal'";
		$result = $this->db->select($query);
		return mysqli_fetch_array($result);
	}

	// GET ALL PIB BY PIB AND DATE
	/* UPLOAD_EDIT_REM_KIRIM_DETIL.PHP
	   UPLOAD_EDIT_REM_KIRIM_DETIL.PHP
	   UPLOAD_STATUS_PFPD_DETIL.PHP */
	function getDocsByPIB2($pib_nomor, $pib_tanggal_x) {
		$pib_nomor = $_GET['pib_nomor'];
		$pib_tanggal = $_GET['pib_tanggal'];
		$query = "SELECT a.id, a.pib_nomor, a.pib_tanggal, DATE_FORMAT(a.pib_tanggal,'%d-%m-%Y') pib_tanggal_x, a.filename, a.`type`, a.type_desc, a.importir, a.shipper, a.uploader_id, a.upload_timestamp, a.pfpd_id, a.`status` FROM dokap a WHERE a.pib_nomor = '$pib_nomor' AND a.pib_tanggal = '$pib_tanggal'";
		$result = $this->db->select($query);
		return mysqli_fetch_array($result);
	}

	// GET ALL PIB BY PIB AND DATE
	/* UPLOAD_EDIT_REM_KIRIM_DETIL.PHP
	   UPLOAD_EDIT_REM_KIRIM_DETIL.PHP 
	   UPLOAD_STATUS_DETIL.PHP
	   UPLOAD_BROWSE_TERIMA_DETIL.PHP
	   UPLOAD_BROWSE_PENDING_DETIL.PHP
	   UPLOAD_BROWSE_SELESAI_DETIL.PHP
	   UPLOAD_STATUS_PFPD_DETIL.PHP
	   UPLOAD_BROWSE_ALL_SELESAI_BYID_DETIL.PHP
	*/
	function getDocsByPIB($pib_nomor, $pib_tanggal_x) {
		$pib_nomor = $_GET['pib_nomor'];
		$pib_tanggal = $_GET['pib_tanggal'];
		$query = "SELECT a.id, a.pib_nomor, a.pib_tanggal, DATE_FORMAT(a.pib_tanggal, '%d-%m-%Y') pib_tanggal_x, a.filename, a.`type`, a.type_desc, a.file_info, a.importir, a.shipper, a.upload_timestamp, a.uploader_id, a.pendok_id, a.pfpd_id, a.`status` FROM dokap a WHERE a.pib_nomor = '$pib_nomor' AND a.pib_tanggal = '$pib_tanggal'";
		$result = $this->db->select($query);
		return $result;
	}

	function getDocsById($user_id) {
		$query = "SELECT COUNT(a.id) jum_dokumen, a.pib_nomor, DATE_FORMAT(a.pib_tanggal,'%d-%m-%Y') pib_tanggal, a.pib_tanggal pib_tanggal_x, a.importir, a.shipper FROM dokap a WHERE a.pib_nomor IN (SELECT DISTINCT (a.pib_nomor) pib_nomor FROM dokap a WHERE a.`status` = 4 AND a.pfpd_id = '$user_id' ORDER BY a.id DESC) GROUP BY a.pib_nomor";
		$result = $this->db->select($query);
		return $result;
	}

	// GET ALL DOCUMENTS BY UPLOADER ID
	/* UPLOAD_STATUS.PHP */
	function getDocsAllById($user_id) {
		$query = "SELECT DISTINCT src.pib_nomor, src.importir, src.shipper, src.jum_dokumen, src.`status`, src.name pendok, dst.name pfpd, src.uploader_id, DATE_FORMAT(src.pib_tanggal,'%d-%m-%Y') pib_tanggal, src.pib_tanggal pib_tanggal_x FROM (SELECT COUNT(a.pib_nomor) jum_dokumen, a.pib_nomor, a.pib_tanggal, b.name, b.nip, a.importir, a.shipper, a.uploader_id, CASE WHEN a.status = 1 THEN 'BELUM DIKIRIM' WHEN a.status = 2 THEN 'DIKIRIM' WHEN a.status = 3 THEN 'PEMERIKSAAN' WHEN a.status = 4 OR a.status = 5 THEN 'DITERIMA' WHEN a.status = 40 THEN 'REJECT' END AS status
			FROM dokap a LEFT JOIN users b ON a.pendok_id = b.id GROUP BY a.pib_nomor) src INNER JOIN (SELECT a.pib_nomor, a.pib_tanggal, b.name, b.nip FROM dokap a LEFT JOIN users b ON a.pfpd_id = b.id) dst ON src.pib_nomor = dst.pib_nomor WHERE src.pib_nomor = dst.pib_nomor AND src.pib_tanggal = dst.pib_tanggal AND src.uploader_id = '$user_id'";
		$result = $this->db->select($query);
		return $result;
	}

	// GET PIB DATA FROM DOKAP 
	/* UPLOAD_STATUS_PFPD.PHP */
	function getDocsAllByPfpdAll() {
		$query = "SELECT DISTINCT src.pib_nomor, src.importir, src.shipper, src.jum_dokumen, src.`status`, src.name pendok, dst.name pfpd, src.uploader_id, src.pfpd_id, DATE_FORMAT(src.pib_tanggal,'%d-%m-%Y') pib_tanggal, src.pib_tanggal pib_tanggal_x FROM (SELECT COUNT(a.pib_nomor) jum_dokumen, a.pib_nomor, a.pib_tanggal, b.name, b.nip, a.importir, a.shipper, a.uploader_id, a.pfpd_id, CASE WHEN a.status = 1 THEN 'BELUM DIKIRIM' WHEN a.status = 2 THEN 'DIKIRIM' WHEN a.status = 3 THEN 'PEMERIKSAAN' WHEN a.status = 4 OR a.status = 5 THEN 'DITERIMA' WHEN a.status = 40 THEN 'REJECT' END AS status
			FROM dokap a LEFT JOIN users b ON a.pendok_id = b.id GROUP BY a.pib_nomor) src INNER JOIN (SELECT a.pib_nomor, a.pib_tanggal, b.name, b.nip FROM dokap a LEFT JOIN users b ON a.pfpd_id = b.id) dst ON src.pib_nomor = dst.pib_nomor WHERE src.pib_nomor = dst.pib_nomor AND src.pib_tanggal = dst.pib_tanggal";
		$result = $this->db->select($query);
		return $result;
	}

	/* UPLOAD_KONFIRMASI.PHP */
	function getDocsKonfById($user_id) {
		$query = "SELECT 
			a.id konf_id, 
			DATE_FORMAT(a.konf_timestamp, '%d-%m-%Y %H:%i:%s') konf_timestamp,
			log_valid.action,
			log_valid.action_detail,
			log_valid.pib_nomor,
			log_valid.pib_tanggal,
			DATE_FORMAT(log_valid.pib_tanggal, '%d-%m-%Y') pib_tanggal_x,
			b.importir,
			b.shipper,
			c.name,
			a.status
		FROM 
			konfirmasi a
		INNER JOIN
			(
			SELECT
				action, action_detail, konf_id, pib_nomor, pib_tanggal
			FROM
				loginfo
			WHERE
				konf_id > 0
			) log_valid 
			ON
				log_valid.konf_id = a.id
		JOIN
			dokap b
			ON
				log_valid.pib_nomor = b.pib_nomor
				AND log_valid.pib_tanggal = b.pib_tanggal
		JOIN
			users c
			ON
				b.pfpd_id = c.id
		WHERE a.customer_id = '$user_id' 
		GROUP BY
			a.id
		ORDER BY a.konf_timestamp ASC";
		$result = $this->db->select($query);
		return $result;
	}

	// GET PIB KONFIRMASI BY PFPD
	function getDocsKonfByPfpdId($user_id) {
		$query = "SELECT 
			a.id konf_id, 
			DATE_FORMAT(a.konf_timestamp, '%d-%m-%Y %H:%i:%s') konf_timestamp,
			log_valid.action,
			log_valid.action_detail,
			log_valid.pib_nomor,
			log_valid.pib_tanggal,
			DATE_FORMAT(log_valid.pib_tanggal, '%d-%m-%Y') pib_tanggal_x,
			b.importir,
			b.shipper,
			c.name,
			a.status
		FROM 
			konfirmasi a
		INNER JOIN
			(
			SELECT
				action, action_detail, konf_id, pib_nomor, pib_tanggal
			FROM
				loginfo
			WHERE
				konf_id > 0
			) log_valid 
			ON
				log_valid.konf_id = a.id
		JOIN
			dokap b
			ON
				log_valid.pib_nomor = b.pib_nomor
				AND log_valid.pib_tanggal = b.pib_tanggal
		JOIN
			users c
			ON
				b.pfpd_id = c.id
		WHERE a.pfpd_id = '$user_id' 
		GROUP BY
			a.id
		ORDER BY a.konf_timestamp ASC";
		$result = $this->db->select($query);
		return $result;
	}

	function getPfpdNpd($user_nip) {
		$query = "SELECT COUNT(a.id) JUM FROM npdx a WHERE a.pfpd_nip = '$user_nip'";
		$result = $this->db->select($query);
		while($data = mysqli_fetch_array($result)) {
			return $data['JUM'];
		}
	}

	function getAllNpd($user_nip) {
		$query = "SELECT COUNT(a.id) JUM FROM npdx a WHERE a.importir_npwp = '$user_nip' OR a.ppjk_npwp = '$user_nip'";
		$result = $this->db->select($query);
		while($data = mysqli_fetch_array($result)) {
			return $data['JUM'];
		}
	}

	function getDiterimaNpd($user_nip) {
		$query = "SELECT COUNT(a.id) JUM FROM npdx a WHERE a.`status` = 'DITERIMA' AND (a.importir_npwp = '$user_nip' OR a.ppjk_npwp = '$user_nip')";
		$result = $this->db->select($query);
		while($data = mysqli_fetch_array($result)) {
			return $data['JUM'];
		}
	}

	function getStatUnread($user_id) {
		$query = "SELECT COUNT(a.id) JUM FROM konfirmasi a WHERE a.customer_id = '$user_id' AND a.`status` = 0";
		$result = $this->db->select($query);
		return $result;
	}

	function getStatRead($user_id) {
		$query = "SELECT COUNT(a.id) JUM FROM konfirmasi a WHERE a.customer_id = '$user_id'";
		$result = $this->db->select($query);
		return $result;
	}

	function getStatPfpd($user_id) {
		$query = "SELECT COUNT(a.id) JUM FROM konfirmasi a WHERE a.pfpd_id = '$user_id'";
		$result = $this->db->select($query);
		return $result;
	}

	// EDIT, REMOVE, KIRIM BY UPLOADER / UPLOAD_EDIT_REM_KIRIM.PHP
	function getDocsAllByIdStatus1($user_id) {
		$query = "SELECT COUNT(a.id) jum_dokumen, a.pib_nomor, DATE_FORMAT(a.pib_tanggal,'%d-%m-%Y') pib_tanggal, a.pib_tanggal pib_tanggal_x, a.importir, a.shipper, CASE WHEN a.status = 1 THEN 'BELUM DIKIRIM' WHEN a.status = 2 THEN 'DIKIRIM' WHEN a.status = 3 THEN 'PEMERIKSAAN' WHEN a.status = 4 OR a.status = 5 THEN 'DITERIMA' WHEN a.status = 40 THEN 'REJECT' END AS status FROM dokap a WHERE a.pib_nomor IN (SELECT DISTINCT (a.pib_nomor) pib_nomor FROM dokap a WHERE a.uploader_id = '$user_id' AND (a.`status` = 1 OR a.`status` = 40) ORDER BY a.id DESC) AND a.uploader_id = '$user_id' GROUP BY a.pib_nomor";
		$result = $this->db->select($query);
		return $result;
	}

	// GET PIB DITERIMA BY PFPD ID
	/* UPLOAD_BROWSE_ALL_SELESAI_BYID.PHP */
	function getDocsAllSelById($user_id) {
		$query = "SELECT src.jum_dokumen, dst.jum_konf, src.pib_nomor, src.pib_tanggal, src.pib_tanggal_x, src.importir, src.shipper, src.status
			FROM (
			SELECT
				COUNT(a.id) jum_dokumen, 
				a.pib_nomor, DATE_FORMAT(a.pib_tanggal,'%d-%m-%Y') pib_tanggal_x, 
				a.pib_tanggal pib_tanggal, 
				a.importir, 
				a.shipper, 
				CASE WHEN a.status = 1 THEN 'BELUM DIKIRIM' WHEN a.status = 2 THEN 'DIKIRIM' WHEN a.status = 3 THEN 'PEMERIKSAAN' WHEN a.status = 4 OR a.status = 5 THEN 'DITERIMA' WHEN a.status = 40 THEN 'REJECT' END AS `status`
			FROM dokap a
				WHERE a.pib_nomor IN (SELECT DISTINCT (a.pib_nomor) pib_nomor FROM dokap a 
				WHERE (a.status = 4 OR a.status = 5) AND a.pfpd_id = '$user_id' ORDER BY a.id DESC)
				GROUP BY a.pib_nomor) src
			LEFT JOIN (
				SELECT COUNT(a.id) jum_konf, a.pib_nomor, a.pib_tanggal
				FROM konfirmasi a
			GROUP BY a.pib_nomor) dst ON src.pib_nomor = dst.pib_nomor AND src.pib_tanggal = dst.pib_tanggal";
		$result = $this->db->select($query);
		return $result;
	}

	function getDocsAllSel($user_id) {
		$query = "SELECT COUNT(a.id) jum_dokumen, a.pib_nomor, DATE_FORMAT(a.pib_tanggal,'%d-%m-%Y') pib_tanggal, a.pib_tanggal pib_tanggal_x, a.importir, a.shipper, CASE WHEN a.status = 1 THEN 'BELUM DIKIRIM' WHEN a.status = 2 THEN 'DIKIRIM' WHEN a.status = 3 THEN 'PEMERIKSAAN' WHEN a.status = 4 THEN 'DITERIMA' WHEN a.status = 40 THEN 'REJECT' END AS status FROM dokap a WHERE a.pib_nomor IN (SELECT DISTINCT (a.pib_nomor) pib_nomor FROM dokap a WHERE a.status = 4 ORDER BY a.id DESC) GROUP BY a.pib_nomor";
		$result = $this->db->select($query);
		return $result;
	}

	// GET ALL PIB BY PFPD ID
	/* UPLOAD_BROWSE_SELESAI_REDIST.PHP */
	function getDocsAllSelRedist($user_id) {
		$query = "SELECT COUNT(a.id) jum_dokumen, a.pib_nomor, DATE_FORMAT(a.pib_tanggal,'%d-%m-%Y') pib_tanggal, a.pib_tanggal pib_tanggal_x, a.importir, a.shipper, CASE WHEN a.status = 1 THEN 'BELUM DIKIRIM' WHEN a.status = 2 THEN 'DIKIRIM' WHEN a.status = 3 THEN 'PEMERIKSAAN' WHEN a.status = 4 OR a.status = 5 THEN 'DITERIMA' WHEN a.status = 40 THEN 'REJECT' END AS `status`, b.name, b.nip, b.id
			FROM dokap a
			INNER JOIN users b ON a.pfpd_id = b.id
			WHERE a.pib_nomor IN (
			SELECT DISTINCT (a.pib_nomor) pib_nomor
			FROM dokap a
			WHERE a.status = 4 OR a.status = 5
			ORDER BY a.id DESC)
			GROUP BY a.pib_nomor";
		$result = $this->db->select($query);
		return $result;
	}

	// GET LOG INFO NY PIB AND DATE
	/* UPLOAD_STATUS_DETIL.PHP
	   UPLOAD_STATUS_PFPD_DETIL.PHP
	   UPLOAD_KONFIRMASI_PFPD_DETIL.PHP
	   UPLOAD_KONFIRMASI_DETIL.PHP */
	function getLogByPib($pib_nomor, $pib_tanggal) {
		$query = "SELECT src.user_id, src.level, src.pib_nomor, src.pib_tanggal, src.`action`, src.action_detail, DATE_FORMAT(src.action_timestamp, '%d-%m-%Y %H:%i:%s') action_timestamp, dst.filename, dst.receiver_id, src.konf_id
			FROM (
			SELECT a.id, a.user_id, a.pib_nomor, a.pib_tanggal, a.action, a.action_detail, a.action_timestamp, a.pernyataan, a.konf_id, u.level
			FROM loginfo a INNER JOIN users u ON a.user_id = u.id
			WHERE a.action = 'DIKIRIM' OR a.action = 'REJECT' OR a.action = 'DITERIMA' OR a.action = 'KONFIRMASI' AND u.level = 4
			ORDER BY a.action_timestamp ASC) src
			LEFT JOIN
			(
			SELECT *
			FROM tanda_terima b
			WHERE b.pib_nomor = '$pib_nomor' AND b.pib_tanggal = '$pib_tanggal'
			) dst ON src.pib_nomor = dst.pib_nomor
			WHERE src.pib_nomor = '$pib_nomor' AND src.pib_tanggal = '$pib_tanggal'
			ORDER BY src.action_timestamp ASC";
		$result = $this->db->select($query);
		return $result;
	}

	function getKonfByPib($konf_id) {
		$query = "SELECT a.id, a.pib_nomor, DATE_FORMAT(a.pib_tanggal, '%d-%m-%Y') pib_tanggal, a.pfpd_id, a.customer_id, a.konfirmasi, DATE_FORMAT(a.konf_timestamp, '%d-%m-%Y %H:%i:%s') konf_timestamp, b.name, b.nip FROM konfirmasi a INNER JOIN users b ON a.pfpd_id = b.id WHERE a.id = '$konf_id'";
		$result = $this->db->select($query);
		return $result;
	}

	/* UPLOAD_KONFIRMASI_PFPD_DETIL.PHP
	   UPLOAD_KONFIRMASI_DETIL.PHP */
	function getKonfByPib2($pib_nomor, $pib_tanggal) {
		$query = "SELECT a.konfirmasi, DATE_FORMAT(a.konf_timestamp, '%d-%m-%Y %H:%i:%s') konf_timestamp, b.name, b.`level` FROM konfirmasi a INNER JOIN users b ON a.sender_id = b.id WHERE a.pib_nomor = '$pib_nomor' AND a.pib_tanggal = '$pib_tanggal'";
		$result = $this->db->select($query);
		return $result;
	}

	/* UPLOAD_KONFIRMASI_PFPD_DETIL.PHP
	   UPLOAD_KONFIRMASI_DETIL.PHP */
	function getKonfById($konf_id) {
		$query = "SELECT a.konfirmasi, DATE_FORMAT(a.konf_timestamp, '%d-%m-%Y %H:%i:%s') konf_timestamp, b.name, b.`level` FROM konfirmasi a INNER JOIN users b ON a.sender_id = b.id WHERE a.id = '$konf_id'";
		$result = $this->db->select($query);
		return mysqli_fetch_array($result);
	}

	/* UPLOAD_KONFIRMASI_PFPD_DETIL.PHP
	   UPLOAD_KONFIRMASI_DETIL.PHP */
	function getKonfReplies($konf_id) {
		$query = "SELECT a.id, a.konfirmasi_id, a.reply, DATE_FORMAT(a.reply_timestamp, '%d-%m-%Y %H:%i:%s') reply_timestamp, b.name, b.`level` FROM konfirmasi_replies a INNER JOIN users b ON a.sender_id = b.id WHERE a.konfirmasi_id = '$konf_id'";
		$result = $this->db->select($query);
		if ($result != NULL) {
			return $result;
		} 	
	}

	
}
