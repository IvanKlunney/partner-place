<!DOCTYPE html>
<html lang="ru">
<head>
	<meta charset="UTF-8"> 
	<link href="//fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
	<link href="//fonts.googleapis.com/css?family=Noto+Sans" rel="stylesheet">
    <link type="text/css" rel="stylesheet" href="styles/materialize.css"  media="screen,projection"/>
    <link type="text/css" rel="stylesheet" href="styles/main.css"/>
	<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no"/>
	<title>ИT СЕРВИС Ритэйл энд Банкинг</title>
</head>
<body>
	<main>
		<div class="container">
			<?php
			require_once('templates/nav.tpl'); 
			require_once('templates/nav_mobile.tpl');
			require_once('templates/main_body.tpl'); 
			?>
	</main>
	<footer class="grey lighten-4 z-depth-5">
		<div class="containter">
			<div class="row">
				<div class="col l12 m12 s12">
					<div class="container">
						<div class="col offset-l1">
						<div id="manager" class="grey-text text-darken-3">Менеджер: <?php require('templates/spinner.tpl'); ?><a href=""><span class="black-text opaque hide-me" id="man_ident"></span></a></div>
						<div class="divider"></div>
						<div id="contact" class="grey-text text-darken-3">Контактный телефон: <?php require('templates/spinner.tpl'); ?><span class="black-text opaque hide-me" id="phone"></span></div>
						<div class="divider"></div>
						</div>
					</div>
				</div>
			</div>
				<div class="row foot">
					<div class="container">
						<div class="offset-l1 col l12 m12 s12">
							<a class="blue-grey-text text-lighten-1 right" href="mailto:admin@itsrb.ru">Связаться с администратором</a>
						</div>
					</div>
        		</div>
			<div class="row foot">
				<div class="divider"></div>
				<div class="container">
					<div class="col l12 m12 s12 offset-l1">
						<p class="right grey-text text-darken-1">ИT СЕРВИС Ритэйл энд Банкинг". Адрес: 105066, г. Москва, ул. Ольховская, д.4, корп.1</p>
					</div>
				</div>
			</div>

		</div>
	</footer>
	<script src="https://code.jquery.com/jquery-2.1.1.min.js"></script>
    <script src="js/materialize.js"></script>
 	<script src="js/main.js"></script>
 	<script>
  		$('.button-collapse').sideNav({
  		    closeOnClick: true,
  		    edge: 'left', 
  		    draggable: true
  		  }
  		);	
</script>
</body>
</html>