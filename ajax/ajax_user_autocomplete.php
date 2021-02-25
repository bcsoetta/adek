<?php

include("../ajax/ajax_conn.php");

$sql = "SELECT name AS value, name, nip, id FROM users a WHERE a.`level` = '4';";
$query = mysqli_query($conn, $sql);

$data = array();
while ($r = mysqli_fetch_assoc($query)) {
	$data[] = $r;
}

print json_encode($data);