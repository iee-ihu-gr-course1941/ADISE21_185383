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
    // Πρόσθεσε τον χρήστη ως παίκτη

    $player = array(
        "id" => $data->user_id,
        "playing_id" => $data->playing_id,
        "playing_iscurrent" => 0,
        "state" => 1
    );

    Player::add($conn, $player);

    // Φέρε το ενεργό παίξιμο

    $playing = Playing::getActive($conn, $data->user_id);

    // Φέρε το πλήθος των παικτών που έχουν μέχρι στιγμής ενταχθεί στο ενεργό παίξιμο

    $actual_player_cnt = Player::getActualPlayerCnt($conn, $data->playing_id);

    // Αν έχει συμπληρωθεί το απαιτούμενο πλήθος παικτών,
    // προχώρησε στην επόμενη φάση και μοίρασε τα χαρτιά ...

    if ($playing['player_cnt'] == $actual_player_cnt) {
        $playing['phase'] = 2;

        Playing::update($conn, $playing);

        Playing::deal($conn, $playing);
    }

    http_response_code(200);
} else {
    http_response_code(400);
    echo json_encode(array("error" => "Ελλιπή στοιχεία!"));
}
