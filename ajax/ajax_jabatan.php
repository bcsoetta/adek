<?php

include("../ajax/ajax_conn.php");

$sql = "SELECT jabatan as value, jabatan, jabatan_kode FROM jabatan ";
$query = mysqli_query($conn, $sql);

$data = array();
while ($r = mysqli_fetch_assoc($query)) {
	$data[] = $r;
}

print json_encode($data);