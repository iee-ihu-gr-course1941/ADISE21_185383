<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once '../../infrastructure/DB.php';
include_once '../../models/Playing.php';
include_once '../../models/Player.php';

$database = new DB();
$db = $database->getConnection();

$data = json_decode(file_get_contents("php://input"));

if ($data) {
    $playingModel = new Playing($db);
    $playingModel->active = 1;
    $playingModel->phase = 1;
    $playingModel->player_cnt = $data->player_cnt;

    $playing_id = $playingModel->add();

    if($playing_id != 0){         
        $player = new Player($db);
        $player->id = $data->user_id;
        $player->playing_id = $playing_id;
        $player->playing_seqno = 1;
        $player->playing_iscurrent = 1;
        $player->state = 1;

        $player->add();

        http_response_code(200);         
    } else{         
        http_response_code(503);        
        echo json_encode(array("error" => "Αδυναμία έναρξης παιχνιδιού!"));
    }    

    http_response_code(200);
} else {
    http_response_code(400);
    echo json_encode( array("error" => "Ελλιπή στοιχεία!"));
}
