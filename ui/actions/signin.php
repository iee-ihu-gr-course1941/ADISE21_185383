<?php
session_start();

$config = include('../config.php');

if (
    isset($_POST['username']) && $_POST['username'] != "" &&
    isset($_POST['password']) && $_POST['password'] != ""
) {
    $dataJSON = json_encode(
        array(
            "username" => $_POST['username'],
            "password" => $_POST['password']
        )
    );

    $url = $config['apiUrl'] . "app/api/users/checkPwd.php";

    $client = curl_init();
    curl_setopt($client, CURLOPT_URL, $url);
    curl_setopt($client, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($client, CURLOPT_POSTFIELDS, $dataJSON);
    $response = curl_exec($client);

    curl_close($client);

    $result = json_decode($response);

    if ($result == null) {
        echo '<p style="color:red"><b>Παρουσιάστηκε άγνωστο σφάλμα κατά την επικοινωνία με το API!</b></p>';
    } else if (isset($result->error)) {
        echo '<p style="color:red"><b>' . $result->error . '</b></p>';
    } else {
        session_regenerate_id();

        $_SESSION['signedin'] = TRUE;
        $_SESSION['userName'] = $_POST['username'];
        $_SESSION['userId'] = $result->id;

        header('Location: ../home.php');
    }
} else {
    echo 'Ανύπαρκτος χρήστης!';
}
