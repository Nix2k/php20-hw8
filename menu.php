<nav>
	<a href="index.php">Главная</a>
	<a href="admin.php">Добавление тестов</a>
	<?php
		require_once 'routines.php';
		if (isUserLogedIn()) {
			echo '<a href="logout.php">Выйти</a>';
		}
	?>
</nav>
<br>	