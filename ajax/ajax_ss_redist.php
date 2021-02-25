<?php

$filepath = realpath(dirname(__FILE__));
include_once ($filepath. '/../lib/Session.php');
Session::init();

$user_id = Session::get("id");

if ($user_id == 0 OR $user_id == null OR $user_id == false) { 
    Session::redirect();
}

include("../ajax/ajax_conn.php");
 
$requestData = $_REQUEST;

$minDate = $_POST['minDate'];
$maxDate = $_POST['maxDate'];

$columns = array( 
    0 => 'pib_nomor', 
    1 => 'pib_tanggal',
    2 => 'importir',
    3 => 'jum_dokumen',
    4 => 'pfpd',
    5 => 'status'
);

$sql = "SELECT jum_dokumen, pib_nomor, pib_tanggal, pib_tanggal_x, importir, shipper, status, pfpd, nip, id FROM (SELECT COUNT(a.id) jum_dokumen, a.pib_nomor, DATE_FORMAT(a.pib_tanggal,'%d-%m-%Y') pib_tanggal, a.pib_tanggal pib_tanggal_x, a.importir, a.shipper, CASE WHEN a.status = 1 THEN 'BELUM DIKIRIM' WHEN a.status = 2 THEN 'DIKIRIM' WHEN a.status = 3 THEN 'PEMERIKSAAN' WHEN a.status = 4 OR a.status = 5 THEN 'DITERIMA' WHEN a.status = 40 THEN 'REJECT' END AS `status`, b.name pfpd, b.nip, b.id FROM dokap a INNER JOIN users b ON a.pfpd_id = b.id WHERE a.pib_nomor IN (SELECT DISTINCT (a.pib_nomor) pib_nomor FROM dokap a WHERE a.status = 4 OR a.status = 5 ORDER BY a.id DESC) GROUP BY a.pib_nomor) AS t WHERE t.pib_tanggal_x BETWEEN STR_TO_DATE('$minDate', '%d-%m-%Y') AND STR_TO_DATE('$maxDate', '%d-%m-%Y')";

$query = mysqli_query($conn, $sql);
$totalData = mysqli_num_rows($query);
$totalFiltered = $totalData; 

$sql = "SELECT jum_dokumen, pib_nomor, pib_tanggal, pib_tanggal_x, importir, shipper, status, pfpd, nip, id FROM (SELECT COUNT(a.id) jum_dokumen, a.pib_nomor, DATE_FORMAT(a.pib_tanggal,'%d-%m-%Y') pib_tanggal, a.pib_tanggal pib_tanggal_x, a.importir, a.shipper, CASE WHEN a.status = 1 THEN 'BELUM DIKIRIM' WHEN a.status = 2 THEN 'DIKIRIM' WHEN a.status = 3 THEN 'PEMERIKSAAN' WHEN a.status = 4 OR a.status = 5 THEN 'DITERIMA' WHEN a.status = 40 THEN 'REJECT' END AS `status`, b.name pfpd, b.nip, b.id FROM dokap a INNER JOIN users b ON a.pfpd_id = b.id WHERE a.pib_nomor IN (SELECT DISTINCT (a.pib_nomor) pib_nomor FROM dokap a WHERE a.status = 4 OR a.status = 5 ORDER BY a.id DESC) GROUP BY a.pib_nomor) AS t WHERE t.pib_tanggal_x BETWEEN STR_TO_DATE('$minDate', '%d-%m-%Y') AND STR_TO_DATE('$maxDate', '%d-%m-%Y')";

if(!empty($requestData['search']['value']) ) { 
    $sql .= " AND (pib_nomor LIKE '%" . $requestData['search']['value'] . "%' "; 
    $sql .= " OR pib_tanggal LIKE '%" . $requestData['search']['value'] . "%' ";
    $sql .= " OR importir LIKE '%" . $requestData['search']['value'] . "%' ";
    $sql .= " OR jum_dokumen LIKE '%" . $requestData['search']['value'] . "%' ";
    $sql .= " OR pfpd LIKE '%" . $requestData['search']['value'] . "%' ";
    $sql .= " OR status LIKE '%" . $requestData['search']['value'] . "%' )";
}

$query = mysqli_query($conn, $sql);
$totalFiltered = mysqli_num_rows($query); 
$sql .= " ORDER BY " . $columns[$requestData['order'][0]['column']] . " " . $requestData['order'][0]['dir'] . " LIMIT " . $requestData['start'] . " ," . $requestData['length'] . " ";  
$query = mysqli_query($conn, $sql);

$data = array();
while( $row = mysqli_fetch_array($query) ) { 
    $nestedData = array(); 
    $nestedData[] = $row["pib_nomor"];
    $nestedData[] = $row["pib_tanggal"];
    $nestedData[] = $row["importir"];
    $nestedData[] = $row["jum_dokumen"];
    $nestedData[] = $row["pfpd"];
    $nestedData[] = $row["status"];
    $nestedData[] = '<div class="ambil_redist" pfpd_id="'.$row['id'].'" pib_nomor="'.$row['pib_nomor'].'" pib_tanggal="'.$row['pib_tanggal_x'].'">REDIST (AMBIL)</div>';
    $data[] = $nestedData;
}

$json_data = array(
    "draw"            => intval($requestData['draw']), 
    "recordsTotal"    => intval($totalData), 
    "recordsFiltered" => intval($totalFiltered),
    "data"            => $data,
    'date'            => $minDate . ' - ' . $maxDate
    );

echo json_encode($json_data);

?>
