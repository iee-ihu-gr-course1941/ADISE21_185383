<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once '../../infrastructure/DB.php';
include_once '../../models/User.php';

$conn = DB::getConnection();

$data = json_decode(file_get_contents("php://input"));

if ($data) {
    $user = User::getByName($conn, $data->username);

    if ($user != null) {
        if (strcmp($data->password, $user['password']) == 0) {
            http_response_code(200);
            echo json_encode($user);
        } else {
            http_response_code(403);
            echo json_encode(
                array("error" => "Λανθασμένο συνθηματικό!")
            );
        }
    } else {
        http_response_code(403);
        echo json_encode(
            array("error" => "Ανύπαρκτος χρήστης!")
        );
    }
} else {
    http_response_code(403);
    echo json_encode(
        array("error" => "Ανύπαρκτος χρήστης!")
    );
}
