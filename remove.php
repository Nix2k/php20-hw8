<?php
	require_once 'routines.php';
	if (!isUserLogedIn()) {
		header('Location: login.php');
		exit;
	}
	if ((isset($_SESSION['guest']))&&($_SESSION['guest']==false)){
		if (isset($_GET['id'])) { //Задан номер теста
			$id = clearInput($_GET['id']);
			$tests = array_slice(scandir('uploads/'), 2);
			$countOfTests = count($tests);			
			if ((is_numeric($id))&&($id>=1)&&($id<=$countOfTests)) { // 0 <= номер теста <= равно количества тестов
				$filename = $tests[$id-1];
				unlink('uploads/' . $filename);
			}
			else {
				http_response_code(404);
				die('Неверный номер теста');
			}
		}
	}
	header('Location: index.php');
	exit;
?>
