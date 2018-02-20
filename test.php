<?php
	require_once 'routines.php';
	if (!isUserLogedIn()) {
		header('Location: login.php');
		exit;
	}

	if (isset($_GET['id'])) { //Задан номер теста
		$id = clearInput($_GET['id']);
		$tests = array_slice(scandir('uploads/'), 2);
		$countOfTests = count($tests);			
		if ((is_numeric($id))&&($id>=1)&&($id<=$countOfTests)) { // 0 <= номер теста <= равно количества тестов
			$filename = $tests[$id-1];
			$jsonData = file_get_contents("uploads/$filename");
			$test = json_decode($jsonData);
			$title = array_shift($test);

			if (isset($_GET['ready'])) { //Если форма отправлена
				$username = $_SESSION['user']['name'];
				$scores = 0; //количество верных ответов
				$numOfQuestions = 0; //количество вопросов
				foreach ($test as $qId => $question) {
					$isRight = true;
					foreach ($question->options as $optionId => $option) {
						if ((isset($_GET[$qId.'_'.$optionId])) xor ($option[1]==1))
							$isRight = false;
					}
					if ($isRight) {
						$scores++;
					}
					$numOfQuestions++;
				}
				$mark = round($scores*100/$numOfQuestions);
				//Генерация файла с сертификатом
				$imgBlank = imagecreatefrompng(__DIR__.'/img/blank.png');
				$xAx = round(imagesx($imgBlank) / 2);
				$fontFile = __DIR__.'/fonts/MarckScript.ttf';
				$textColor = imagecolorallocate($imgBlank, 0, 0, 0);
				$textBox = imagettfbbox(36, 0, $fontFile, "$username");
				$textHalfWidth = round(($textBox[2] - $textBox[0])/2);
				imagettftext($imgBlank,36,0,$xAx-$textHalfWidth,350,$textColor,$fontFile,"$username");
				$textBox = imagettfbbox(36, 0, $fontFile, "выполнил тест");
				$textHalfWidth = round(($textBox[2] - $textBox[0])/2);
				imagettftext($imgBlank,32,0,$xAx-$textHalfWidth,425,$textColor,$fontFile,"выполнил тест");
				$textBox = imagettfbbox(36, 0, $fontFile, "$title->title");
				$textHalfWidth = round(($textBox[2] - $textBox[0])/2);
				imagettftext($imgBlank,36,0,$xAx-$textHalfWidth,500,$textColor,$fontFile,"$title->title");
				$textBox = imagettfbbox(36, 0, $fontFile, "Результат тестирования: $mark из 100");
				$textHalfWidth = round(($textBox[2] - $textBox[0])/2);
				imagettftext($imgBlank,36,0,$xAx-$textHalfWidth,575,$textColor,$fontFile,"Результат тестирования: $mark из 100");
				header('Content-Type: image/png');
				header('Content-Disposition: attachment; filename="certificate.png"');
				imagepng($imgBlank);
				imagedestroy($imgBlank);
				exit();
			}
		}
		else {
			http_response_code(404);
			die('Неверный номер теста');
		}
	}
	else {
		http_response_code(400);
		die('Не задан параметр id, указывающий номер теста');
	}

?>
<!DOCTYPE html>
<html lang="ru">
<head>
	<meta charset="UTF-8">
	<link rel="stylesheet" type="text/css" href="style.css">
	<title>Тест</title>
</head>
<body>
<?php 
	include 'menu.php';
	//Отрисовка формы
	echo "<h1>$title->title</h1>";
	echo "<form method='GET' action='test.php'>";
	foreach ($test as $qId => $question) {
		echo '<p><b>'.clearInput($question->text).'</b></p>';
		foreach ($question->options as $optionId => $option) {
			echo "<input type='checkbox' name='".clearInput($qId)." ".clearInput($optionId)."' value='1'>".clearInput($option[0])."<br>";
		}
	}
	echo "<input type='hidden' name='id' value='$id'>";
	echo "<br><input type='submit' name='ready' value='Проверить'></form>";
?>
</body>
</html>