<nav class="fixed side-nav grey lighten-5">
	<ul id="main-navbar" class="blue-grey-text text-darken-4">
		<img src="img/logo.png" alt="" class="z-depth-5 grey lighten-4">
		<li data-route="/default" id="li-home" class="waves-effect waves center">Главная</li>
		<li data-activates="dd" id="li-clients" class="waves-effect waves center dpd">Клиенты</li>
		<div data-route="/clients" id="dd" class="container right dropdown-content grey lighten-2"></div>
		<li data-route="/doc" id="li-doc" class="waves-effect waves center">Общая техническая документация</li>
	</ul>
		<p class="hide-me">Добро пожаловать, <span id="login"><?=$_SESSION['user']['sAMAccountName'][0]?></span></p>
		<a id="exit" href="<?=$_SERVER['PHP_SELF'].'?destroy=yes'?>" class="center grey lighten-0 white-text waves-effect waves exit">Выход</a>
</nav>