<?php
header("Content-Type: application/json; charset=UTF-8");

include_once '../../infrastructure/DB.php';
include_once '../../models/Playing.php';
include_once '../../models/Player.php';

$conn = DB::getConnection();

$current_user_id = intval((isset($_GET['user_id']) && $_GET['user_id']) ? $_GET['user_id'] : '0');

// Φέρε το ενεργό παίξιμο

$playing = Playing::getActive($conn, $current_user_id);

if ($playing != null) {
    // Φέρε τον τρέχοντα παίκτη του ενεργού παιξίματος

    $currentPlayer = Player::getPlayingCurrent($conn, $playing['id']);

    // Φέρε τον παίκτη του ενεργού παιξίματος που αντιστοιχεί στον τρέχοντα χρήστη

    $currentUserAsPlayer = Player::getByPlayingAndUser($conn, $playing['id'], $current_user_id);

    http_response_code(200);

    if ($currentUserAsPlayer != null) {
        $current_user_is_current_player = $currentPlayer['id'] == $currentUserAsPlayer['id'];

        echo json_encode(
            array(
                "playing_id" => $playing['id'],
                "playing_phase" => $playing['phase'],
                "current_player_state" => $currentPlayer['state'],
                "current_user_state" => $currentUserAsPlayer['state'],
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
                "current_user_state" => 0,
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
            "current_user_state" => 0,
            "current_user_is_current_player" => true,
            "players" => []
            )
    );
}
