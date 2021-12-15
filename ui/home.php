<?php
session_start();

if (!isset($_SESSION['signedin'])) {
	header('Location: ../index.html');
	exit;
}
?>

<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>Μουτζούρης</title>
		<link href="style.css" rel="stylesheet" type="text/css">
		<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.1/css/all.css">
	</head>
	<body class="signedin">
		<nav class="navbar">
			<div>
				<h1>Μουτζούρης</h1>
				<a href="controllers/signout.php"><i class="fas fa-sign-out-alt"></i>Αποσύνδεση</a>
			</div>
		</nav>
		<div class="infobar">
			<h2><?=$_SESSION['userName']?></h2>
		</div>
	</body>
</html>