<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once '../../infrastructure/DB.php';
include_once '../../models/Playing.php';
include_once '../../models/Player.php';

$conn = DB::getConnection();

$data = json_decode(file_get_contents("php://input"));

if ($data) {
    // Δημιούργησε νέο παίξιμο

    $playing_id = Playing::add($conn, 1, 1, $data->player_cnt);

    if($playing_id != 0){         
        // Πρόσθεσε τον τρέχοντα χρήστη ως παίκτη (και μάλιστα τρέχοντα)

        Player::add($conn, $data->user_id, $playing_id, 1, 1);

        http_response_code(200);         
    } else{         
        http_response_code(503);     

        echo json_encode(array("error" => "Αδυναμία έναρξης παιχνιδιού!"));
    }
} else {
    http_response_code(400);
    echo json_encode( array("error" => "Ελλιπή στοιχεία!"));
}
