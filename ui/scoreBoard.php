<?php
session_start();

if (!isset($_SESSION['signedin'])) {
	header('Location: ../index.html');
	exit;
}

$config = include('config.php');

// Κάλεσε api

$url = $config['apiUrl'] . "app/api/playings/scoreBoard.php";

$client = curl_init();
curl_setopt($client, CURLOPT_URL, $url);
curl_setopt($client, CURLOPT_RETURNTRANSFER, true);
$headers = array();
$headers[] = "X-Token: " . $_SESSION['userId'];
curl_setopt($client, CURLOPT_HTTPHEADER, $headers);

$response = curl_exec($client);

curl_close($client);

$info = null;
$scoreBoard = json_decode($response);

if ($scoreBoard == null) {
	echo '<p style="color:red"><b>Παρουσιάστηκε άγνωστο σφάλμα κατά την επικοινωνία με το API!</b></p>';
} else if (isset($scoreBoard->error)) {
	echo '<p style="color:red"><b>' . $scoreBoard->error . '</b></p>';
} else {
	$info = "ΑΠΟΤΕΛΕΣΜΑΤΑ ΠΑΛΑΙΟΤΕΡΩΝ ΠΑΙΧΝΙΔΙΩΝ";
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
			<a href="home.php"><i class="fas fa-home"></i>Αρχική Σελίδα</a>
			<a href="actions/signout.php"><i class="fas fa-sign-out-alt"></i>Αποσύνδεση</a>
		</div>
	</nav>
	<div class="infobar">
		<h2><?= $info ?></h2>
	</div>
	<div>
		<?php
		if (isset($scoreBoard->playings)) {
			foreach ($scoreBoard->playings as $playing) {
				$i = 1 ?>
				<h2>Παίξιμο #<?= $playing->id ?></h2>
				<?php foreach ($playing->players as $player) { ?>
					<h3><?= $i++ ?>. <?= $player->name ?> (χαρτιά: <?= $player->final_card_cnt ?>)</h3>
				<?php } ?>
			<?php } ?>
		<?php } ?>
	</div>
</body>

</html>