<?php

include("../ajax/ajax_conn.php");

$sql = "SELECT unit_long as value, unit_long, id FROM unit ";
$query = mysqli_query($conn, $sql);

$data = array();
while ($r = mysqli_fetch_assoc($query)) {
	$data[] = $r;
}

print json_encode($data);