<?php
session_start();

if (!isset($_SESSION['signedin'])) {
	header('Location: ../index.html');
	exit;
}

$config = include('config.php');

$url = $config['apiUrl'] . "app/api/playings/board/" . $_SESSION['userId'];

$client = curl_init();
curl_setopt($client, CURLOPT_URL, $url);
curl_setopt($client, CURLOPT_RETURNTRANSFER, true);
$response = curl_exec($client);

curl_close($client);

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
		if ($board->current_user_state == 0) {
			$info = "ΕΝΤΑΞΗ ΠΑΙΚΤΩΝ => Θέλεις να παίξεις;";
		} else {
			$info = "ΕΝΤΑΞΗ ΠΑΙΚΤΩΝ => Παρακαλώ, περίμενε ...";
		}
	} else if ($board->playing_phase == 2) {
		if ($board->current_user_state == 1) {
			if ($board->current_user_is_current_player) {
				$info = "ΑΡΧΙΚΟ ΡΙΞΙΜΟ ΔΙΠΛΩΝ => Ρίξε τα διπλά χαρτιά.";
			} else {
				$info = "ΑΡΧΙΚΟ ΡΙΞΙΜΟ ΔΙΠΛΩΝ => Παρακαλώ, περίμενε τη σειρά σου ...";
			}
		} else if ($board->current_user_state == 0) {
			$info = "ΑΡΧΙΚΟ ΡΙΞΙΜΟ ΔΙΠΛΩΝ => Δυστυχώς δε συμμετέχεις στο ενεργό παίξιμο! Μία άλλη φορά ...";
		} else {
			$info = "ΑΡΧΙΚΟ ΡΙΞΙΜΟ ΔΙΠΛΩΝ => Παρακαλώ, περίμενε ...";
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
		<?php if (
			$board != null
			&& $board->playing_phase == 0
		) { ?>
			<form action="actions/start.php" method="post" target="">
				<label for="player_cnt">
					Πλήθος Παικτών (2-6):
				</label>
				<input type="range" name="player_cnt" placeholder="Πλήθος Παικτών" id="player_cnt" min="2" max="6" value="2" step="1" oninput="onPlayerCntUpdate(value)" required>
				<output for="player_cnt" id="player_cnt_volume">2</output>
				<input type="submit" value="Έναρξη">
			</form>
		<?php } ?>

		<?php if (
			$board != null
			&& $board->current_user_is_current_player
			&& $board->playing_phase == 1
			&& $board->current_user_state == 0
		) { ?>
			<form action="actions/go.php" method="post" target="">
				<input type="submit" value="Πάμε">
			</form>
		<?php } ?>

		<?php if (
			$board != null
			&& $board->current_user_is_current_player
			&& $board->playing_phase == 2
			&& $board->current_user_state == 1
		) { ?>
			<form action="actions/go.php" method="post" target="">
				<label for="cards_to_throw">
					Επίλεξε τα διπλά χαρτιά ανά ζεύγη, διαχωρίζοντας τα ζεύγη με καθέτους (π.χ. 3,24|4,16):
				</label>
				<textarea name="cards_to_throw" id="cards_to_throw">
				</textarea>
				<input type="submit" value="Πάμε">
			</form>
		<?php } ?>
	</div>
	<div class="cardarea">
		<?php if (
			$board != null
			&& $board->playing_phase == 2
		) { ?>
			<?php foreach ($board->players as $player) {
				$i = 1 ?>
				<h2><?= $player->name ?></h2>
				<?php foreach ($player->cards as $card) { ?>
					<b>(<?= $i++ ?>)</b>
					<em><?= $card->label ?></em>
				<?php } ?>
			<?php } ?>
		<?php } ?>
	</div>
</body>

<script>
	function onPlayerCntUpdate(vol) {
		document.querySelector('#player_cnt_volume').value = vol;
	}
</script>

</html>