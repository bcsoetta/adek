<?php

header("Content-Type: application/json");

include("../ajax/ajax_conn.php");

$sql = "SELECT COUNT(DISTINCT(a.pib_nomor)) jum_pib, MONTHNAME(a.action_timestamp) month FROM loginfo a GROUP BY MONTH ORDER BY MONTH DESC";
$query = mysqli_query($conn, $sql);

$data = array();
while ($r = mysqli_fetch_assoc($query)) {
	$data[] = $r;
}

print json_encode($data);