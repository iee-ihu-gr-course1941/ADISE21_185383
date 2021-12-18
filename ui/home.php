<?php
session_start();

if (!isset($_SESSION['signedin'])) {
	header('Location: ../index.html');
	exit;
}

$config = include('config.php');

$url = $config['apiUrl'] . "app/api/playings/board";

$client = curl_init();
curl_setopt($client, CURLOPT_URL, $url);
curl_setopt($client, CURLOPT_RETURNTRANSFER, true);
$response = curl_exec($client);

curl_close($client);

$board = null;
$info = null;

$board = json_decode($response);

if ($board == null) {
	echo 'Παρουσιάστηκε άγνωστο σφάλμα κατά την επικοινωνία με το API!';
} else if (isset($board->error)) {
	echo $board->error;
} else {
	if ($board->playing_phase == 0) {
		$info = "ΧΩΡΙΣ ΠΑΙΧΝΙΔΙ";
	} else if ($board->playing_phase == 1) {
		if ($board->current_player_state == 0) {
			$info = "ΕΝΤΑΞΗ ΠΑΙΚΤΩΝ => Θέλεις να παίξεις;";
		}
		else {
			$info = "ΕΝΤΑΞΗ ΠΑΙΚΤΩΝ => Περίμενε....";
		}
	}
}

?>

<!DOCTYPE html>
<html>

<head>
	<meta charset="utf-8">
	<title>Μουτζούρης</title>
	<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.1/css/all.css">
	<link href="style.css" rel="stylesheet" type="text/css">
</head>

<body class="signedin">
	<nav class="navbar">
		<div>
			<h1>Μουτζούρης</h1>
			<h2><?= $_SESSION['userName'] ?></h2>
			<a href="actions/signout.php"><i class="fas fa-sign-out-alt"></i>Αποσύνδεση</a>
		</div>
	</nav>
	<div class="infobar">
		<h2><?= $info ?></h2>
	</div>
	<div class="commandbar">
		<?php if ($board != null && $board->playing_phase == 0) { ?>
			<form action="actions/start.php" method="post" target="">
				<input type="submit" value="Έναρξη">
			</form>
		<?php } ?>
	</div>
</body>

</html>