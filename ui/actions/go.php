<?php
session_start();

$config = include('../config.php');

// Φέρε την τρέχουσα κατάσταση του board

$url = $config['apiUrl'] . "app/api/playings/board.php";

$client = curl_init();
curl_setopt($client, CURLOPT_URL, $url);
curl_setopt($client, CURLOPT_RETURNTRANSFER, true);
$headers = array();
$headers[] = "X-Token: " . $_SESSION['userId'];
curl_setopt($client, CURLOPT_HTTPHEADER, $headers);

$response = curl_exec($client);

curl_close($client);

$board = json_decode($response);

if ($board == null) {
    echo '<p style="color:red"><b>Παρουσιάστηκε άγνωστο σφάλμα κατά την επικοινωνία με το API!</b></p>';
} else if (isset($board->error)) {
    echo '<p style="color:red"><b>' . $board->error . '</b></p>';
} else {
    // O τρέχων χρήστης μόλις ζήτησε να ενταχθεί στο ενεργό παίξιμο ...
    if (
        $board->playing_phase == 1
        && $board->current_user_state == 0
    ) {
        // Καταχώρησε τον τρέχοντα χρήστη ως παίκτη

        $dataJSON = json_encode(
            array(
                "playing_id" => $board->playing_id
            )
        );

        $url = $config['apiUrl'] . "app/api/players/add.php";

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
    } else if (
        ($board->playing_phase == 2 && $board->current_user_state == 1)
        || ($board->playing_phase == 3 && $board->current_user_state == 3)
    ) {
        // Ρίξε τα χαρτιά που επέλεξε - να ρίξει - ο τρέχοντας χρήστης-παίκτης

        $dataJSON = json_encode(
            array(
                "playing_id" => $board->playing_id,
                "cards_to_throw" => trim($_POST['cards_to_throw'])
            )
        );

        $url = $config['apiUrl'] . "app/api/players/throw.php";

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
    } else if (
        $board->playing_phase == 3 && $board->current_user_state == 2
    ) {
        // Δώσε τρέχοντα χρήστη-παίκτη το χαρτί που επέλεξε

        $dataJSON = json_encode(
            array(
                "playing_id" => $board->playing_id,
                "card_to_pick" => trim($_POST['card_to_pick'])
            )
        );

        $url = $config['apiUrl'] . "app/api/players/pick.php";

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
    }
}

header('Location: ../home.php');
