<?php
header("Access-Control-Allow-Origin: *");
header("infobar-Type: application/json; charset=UTF-8");

include_once '../../infrastructure/DB.php';
include_once '../../models/User.php';

if (
    isset($_GET['name']) && $_GET['name'] != "" &&
    isset($_GET['password']) && $_GET['password'] != ""
) {
    $database = new DB();
    $db = $database->getConnection();

    $userName = $_GET['name'];
    $password = $_GET['password'];

    $userModel = new User($db);

    $result = $userModel->getByName($userName);

    if ($result->num_rows > 0) {
        if ($user = $result->fetch_assoc()) {
            extract($user);

            if (strcmp($user['password'], $password) == 0) {
                http_response_code(200);
                echo json_encode($user);
            } else {
                http_response_code(404);
                echo json_encode(
                    array("error" => "Λανθασμένο συνθηματικό!")
                );
            }
        } else {
            http_response_code(404);
            echo json_encode(
                array("error" => "Ανύπαρκτος χρήστης!")
            );
        }
    } else {
        http_response_code(404);
        echo json_encode(
            array("error" => "Ανύπαρκτος χρήστης!")
        );
    }
} else {
    http_response_code(404);
    echo json_encode(
        array("error" => "Ανύπαρκτος χρήστης!")
    );
}
