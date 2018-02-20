<?php
	require_once 'routines.php';
	if (!isUserLogedIn()) {
		header('Location: login.php');
		exit;
	}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
	<meta charset="UTF-8">
	<link rel="stylesheet" type="text/css" href="style.css">
	<title>Тестирование</title>
	<script src='https://www.google.com/recaptcha/api.js'></script>
</head>
<body>

<?php 
	include 'menu.php';
	include 'list.php';
?>

</body>
</html>