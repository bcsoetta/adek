<?php

header("Content-Type: application/json");

include("../ajax/ajax_conn.php");

$sql = "SELECT COUNT(DISTINCT(a.pib_nomor)) jum_pib, CASE WHEN a.status = 1 THEN 'BELUM DIKIRIM' WHEN a.status = 2 THEN 'DIKIRIM' WHEN a.status = 3 THEN 'PEMERIKSAAN' WHEN a.status = 4 OR a.`status` = 5 THEN 'DITERIMA' WHEN a.status = 40 THEN 'REJECT' END AS statuss FROM dokap a WHERE YEAR(a.pib_tanggal) = YEAR(NOW()) GROUP BY statuss";
$query = mysqli_query($conn, $sql);

$data = array();
while ($r = mysqli_fetch_assoc($query)) {
	$data[] = $r;
}

print json_encode($data);