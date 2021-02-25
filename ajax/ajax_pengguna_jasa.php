<?php

include("../ajax/ajax_conn.php");

$sql = "SELECT `name` as value, `name`, id, nip FROM users WHERE `level` = 2 AND `status` = 100";
$query = mysqli_query($conn, $sql);

$data = array();
while ($r = mysqli_fetch_assoc($query)) {
	$data[] = $r;
}

print json_encode($data);