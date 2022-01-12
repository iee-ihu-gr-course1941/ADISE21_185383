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

$player = Player::getById($conn, $playing['id'], $current_user_id);

    if ($player != null) {
        // Ρίξε τα χαρτιά που επέλεξε ο χρήστης

        $cards = $player['cards'];
        $pairs_to_throw = explode('|', $data->cards_to_throw);

        foreach ($pairs_to_throw as $pair) {
            $pairCards = explode(',',  $pair);

            if (count($pairCards) == 2) {
                $card1 = $cards[$pairCards[0] - 1];
                $card2 = $cards[$pairCards[1] - 1];

                if ($card1['figure'] == $card2['figure']) {
                    Card::clear($conn, $card1['id']);
                    Card::clear($conn, $card2['id']);
                }
            }
        }

        // Άλλαξε την κατάσταση του χρήστη-παίκτη σε κατάσταση "επιλογής χαρτιού"

        $playerOldState = $player['state'];

        $player['state'] = 2;

        Player::update($conn, $player);

        // Άλλαξε τη φάση του ενεργού παιξίματος

        $takeCurrent = false;

        if ($playing['phase'] == 2) {
            if (Playing::areAllPlayersInState($conn, 2, $playing['id'], $playing['player_cnt'])) { // Αν όλοι οι παίκτες έχουν ρίξει τα διπλά χαρτιά τους ...
                // ... πήγαινε στη φάση 3 (κύριο μέρος)

                $playing['phase'] = 3;

                Playing::update($conn, $playing);
            }

            $takeCurrent = true;
        } else if ($playing['phase'] == 3) {
            if (Player::getCardCnt($conn, $playing['id'], $player['id']) == 0) { // Αν ο τρέχων παίκτης δεν έχει χαρτιά ...
                // ... τερμάτισε το παίξιμο

                $playing['phase'] = 4;

                Playing::update($conn, $playing);

                Player::storeCardCnt($conn, $playing['id']);
            } else if ($playerOldState == 3) {
                $takeCurrent = true;
            }
        }

        if ($takeCurrent) { // Αν ο τρέχων χρήστης-παίκτης χάνει τη σειρά του ...
            // Πάρε τη σειρά από τον τρέχοντα χρήστη-παίκτη

            $player = Player::getById($conn, $playing['id'], $player['id']);

            $player['playing_iscurrent'] = 0;

            Player::update($conn, $player);

            // Κάνε τρέχοντα τον επόμενο κατά σειρά παίκτη

            $nextPlayer = Player::getPlayingNext($conn, $playing['id'], $player['id']);

            $nextPlayer['playing_iscurrent'] = 1;

            Player::update($conn, $nextPlayer);
        }
    }

    http_response_code(200);
} else {
    http_response_code(400);
    echo json_encode(array("error" => "Ελλιπή στοιχεία!"));
}
