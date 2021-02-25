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
    0 => 'upload_timestamp',
    1 => 'pib_nomor', 
    2 => 'pib_tanggal',
    3 => 'importir',
    4 => 'jum_dokumen',
    5 => 'unit_short',
    6 => 'jum_konf'
);

$sql = "SELECT id, jum_dokumen, jum_konf, unit_short, pib_nomor, pib_tanggal, pib_tanggal_x, upload_timestamp, importir, shipper, status FROM (SELECT src.pfpd_id id, src.jum_dokumen, dst.jum_konf, src.unit_short, src.pib_nomor, src.pib_tanggal, src.pib_tanggal_x, src.upload_timestamp, src.importir, src.shipper, src.status FROM (SELECT COUNT(a.id) jum_dokumen, a.pfpd_id, a.pib_nomor, DATE_FORMAT(a.pib_tanggal,'%d-%m-%Y') pib_tanggal_x, a.pib_tanggal pib_tanggal, a.upload_timestamp, a.importir, a.shipper, h.unit_short, CASE WHEN a.status = 1 THEN 'BELUM DIKIRIM' WHEN a.status = 2 THEN 'DIKIRIM' WHEN a.status = 3 THEN 'PEMERIKSAAN' WHEN a.status = 4 OR a.status = 5 THEN 'DITERIMA' WHEN a.status = 40 THEN 'REJECT' END AS `status` FROM dokap a INNER JOIN users g ON a.uploader_id = g.id INNER JOIN unit h ON g.unit_id = h.id WHERE a.pib_nomor IN (SELECT DISTINCT (a.pib_nomor) pib_nomor FROM dokap a WHERE (a.status = 4 OR a.status = 5) AND a.pfpd_id = '$user_id' ORDER BY a.id DESC) GROUP BY a.pib_nomor) src LEFT JOIN (SELECT COUNT(a.id) jum_konf, a.pib_nomor, a.pib_tanggal FROM konfirmasi a GROUP BY a.pib_nomor) dst ON src.pib_nomor = dst.pib_nomor AND src.pib_tanggal = dst.pib_tanggal) AS t WHERE t.pib_tanggal BETWEEN STR_TO_DATE('$minDate', '%d-%m-%Y') AND STR_TO_DATE('$maxDate', '%d-%m-%Y')";

$query = mysqli_query($conn, $sql);
$totalData = mysqli_num_rows($query);
$totalFiltered = $totalData; 

$sql = "SELECT id, jum_dokumen, jum_konf, unit_short, pib_nomor, pib_tanggal, pib_tanggal_x, upload_timestamp, importir, shipper, status FROM (SELECT src.pfpd_id id, src.jum_dokumen, dst.jum_konf, src.unit_short, src.pib_nomor, src.pib_tanggal, src.pib_tanggal_x, src.upload_timestamp, src.importir, src.shipper, src.status FROM (SELECT COUNT(a.id) jum_dokumen, a.pfpd_id, a.pib_nomor, DATE_FORMAT(a.pib_tanggal,'%d-%m-%Y') pib_tanggal_x, a.pib_tanggal pib_tanggal, a.upload_timestamp, a.importir, a.shipper, h.unit_short, CASE WHEN a.status = 1 THEN 'BELUM DIKIRIM' WHEN a.status = 2 THEN 'DIKIRIM' WHEN a.status = 3 THEN 'PEMERIKSAAN' WHEN a.status = 4 OR a.status = 5 THEN 'DITERIMA' WHEN a.status = 40 THEN 'REJECT' END AS `status` FROM dokap a INNER JOIN users g ON a.uploader_id = g.id INNER JOIN unit h ON g.unit_id = h.id WHERE a.pib_nomor IN (SELECT DISTINCT (a.pib_nomor) pib_nomor FROM dokap a WHERE (a.status = 4 OR a.status = 5) AND a.pfpd_id = '$user_id' ORDER BY a.id DESC) GROUP BY a.pib_nomor) src LEFT JOIN (SELECT COUNT(a.id) jum_konf, a.pib_nomor, a.pib_tanggal FROM konfirmasi a GROUP BY a.pib_nomor) dst ON src.pib_nomor = dst.pib_nomor AND src.pib_tanggal = dst.pib_tanggal) AS t WHERE t.pib_tanggal BETWEEN STR_TO_DATE('$minDate', '%d-%m-%Y') AND STR_TO_DATE('$maxDate', '%d-%m-%Y')";

if(!empty($requestData['search']['value']) ) { 
    $sql .= " AND (upload_timestamp LIKE '%" . $requestData['search']['value'] . "%' "; 
    $sql .= " OR pib_nomor LIKE '%" . $requestData['search']['value'] . "%' ";
    $sql .= " OR pib_tanggal LIKE '%" . $requestData['search']['value'] . "%' ";
    $sql .= " OR importir LIKE '%" . $requestData['search']['value'] . "%' ";
    $sql .= " OR jum_dokumen LIKE '%" . $requestData['search']['value'] . "%' ";
    $sql .= " OR unit_short LIKE '%" . $requestData['search']['value'] . "%' )";
}

$query = mysqli_query($conn, $sql);
$totalFiltered = mysqli_num_rows($query); 
$sql .= " ORDER BY " . $columns[$requestData['order'][0]['column']] . " " . $requestData['order'][0]['dir'] . " LIMIT " . $requestData['start'] . " ," . $requestData['length'] . " ";  
$query = mysqli_query($conn, $sql);

$data = array();
while( $row = mysqli_fetch_array($query) ) { 
    $nestedData = array(); 
    $nestedData[] = $row["upload_timestamp"];
    $nestedData[] = $row["pib_nomor"];
    $nestedData[] = $row["pib_tanggal"];
    $nestedData[] = $row["importir"];
    $nestedData[] = $row["jum_dokumen"];
    $nestedData[] = $row["unit_short"];

    if ($row['jum_konf'] == null) { 
        $nestedData[] = '0'; 
    } else  {
    $nestedData[] = $row['jum_konf'];
    }

    // $nestedData[] = $row["jum_konf"];
    $nestedData[] = '<div class="selesai_byid_detil_link" pib_nomor="'.$row['pib_nomor'].'" pib_tanggal="'.$row['pib_tanggal'].'">VIEW DETIL (KONF)</div>';
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
