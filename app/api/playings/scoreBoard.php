<?php
header("Content-Type: application/json; charset=UTF-8");

include_once '../../infrastructure/DB.php';
include_once '../../models/Playing.php';
include_once '../../models/Player.php';

$conn = DB::getConnection();

$playings = PLaying::history($conn);

http_response_code(200);

echo json_encode(
    array(
        "playings" => $playings
    )
);
