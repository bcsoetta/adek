<?php

include("../ajax/ajax_conn.php");

if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && ($_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest')) {

    $query = mysqli_query($conn, "SELECT users.id AS user_id, users.username, users.name, users.nip, CASE users.level WHEN 1 THEN 'ADMINISTRATOR' WHEN 2 THEN 'PENGGUNA JASA' WHEN 3 THEN 'ADMIN PENDOK' ELSE 'PFPD' END AS level, users.unit_id, CASE users.`status` WHEN 100 THEN 'ACTIVE' WHEN 400 THEN 'INACTIVE' ELSE 'UNKNOWN' END AS `status`, unit.id AS unit_id, unit.unit_short, unit.unit_long FROM users INNER JOIN unit ON users.unit_id = unit.id ORDER BY users.name");

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
