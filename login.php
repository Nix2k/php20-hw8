<?php
	require_once 'routines.php';
	if (isUserLogedIn()) {
		header('Location: index.php');
		exit;
	}
	if ($_SESSION['timeToLogin']>time()) {
		$remine = $_SESSION['timeToLogin']-time();
		die("Повторите попытку через $remine сек.");
	}
	if (isset($_POST['Login'])) {
		$username = clearInput($_POST['username']);
		$password = clearInput($_POST['password']);
		if (checkUser($username,$password)){
			header('Location: index.php');
			exit;
		}
	}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
	<meta charset="UTF-8">
	<link rel="stylesheet" type="text/css" href="style.css">
	<title>Вход на сайт</title>
	<script src='https://www.google.com/recaptcha/api.js'></script>
</head>
<body>

<h1>Вход на сайт</h1>
<form action="login.php" method="POST">
	<input name="username" placeholder="Имя пользователя" required>
	<input type="password" name="password" placeholder="Пароль" required>
	<?php
		if ($_SESSION['errLoginCount']>5) { //если было 6 неудачных логинов вывести капчу
			echo '<div class="g-recaptcha" data-sitekey="6LeDDEcUAAAAAP8iUMPgUImZNGuBLhTSyZwa8jcD"></div>';
		}
	?>
	<input type="submit" name="Login" value="Войти">
</form>

</body>
</html>