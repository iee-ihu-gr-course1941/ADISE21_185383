<?php
header("Content-Type: application/json; charset=UTF-8");

include_once '../../infrastructure/DB.php';
include_once '../../models/Playing.php';
include_once '../../models/Player.php';

$database = new DB();
$db = $database->getConnection();

// Φέρε το ενεργό παίξιμο

$playingModel = new Playing($db);
$result = $playingModel->getActive();

if (
    $result->num_rows > 0 &&
    $playing = $result->fetch_assoc()
) {
    // Φέρε τον τρέχοντα παίκτη του ενεργού παιχνιδιού

    $playerModel = new Player($db);
    $result = $playerModel->getPlayingCurrent($playing['id']);

    if (
        $result->num_rows > 0 &&
        $player = $result->fetch_assoc()
    ) {
        http_response_code(200);

        echo json_encode(
            array(
                "playing_phase" => $playing['phase'],
                "current_player_state" => $player['state']
            )
        );
    } else {
        http_response_code(503);

        echo json_encode(array("error" => "Δε βρέθηκε ο τρέχων παίχτης!"));
    }
} else {
    http_response_code(200);

    echo json_encode(
        array(
            "playing_phase" => 0,
            "current_player_state" => 0
        )
    );
}
