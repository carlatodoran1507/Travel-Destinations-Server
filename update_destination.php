<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET,HEAD,OPTIONS,POST,PUT,PATCH");
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept, Authorization");

$conn = mysqli_connect("localhost", "root", "", "vacations_db");

$data = json_decode(file_get_contents("php://input"));
$destination_id = mysqli_real_escape_string($conn, $data->destination_id);
$location = isset($data->location) ? mysqli_real_escape_string($conn, $data->location) : null;
$country = isset($data->country) ? mysqli_real_escape_string($conn, $data->country) : null;
$description = isset($data->description) ? mysqli_real_escape_string($conn, $data->description) : null;
$tourist_target = isset($data->tourist_target) ? mysqli_real_escape_string($conn, $data->tourist_target) : null;
$estimated_cost = isset($data->estimated_cost) ? mysqli_real_escape_string($conn, $data->estimated_cost) : null;

if ($destination_id && ($location || $country || $description || $tourist_target || $estimated_cost)) {
    $sql_query = "update destination set";
    $types_string = "";
    $array_of_params = array();

    if ($location != null) {
        $sql_query .= " location = ?, ";
        $types_string .= "s";
        $array_of_params[] = $location;
    }
    if ($country != null) {
        $sql_query .= " country = ?, ";
        $types_string .= "s";
        $array_of_params[] = $country;
    }
    if ($description != null) {
        $sql_query .= " description = ?, ";
        $types_string .= "s";
        $array_of_params[] = $description;
    }
    if ($tourist_target != null) {
        $sql_query .= " tourist_target = ?, ";
        $types_string .= "s";
        $array_of_params[] = $tourist_target;
    }
    if ($estimated_cost != null) {
        $sql_query .= " estimated_cost_per_day = ?, ";
        $types_string .= "i";
        $array_of_params[] = $estimated_cost;
    }
    $types_string .= "i";
    $sql_query = substr($sql_query, 0, -2);
    $sql_query .= " where destination_id = ?;";
    $array_of_params[] = $destination_id;

    $stmt = mysqli_stmt_init($conn);

    if (!mysqli_stmt_prepare($stmt, $sql_query)) {
        echo "Prepare failed";
    } else {
        mysqli_stmt_bind_param($stmt, $types_string, ...$array_of_params);
        mysqli_stmt_execute($stmt);
    }
}

$conn->close();
