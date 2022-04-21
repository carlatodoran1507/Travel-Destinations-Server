<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET,HEAD,OPTIONS,POST,PUT");
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept, Authorization");

$conn = mysqli_connect("localhost", "root", "", "vacations_db");

$data = json_decode(file_get_contents("php://input"));
$location = mysqli_real_escape_string($conn, $data->location);
$country = mysqli_real_escape_string($conn, $data->country);
$description = mysqli_real_escape_string($conn, $data->description);
$tourist_target = mysqli_real_escape_string($conn, $data->tourist_target);
$estimated_cost = mysqli_real_escape_string($conn, intval($data->estimated_cost));

if ($location && $country && $description && $tourist_target && $estimated_cost) {
    $stmt = mysqli_stmt_init($conn);

    if (!mysqli_stmt_prepare($stmt, "insert into destination (destination_id, location, country, description, tourist_target, estimated_cost_per_day) values (null, ?, ?, ?, ?, ?)")) {
        echo "Prepare failed";
    } else {
        mysqli_stmt_bind_param($stmt, "ssssi", $location, $country, $description, $tourist_target, $estimated_cost);
        mysqli_stmt_execute($stmt);
    }

    $conn->close();
}