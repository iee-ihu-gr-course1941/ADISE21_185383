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

// Φέρε το ενεργό παίξιμο

$playing = Playing::getActive($conn);

if ($playing != null) {
    // Παραγωγή ετικέτας για κάθε χαρτί

    $players = $playing['players'];

    for ($i = 0; $i < count( $players); $i++) {
        $player = $players[$i];

        $cards = $player['cards'];

        for ($j = 0; $j < count($cards); $j++) {
            $card = $cards[$j];

            // Μόνο τα χαρτιά του τρέχοντος χρήστη γίνονται ορατά!

            if ($player['id'] != $current_user_id) {
                $card['label'] = '?';
            } else {
                $card['label'] = $card['figure'] . '-' . $card['symbol'];
            }

            $cards[$j] = $card;
        }

        $player['cards'] = $cards;

        $players[$i] = $player;
    }

    $playing['players'] = $players;

    // Φέρε τον τρέχοντα παίκτη του ενεργού παιξίματος

    $currentPlayer = Player::getPlayingCurrent($conn, $playing['id']);

    // Φέρε τον προηγούμενο παίκτη του τρέχοντος παίκτη

    $prevPlayer = Player::getPlayingPrev($conn, $playing['id'], $currentPlayer['id']);

    // Φέρε τον παίκτη που αντιστοιχεί στον τρέχοντα χρήστη

    $currentUserAsPlayer = Player::getByPlayingAndUser($conn, $playing['id'], $current_user_id);

    http_response_code(200);

    if ($currentUserAsPlayer != null) {
        $current_user_is_current_player = $currentPlayer['id'] == $currentUserAsPlayer['id'];

        echo json_encode(
            array(
                "playing_id" => $playing['id'],
                "playing_phase" => $playing['phase'],
                "current_player_state" => $currentPlayer['state'],
                "prev_player_name" => ($prevPlayer == null ? null : $prevPlayer['name']),
                "current_user_state" => $currentUserAsPlayer['state'],
                "current_user_card_cnt" => count($currentUserAsPlayer['cards']),
                "current_user_is_current_player" => $current_user_is_current_player,
                "players" => $playing['players']
            )
        );
    } else {
        echo json_encode(
            array(
                "playing_id" => $playing['id'],
                "playing_phase" => $playing['phase'],
                "current_player_state" => $currentPlayer['state'],
                "prev_player_name" => ($prevPlayer == null ? null : $prevPlayer['name']),
                "current_user_state" => 0,
                "current_user_card_cnt" => -1,
                "current_user_is_current_player" => true,
                "players" => $playing['players']
            )
        );
    }
} else {
    http_response_code(200);

    echo json_encode(
        array(
            "playing_id" => 0,
            "playing_phase" => 0,
            "current_player_state" => 0,
            "prev_player_name" => null,
            "current_user_state" => 0,
            "current_user_card_cnt" => -1,
            "current_user_is_current_player" => true,
            "players" => []
        )
    );
}
