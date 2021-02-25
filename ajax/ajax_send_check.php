<?php

include("../ajax/ajax_conn.php");

$sql = "SELECT COUNT(a.id) jum, a.pib_nomor, DATE_FORMAT(a.pib_tanggal,'%d-%m-%Y') pib_tanggal FROM dokap a WHERE a.pib_nomor = (SELECT DISTINCT (a.pib_nomor) pib_nomor FROM dokap a WHERE a.`status` = 1 ORDER BY a.id DESC LIMIT 1) GROUP BY a.pib_nomor";
$query = mysqli_query($conn, $sql);

$data = array();
while ($r = mysqli_fetch_assoc($query)) {
	$data[] = $r;
}

print json_encode($data);

