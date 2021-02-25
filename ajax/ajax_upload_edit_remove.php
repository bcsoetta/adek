<?php

include("../ajax/ajax_conn.php");

$sql = "SELECT a.id, a.pib_nomor, CASE WHEN a.type = 1 THEN 'IN-' WHEN a.type = 2 THEN 'PL-' WHEN a.type = 3 THEN 'MA-' WHEN a.type = 4 THEN 'HA-' WHEN a.type = 5 THEN 'LL-' END AS type FROM dokap a WHERE a.pib_nomor = (SELECT DISTINCT (a.pib_nomor) pib_nomor FROM dokap a WHERE a.`status` = 1 ORDER BY a.id DESC LIMIT 1) ORDER BY a.`type` DESC ";
$query = mysqli_query($conn, $sql);

$data = array();
while ($r = mysqli_fetch_assoc($query)) {
	$data[] = $r;
}

print json_encode($data);

