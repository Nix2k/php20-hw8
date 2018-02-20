<?php
	require_once 'routines.php';
	if (isUserLogedIn()) {
		header('Location: index.php');
		exit;
	}

	if ((isset($_POST['Login']))&&(isset($_POST['username']))) {
		$username = clearInput($_POST['username']);
		$_SESSION['user']['name'] = $username;
		$_SESSION['guest']=true;
		header('Location: index.php');
		exit;
	}

	header('Location: login.php');
	exit;
?>