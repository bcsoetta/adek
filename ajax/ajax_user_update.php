<?php

include("../ajax/ajax_conn.php");

$username = $_POST["username"];
$password_insert = md5($_POST["password"]);
$password_check = $_POST["password"];
$name = $_POST["name"];
$nip = $_POST["nip"];
$level_id = $_POST["level_id"];
$unit_id = $_POST["unit_id"];
$jabatan_id = $_POST["jabatan_id"];
$pangkat_id = $_POST["pangkat_id"];
$status_update = $_POST["status_update"];
$id = $_POST["id"];

$update1 = mysqli_query($conn, "UPDATE users SET username = '$username' WHERE username <> '$username' AND id = '$id' ");
$update2 = mysqli_query($conn, "UPDATE users SET password = '$password_insert' WHERE password != '$password_check' AND id = '$id' ");
$update3 = mysqli_query($conn, "UPDATE users SET name = '$name' WHERE name <> '$name' AND id = '$id' ");
$update4 = mysqli_query($conn, "UPDATE users SET nip = '$nip' WHERE nip <> '$nip' AND id = '$id' ");
$update5 = mysqli_query($conn, "UPDATE users SET level = '$level_id' WHERE level <> '$level_id' AND id = '$id' ");
$update6 = mysqli_query($conn, "UPDATE users SET unit_id = '$unit_id' WHERE unit_id <> '$unit_id' AND id = '$id' ");
$update7 = mysqli_query($conn, "UPDATE users SET jabatan = '$jabatan_id' WHERE jabatan <> '$jabatan_id' AND id = '$id' ");
$update8 = mysqli_query($conn, "UPDATE users SET pangkat_gol = '$pangkat_id' WHERE pangkat_gol <> '$pangkat_id' AND id = '$id' ");
$update9 = mysqli_query($conn, "UPDATE users SET status = '$status_update' WHERE status <> '$status_update' AND id = '$id' ");
