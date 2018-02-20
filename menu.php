<nav>
	<a href="index.php">Главная</a>
	<?php
		require_once 'routines.php';
		if (!isGuest()) {
			echo '<a href="admin.php">Добавление тестов</a>';
		}
		if (isUserLogedIn()) {
			echo '<a href="logout.php">Выйти</a>';
		}
	?>
</nav>	