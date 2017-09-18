<!DOCTYPE html>
<html lang="ru">
<head>
	<meta charset="UTF-8">
	<title>Redirection...</title>
	<link rel="stylesheet" href="styles/stub.css">
</head>
<body>
	<div class="container">
		<div class="col l12 m12 s12">
				<div>
					<img src="img/loader.gif" alt="loader">
				</div>
		</div>
	</div>
	<div class="hide-me">
	<?php 
		require_once('templates/nav.tpl'); 
		require_once('templates/nav_mobile.tpl'); 
		require_once('templates/main_body.tpl');
	?>
	</div>
		<script src="https://code.jquery.com/jquery-2.1.1.min.js"></script>
    	<script src="js/materialize.js"></script>
		<script src="js/main.js"></script>
</body>
</html>