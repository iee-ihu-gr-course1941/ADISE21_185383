<?php
session_start();

$config = include('../config.php');

// Φέρε την τρέχουσα κατάσταση του board

$url = $config['apiUrl'] . "app/api/playings/board.php?user_id=" . $_SESSION['userId'];

$client = curl_init();
curl_setopt($client, CURLOPT_URL, $url);
curl_setopt($client, CURLOPT_RETURNTRANSFER, true);
$response = curl_exec($client);

curl_close($client);

$board = json_decode($response);

if ($board == null) {
    echo 'Παρουσιάστηκε άγνωστο σφάλμα κατά την επικοινωνία με το API!';
} else if (isset($board->error)) {
    echo $board->error;
} else {
    // O τρέχων χρήστης μόλις ζήτησε να ενταχθεί στο ενεργό παίξιμο ...
    if (
        $board->playing_phase == 1
        && $board->current_user_state == 0
    ) {
        // Καταχώρησε τον τρέχοντα χρήστη ως παίκτη

        $dataJSON = json_encode(
            array(
                "playing_id" => $board->playing_id,
                "user_id" =>  $_SESSION['userId']
            )
        );

        $url = $config['apiUrl'] . "app/api/players/add.php";

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
    } else if (
        ($board->playing_phase == 2 && $board->current_user_state == 1)
        || ($board->playing_phase == 3 && $board->current_user_state == 3)
    ) {
        // Ρίξε τα χαρτιά που επέλεξε - να ρίξει - ο τρέχοντας χρήστης-παίκτης

        $dataJSON = json_encode(
            array(
                "playing_id" => $board->playing_id,
                "user_id" =>  $_SESSION['userId'],
                "cards_to_throw" => trim($_POST['cards_to_throw'])
            )
        );

        $url = $config['apiUrl'] . "app/api/players/throw.php";

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
    } else if (
        $board->playing_phase == 3 && $board->current_user_state == 2
    ) {
        // Δώσε τρέχοντα χρήστη-παίκτη το χαρτί που επέλεξε

        $dataJSON = json_encode(
            array(
                "playing_id" => $board->playing_id,
                "user_id" =>  $_SESSION['userId'],
                "card_to_pick" => trim($_POST['card_to_pick'])
            )
        );

        $url = $config['apiUrl'] . "app/api/.php";

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
    }
}

header('Location: ../home.php');
