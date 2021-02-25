<?php

include("../ajax/ajax_conn.php");
 
$requestData = $_REQUEST;

$minDate = $_POST['minDate'];
$maxDate = $_POST['maxDate'];

$columns = array( 
    0 => 'pib_nomor', 
    1 => 'pib_tanggal',
    2 => 'importir',
    3 => 'pendok',
    4 => 'pfpd',
    5 => 'status'
);

$sql = "SELECT DISTINCT src.pib_nomor, src.importir, src.shipper, src.jum_dokumen, src.`status`, src.pendok, dst.pfpd, src.uploader_id, src.pfpd_id, DATE_FORMAT(src.pib_tanggal,'%d-%m-%Y') pib_tanggal, src.pib_tanggal pib_tanggal_x FROM (SELECT COUNT(a.pib_nomor) jum_dokumen, a.pib_nomor, a.pib_tanggal, b.name pendok, b.nip, a.importir, a.shipper, a.uploader_id, a.pfpd_id, CASE WHEN a.status = 1 THEN 'BELUM DIKIRIM' WHEN a.status = 2 THEN 'DIKIRIM' WHEN a.status = 3 THEN 'PEMERIKSAAN' WHEN a.status = 4 OR a.status = 5 THEN 'DITERIMA' WHEN a.status = 40 THEN 'REJECT' END AS status FROM dokap a LEFT JOIN users b ON a.pendok_id = b.id WHERE a.pib_tanggal BETWEEN STR_TO_DATE('$minDate', '%d-%m-%Y') AND STR_TO_DATE('$maxDate', '%d-%m-%Y') GROUP BY a.pib_nomor) src INNER JOIN (SELECT a.pib_nomor, a.pib_tanggal, b.name pfpd, b.nip FROM dokap a LEFT JOIN users b ON a.pfpd_id = b.id) dst ON src.pib_nomor = dst.pib_nomor WHERE src.pib_tanggal BETWEEN STR_TO_DATE('$minDate', '%d-%m-%Y') AND STR_TO_DATE('$maxDate', '%d-%m-%Y') AND src.pib_nomor = dst.pib_nomor AND src.pib_tanggal = dst.pib_tanggal";

$query = mysqli_query($conn, $sql);
$totalData = mysqli_num_rows($query);
$totalFiltered = $totalData; 

$sql = "SELECT DISTINCT src.pib_nomor, src.importir, src.shipper, src.jum_dokumen, src.`status`, src.pendok, dst.pfpd, src.uploader_id, src.pfpd_id, DATE_FORMAT(src.pib_tanggal,'%d-%m-%Y') pib_tanggal, src.pib_tanggal pib_tanggal_x FROM (SELECT COUNT(a.pib_nomor) jum_dokumen, a.pib_nomor, a.pib_tanggal, b.name pendok, b.nip, a.importir, a.shipper, a.uploader_id, a.pfpd_id, CASE WHEN a.status = 1 THEN 'BELUM DIKIRIM' WHEN a.status = 2 THEN 'DIKIRIM' WHEN a.status = 3 THEN 'PEMERIKSAAN' WHEN a.status = 4 OR a.status = 5 THEN 'DITERIMA' WHEN a.status = 40 THEN 'REJECT' END AS status FROM dokap a LEFT JOIN users b ON a.pendok_id = b.id WHERE a.pib_tanggal BETWEEN STR_TO_DATE('$minDate', '%d-%m-%Y') AND STR_TO_DATE('$maxDate', '%d-%m-%Y') GROUP BY a.pib_nomor) src INNER JOIN (SELECT a.pib_nomor, a.pib_tanggal, b.name pfpd, b.nip FROM dokap a LEFT JOIN users b ON a.pfpd_id = b.id) dst ON src.pib_nomor = dst.pib_nomor WHERE src.pib_tanggal BETWEEN STR_TO_DATE('$minDate', '%d-%m-%Y') AND STR_TO_DATE('$maxDate', '%d-%m-%Y') AND src.pib_nomor = dst.pib_nomor AND src.pib_tanggal = dst.pib_tanggal";

if(!empty($requestData['search']['value']) ) { 
    $sql .= " AND (src.pib_nomor LIKE '%" . $requestData['search']['value'] . "%' "; 
    $sql .= " OR src.pib_tanggal LIKE '%" . $requestData['search']['value'] . "%' ";
    $sql .= " OR src.importir LIKE '%" . $requestData['search']['value'] . "%' ";
    $sql .= " OR src.pendok LIKE '%" . $requestData['search']['value'] . "%' ";
    $sql .= " OR pfpd LIKE '%" . $requestData['search']['value'] . "%' ";
    $sql .= " OR src.status LIKE '%" . $requestData['search']['value'] . "%' )";
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
    $nestedData[] = $row["pendok"];
    $nestedData[] = $row["pfpd"];
    $nestedData[] = $row["status"];
    $nestedData[] = '<div class="status_detil_link_pfpd" pib_nomor="'.$row["pib_nomor"].'" pib_tanggal="'.$row["pib_tanggal_x"].'">VIEW DETIL</div>';
    $data[] = $nestedData;
}

$json_data = array(
    "draw"            => intval($requestData['draw']), 
    "recordsTotal"    => intval($totalData), 
    "recordsFiltered" => intval($totalFiltered),
    "data"            => $data,
    'date'            => $minDate . ' - ' . $maxDate
    // 'sql'               => $sql
    );

header('Content-Type: application/json');
echo json_encode($json_data);

?>
