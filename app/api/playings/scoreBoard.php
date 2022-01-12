<?php
header("Content-Type: application/json; charset=UTF-8");

include_once '../../infrastructure/DB.php';
include_once '../../models/Playing.php';
include_once '../../models/Player.php';

// Έλεγχος εξουσιοδότησης

if (isset($_SERVER['HTTP_X_TOKEN'])) {
    $current_user_id = $_SERVER['HTTP_X_TOKEN'];
}
else {
    http_response_code(403);
    echo json_encode(
        array("error" => "Δεν έχετε εξουσιοδότηση κλήσης του api!")
    );
    exit;
}

$conn = DB::getConnection();

$playings = PLaying::history($conn);

http_response_code(200);

echo json_encode(
    array(
        "playings" => $playings
    )
);
