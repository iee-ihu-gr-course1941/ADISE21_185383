<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once '../../infrastructure/DB.php';
include_once '../../models/Playing.php';
include_once '../../models/Player.php';
include_once '../../models/Card.php';

$conn = DB::getConnection();

$data = json_decode(file_get_contents("php://input"));

if ($data) {
    // Θέσε ως ανενεργό το τελευταίο παίξιμο (αν υπάρχει)

    Playing::clearActive($conn);

    // Δημιούργησε νέο παίξιμο

    $playing = array(
        "active" => 1,
        "phase" => 1,
        "player_cnt" => $data->player_cnt
    );
    
    $playing_id = Playing::add($conn, $playing);

    if($playing_id != 0){         
        // Πρόσθεσε τον τρέχοντα χρήστη ως παίκτη (και μάλιστα ως τρέχοντα)

        $player = array(
            "id" => $data->user_id,
            "playing_id" => $playing_id,
            "playing_iscurrent" => 1,
            "state" => 1
        );

        Player::add($conn, $player);

        // Προετοίμασε τα χαρτιά για νέο παίξιμο

        Card::init($conn);

        http_response_code(200);         
    } else{         
        http_response_code(503);     

        echo json_encode(array("error" => "Αδυναμία έναρξης παιχνιδιού!"));
    }
} else {
    http_response_code(400);
    echo json_encode( array("error" => "Ελλιπή στοιχεία!"));
}
