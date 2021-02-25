<?php

include("../ajax/ajax_conn.php");

$user_id = $_POST['user_id'];

$query = mysqli_query($conn, "UPDATE users SET `status` = '400' WHERE id = '$user_id' ");

