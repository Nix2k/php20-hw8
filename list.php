<?php
	require_once 'routines.php';
	if (!isUserLogedIn()) {
		header('Location: login.php');
		exit;
	}

	$tests = array_slice(scandir('uploads/'), 2);
	echo '<h1>Доступные тесты</h1>';
	$i = 1; 
	foreach ($tests as $filename) {
		echo "<p>$i. <a href='test.php?id=$i'> $filename </a>";
		if (!$_SESSION['guest']){
			echo "<a href='remove.php?id=$i'> Удалить </a></p>";
		}
		$i++;	
	}
?>