<?php
session_start();
if (!isset($_SESSION['errLoginCount']))
	$_SESSION['errLoginCount'] = 0;
if (!isset($_SESSION['timeToLogin']))
	$_SESSION['timeToLogin'] = 0;

// очищает аргумент от тегов и спецсимволов
function clearInput($input) {
	return htmlspecialchars(strip_tags($input));
}

// проверка залогинен ли пользователь
function isUserLogedIn() {
	return isset($_SESSION['user']);
}

// ошибка логина
function loginError() {
	unset($_SESSION['user']);
	$_SESSION['errLoginCount']++;
	if ($_SESSION['errLoginCount']>10) {
		$_SESSION['timeToLogin'] = time()+3600;
	}
	http_response_code(403);
	header('Location: login.php');
}

// проверка логина и пароля
function checkUser($login, $password) {
	$login = clearInput($login);
	$password = clearInput($password);
	$loginFile = file_get_contents(__DIR__ . '/login.json');
	$users = json_decode($loginFile,true);
	if ($_SESSION['timeToLogin']>time()) { //если время разрешенное для залогинивания ещё не наступило
		loginError();
		return false;
	}
	if ($_SESSION['errLoginCount']>5) { //если было 6 неудачных попыток проверить капчу
		if (isset($_POST['g-recaptcha-response'])) {
			$response = clearInput($_POST['g-recaptcha-response']);
			$reqStr="https://www.google.com/recaptcha/api/siteverify";
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $reqStr);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS, 'secret=6LeDDEcUAAAAAFZn34iuhA8k5_BOOvvKTJJwMrf7&response=' . $response);
			$jsonData=curl_exec($ch);
			curl_close($ch);
			$data=json_decode($jsonData,true);
			if (!$data['success']){
				loginError();
				return false;
			}
		}
		else {
			loginError();
			return false;
		}
	}
	if (isset($users[$login])) {
		if ($users[$login]['pass']==hash('sha256',$password)) {
			$_SESSION['user'] = $users[$login];
			$_SESSION['errLoginCount'] = 0;
			$_SESSION['timeToLogin'] = time();
			$_SESSION['guest']=false;
			return true;
		}
		else {
			loginError();
			return false;
		}
	}
	else {
		loginError();
		return false;
	}
}

// проверяет является ли пользователь гостем
function isGuest() {
	return $_SESSION['guest'];
}

// разлогинивание пользователя
function logoutUser() {
	session_destroy();
	header('Location: login.php');
}

?>