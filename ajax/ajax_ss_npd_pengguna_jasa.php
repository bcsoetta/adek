<?php

$filepath = realpath(dirname(__FILE__));
include_once ($filepath. '/../lib/Session.php');
Session::init();

$user_nip = Session::get("nip");

if ($user_nip == 0 OR $user_nip == null OR $user_nip == false) { 
    Session::redirect();
}

include("../ajax/ajax_conn.php");
 
$requestData = $_REQUEST;

$minDate = $_POST['minDate'];
$maxDate = $_POST['maxDate'];

$columns = array( 
    0 => 'pib_nomor', 
    1 => 'pib_tanggal',
    2 => 'importir_nama',
    3 => 'ppjk_nama',
    4 => 'pfpd_nama',
    5 => 'status'
);

$sql = "SELECT * FROM npdx WHERE pib_tanggal BETWEEN STR_TO_DATE('$minDate', '%d-%m-%Y') AND STR_TO_DATE('$maxDate', '%d-%m-%Y')AND (importir_npwp = '$user_nip' OR ppjk_npwp = '$user_nip')";

$query = mysqli_query($conn, $sql);
$totalData = mysqli_num_rows($query);
$totalFiltered = $totalData; 

$qstr = $_REQUEST['search']['value'];
if(strlen($qstr) || true) { 
    $sql .= " AND (`pib_nomor` LIKE '%" . $qstr . "%' "; 
    $sql .= " OR pib_tanggal LIKE '%" . $qstr . "%' ";
    $sql .= " OR importir_nama LIKE '%" . $qstr . "%' ";
    $sql .= " OR ppjk_nama LIKE '%" . $qstr . "%' ";
    $sql .= " OR pfpd_nama LIKE '%" . $qstr . "%' ";
    $sql .= " OR status LIKE '%" . $qstr . "%' )";
}

$query = mysqli_query($conn, $sql);
$totalFiltered = mysqli_num_rows($query); 
$sql .= " ORDER BY " . $columns[$requestData['order'][0]['column']] . " " . $requestData['order'][0]['dir'] . " LIMIT " . $requestData['start'] . " ," . $requestData['length'] . ";";  
$query = mysqli_query($conn, $sql);

$data = array();
while( $row = mysqli_fetch_array($query) ) { 
    $nestedData = array(); 
    $nestedData[] = $row["pib_nomor"];
    $nestedData[] = $row["pib_tanggal"];
    $nestedData[] = $row["importir_nama"];
    $nestedData[] = $row["ppjk_nama"];
    $nestedData[] = $row["pfpd_nama"];
    $nestedData[] = $row["status"];
    // $nestedData[] = '<div class="status_detil_link" pib_nomor="'.$row["pib_nomor"].'" pib_tanggal="'.$row["pib_tanggal"].'">VIEW DETIL</div>';
    $data[] = $nestedData;
}

$json_data = array(
    "draw"            => intval($requestData['draw']), 
    "recordsTotal"    => intval($totalData), 
    "recordsFiltered" => intval($totalFiltered),
    "data"            => $data,
    'date'            => $minDate . ' - ' . $maxDate
    // "sql"             => $sql
    );

echo json_encode($json_data);

?>
