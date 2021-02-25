<?php

include("../ajax/ajax_conn.php");

$sql = "SELECT CONCAT(pangkat, ' / ', gol) as value, CONCAT(pangkat, ' / ', gol) AS pangkat_gol, id FROM pangkat ";
$query = mysqli_query($conn, $sql);

$data = array();
while ($r = mysqli_fetch_assoc($query)) {
	$data[] = $r;
}

print json_encode($data);