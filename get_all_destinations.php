<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET,HEAD,OPTIONS,POST,PUT");
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept, Authorization");

$conn = mysqli_connect("localhost", "root", "", "vacations_db");

$results_per_page = 4;
$query_params = array();
parse_str($_SERVER['QUERY_STRING'], $query_params);

$page = mysqli_real_escape_string($conn, $query_params['page']);

if ($page) {
    $page_first_result = ($page - 1) * $results_per_page;
    $query = "SELECT * FROM destination ";

    if ($query_params['filter']) {
        $query .= " WHERE country = ? ";
    }

    $query .= " LIMIT ?, ?";
    $stmt = mysqli_stmt_init($conn);

    if (!mysqli_stmt_prepare($stmt, $query)) {
        echo "Prepare failed";
    } else {

        if ($query_params['filter']) {
            $filter = mysqli_real_escape_string($conn, $query_params['filter']);
            mysqli_stmt_bind_param($stmt, "sii", $filter, $page_first_result, $results_per_page);
        } else {
            mysqli_stmt_bind_param($stmt, "ii", $page_first_result, $results_per_page);
        }

        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $destinations = array();

        while ($row = mysqli_fetch_assoc($result))
            $destinations[] = $row;
        echo json_encode($destinations);
    }
}

$conn->close();
