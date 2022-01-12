<?php
session_start();

$config = include('../config.php');

$dataJSON = json_encode(
    array(
        "player_cnt" => $_POST['player_cnt']
    )
);

// Κάλεσε api

$url = $config['apiUrl'] . "app/api/playings/start.php";

$client = curl_init();
curl_setopt($client, CURLOPT_URL, $url);
curl_setopt($client, CURLOPT_RETURNTRANSFER, true);
curl_setopt($client, CURLOPT_POSTFIELDS, $dataJSON);
$headers = array();
$headers[] = "X-Token: " . $_SESSION['userId'];
curl_setopt($client, CURLOPT_HTTPHEADER, $headers);

$response = curl_exec($client);

curl_close($client);

$result = json_decode($response);

if ($result == null) {
    echo '<p style="color:red"><b>Παρουσιάστηκε άγνωστο σφάλμα κατά την επικοινωνία με το API!</b></p>';
} else if (isset($result->error)) {
    echo '<p style="color:red"><b>' . $result->error . '</b></p>';
} 

header('Location: ../home.php');
