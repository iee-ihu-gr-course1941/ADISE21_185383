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

$data = json_decode(file_get_contents("php://input"));

if ($data) {
    // Φέρε το ενεργό παίξιμο

    $playing = Playing::getActive($conn);

    // Φέρε τον τρέχοντα χρήστη-παίκτη 

$player = Player::getById($conn, $playing['id'],  $current_user_id);

    if ($player != null) {
        // Πάρε το χαρτί που επέλεξε ο τρέχων χρήστης-παίκτης

        $prevPlayer = Player::getPlayingPrev($conn, $playing['id'], $player['id']);

        $prevCards = $prevPlayer['cards'];

        $pickedCard = $prevCards[$data->card_to_pick - 1];

        $pickedCard['player_id'] = $player['id'];
        $lastCardPos = count($player['cards']) - 1;
        $lastCard = $player['cards'][$lastCardPos];
        $pickedCard['player_seqno'] = $lastCard['player_seqno'] + 1;

        Card::update($conn, $pickedCard);

        // Έλεγξε μήπως ο προηγούμενος παίκτης ξέμεινε από χαρτιά γιατί,
        // σ' αυτή την περίπτωση είναι ο νικητής!

        if (Player::getCardCnt($conn, $playing['id'], $prevPlayer['id']) == 0) { // Αν ο προηγούμενος παίκτης δεν έχει χαρτιά ...
            // ... τερμάτισε το παίξιμο

            $playing['phase'] = 4;

            Playing::update($conn, $playing);

            Player::storeCardCnt($conn, $playing['id']);
        } else {
            // Άλλαξε την κατάσταση του χρήστη-παίκτη στην κατάσταση επιλογής χαρτιού

            $player['state'] = 3;

            Player::update($conn, $player);
        }
    }

    http_response_code(200);
} else {
    http_response_code(400);
    echo json_encode(array("error" => "Ελλιπή στοιχεία!"));
}
