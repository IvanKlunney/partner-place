<!DOCTYPE html>
<html lang="ru">
<head>
    <link href="//fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link href="//fonts.googleapis.com/css?family=Noto+Sans" rel="stylesheet">
    <link type="text/css" rel="stylesheet" href="styles/materialize.css"  media="screen,projection"/>
    <link type="text/css" rel="stylesheet" href="styles/index.css"/>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
	<title>IT Service Retail & Banking</title>
</head>
<body>
<noscript>В Вашем бразуере выключена поддержка JavaScript. Для дальнешей работы с приложением, пожалуйста, включите JS в настройках браузера.</noscript>
	<header> 		
		<div class="row">
			<div class="col l4 m8 s12 offset-l4 offset-m2">
				<h2 class="center hide-me">
					IT Service Retail & Banking
				</h2>
			<div>
		</div>	
	</header>
	<main class="valign-wrapper">
	<div class="container valign">
		<div class="row">
				<div class="col l4 m8 s12 offset-l4 offset-m2">
					<div class="row">
						<form id="main" method="POST" action="<?=$_SERVER['REQUEST_URI']?>">
							<div class="input-field col l12 m12 s12">
								<input name="l" type="text" id="login" class="validate white-text">
								<label for="login">Логин</label>
							</div>
							<div class="input-field col l12 m12 s12"> 
								<input name="p" type="password" id="password" class="validate white-text"> 
								<label for="password">Пароль</label> 
							</div>
							<div class="input-field col l12 m12 s12">
 							 	<button class="btn waves-effect waves-green right red darken-4" type="submit" onclick="return validatingUserData();">Войти<i class="material-icons right">send</i>
									 <?php require_once('templates/spinner.tpl'); ?>
								</button>
							</div>
						</form>
					</div>
				</div>
		</div>
	</div>
	</main>
	<footer>
		<div class="row">
			<div class="col l4 m8 s12 offset-l4 offset-m2 center">
				<p class="blue-grey-text text-lighten-2">
					IT Service Retail & Banking 2017 © All right reserved
				<p>
			</div>
		</div>
	</footer>
    <script src="//code.jquery.com/jquery-2.1.1.min.js"></script>
    <script src="js/materialize.js"></script>
    <script src="js/idx.js"></script>
    <script>
    	window.onload = function() {
    		document.getElementsByTagName('main')[0].style.opacity = 1;
    	}
    </script>
		<?php
			if(isset($_SESSION['err'])) {
				echo "<script>Materialize.toast(\"{$_SESSION['err']}\", 5000)</script>";
			}
		?>
</body>
</html>