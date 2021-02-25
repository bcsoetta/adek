<?php

include("../ajax/ajax_conn.php");

$username = $_POST["username"];
$password = md5($_POST["password"]);
$name = $_POST["name"];
$nip = $_POST["nip"];
$level_id = $_POST["level_id"];
$unit_id = $_POST["unit_id"];
$jabatan_id = $_POST["jabatan_id"];
$pangkat_id = $_POST["pangkat_id"];

$get_user = mysqli_query($conn, "SELECT username, nip FROM users WHERE username = '$username' OR nip = '$nip' ");
$count = mysqli_num_rows($get_user);

if ($count > 0) {
	echo "The username or NIP already registered! Try again.";
} else {
	$query = "INSERT INTO users (username, password, name, nip, level, unit_id, jabatan, pangkat_gol, `status`) VALUES ('$username', '$password', '$name', '$nip', '$level_id', '$unit_id', '$jabatan_id', '$pangkat_id', 100)";
	$sql = mysqli_query($conn, $query);
	
	if ($sql) {
		echo "Succeed.";
	} else {
		echo mysqli_error($conn);
	}
}




