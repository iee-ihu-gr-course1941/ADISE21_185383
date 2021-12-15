<?php
session_start();

if (
    isset($_POST['username']) && $_POST['username'] != "" &&
    isset($_POST['password']) && $_POST['password'] != ""
) {
    $userName = $_POST['username'];
    $password = $_POST['password'];

    $url = "http://localhost/ADISE21_185383/app/api/users/checkPwd.php?name=" . $userName . "&password=" . $password;

    $client = curl_init($url);
    curl_setopt($client, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($client);

    $result = json_decode($response);

    if ($result == null) {
        echo 'Παρουσιάστηκε άγνωστο σφάλμα κατά την επικοινωνία με το API!';
    } else if (isset($result->error)) {
        echo $result->error;
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
