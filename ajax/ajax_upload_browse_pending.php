<?php

$filepath = realpath(dirname(__FILE__));
include_once ($filepath. '/../lib/Session.php');
Session::init();

$user_id = Session::get("id");

include("../ajax/ajax_conn.php");

if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && ($_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest')) {
    // $query = mysqli_query($conn, "SELECT COUNT(a.id) jum_dokumen, a.pib_nomor, DATE_FORMAT(a.pib_tanggal,'%d-%m-%Y') pib_tanggal, a.pib_tanggal pib_tanggal_x, a.importir, a.shipper FROM dokap a WHERE a.pib_nomor IN (SELECT DISTINCT (a.pib_nomor) pib_nomor FROM dokap a WHERE a.`status` = 3 AND a.pendok_id = '$user_id' ORDER BY a.id DESC) GROUP BY a.pib_nomor");

    $query = mysqli_query($conn, "SELECT COUNT(a.id) jum_dokumen, a.pib_nomor, DATE_FORMAT(a.pib_tanggal,'%d-%m-%Y') pib_tanggal, a.pib_tanggal pib_tanggal_x, a.importir, a.shipper FROM dokap a WHERE a.status = 3 AND a.pendok_id = '$user_id' GROUP BY a.pib_nomor, a.pib_tanggal");


    $jsonResult = '{ "data" : [ ';

    $i=0;
    while ($data=mysqli_fetch_assoc($query)) {
        if($i != 0) {
            $jsonResult .=',';
        }
        $jsonResult .=json_encode($data);
        $i++;
    }
    $jsonResult .= ']}';
    echo $jsonResult;
} else {
    header("Location: 404.php");
}

?>
