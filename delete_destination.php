<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET,HEAD,OPTIONS,POST,PUT,DELETE");
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept, Authorization");

$conn = mysqli_connect("localhost", "root", "", "vacations_db");

$query_params = array();
parse_str($_SERVER['QUERY_STRING'], $query_params);

$destination_id = mysqli_real_escape_string($conn, $query_params['destination_id']);

$stmt = mysqli_stmt_init($conn);

if (!mysqli_stmt_prepare($stmt, "delete from destination where destination_id=?;")) {
    echo "Prepare failed";
} else {
    if ($destination_id) {
        mysqli_stmt_bind_param($stmt, "i", $destination_id);
        mysqli_stmt_execute($stmt);
    }
}

$conn->close();
