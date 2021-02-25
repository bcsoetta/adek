<?php

include("../ajax/ajax_conn.php");

if (isset($_POST['pib_nomor']) && isset($_POST['pib_tanggal'])) {

    $pib_nomor = $_POST['pib_nomor'];
    $pib_tanggal = date("Y-m-d", strtotime($_POST['pib_tanggal']));
    $importir = $_POST['importir'];
    $importir_npwp = $_POST['importir_npwp'];
    $ppjk = $_POST['ppjk'];
    $ppjk_npwp = $_POST['ppjk_npwp'];
    $pfpd = $_POST['pfpd'];
    $pfpd_nip = $_POST['pfpd_nip'];

    $query = "INSERT INTO npdx (`pib_nomor`, `pib_tanggal`, `importir_nama`, `importir_npwp`, `ppjk_nama`, `ppjk_npwp`, `pfpd_nama`, `pfpd_nip`, `status`) VALUES ('$pib_nomor', '$pib_tanggal', '$importir', '$importir_npwp', '$ppjk', '$ppjk_npwp', '$pfpd', '$pfpd_nip', 'PENDING')";
    $sql = mysqli_query($conn, $query);

    if (mysqli_affected_rows($conn) > 0) {
        echo "Succeed.";
    } else {
        echo "Faild";
    }
}