<?php
session_start();

$config = include('../config.php');

$dataJSON = json_encode(
    array(
        ////"player_cnt" => $_POST['player_cnt'],
        "player_cnt" => 2,
        "user_id" =>  $_SESSION['userId']
    )
);

$url = $config['apiUrl'] . "app/api/playings/start";

$client = curl_init();
curl_setopt($client, CURLOPT_URL, $url);
curl_setopt($client, CURLOPT_RETURNTRANSFER, true);
curl_setopt($client, CURLOPT_POSTFIELDS, $dataJSON);
$response = curl_exec($client);

curl_close($client);

$result = json_decode($response);

if ($result == null) {
    echo 'Παρουσιάστηκε άγνωστο σφάλμα κατά την επικοινωνία με το API!';
} else if (isset($result->error)) {
    echo $result->error;
} 

header('Location: ../home.php');
