<nav id="slide-out" class="side-nav fixed grey lighten-5 hide-on-large-only">
	<ul id="main-navbar-mobile" class="blue-grey-text text-darken-4">
		<img src="img/logo.png" alt="" class="z-depth-5 grey lighten-4">
		<li data-route="/default" id="li-home-mobile" class="waves-effect waves center">Главная</li>
		<li data-activates="dd-mobile" id="li-clients-mobile" class="waves-effect waves center dpd">Клиенты</li>
		<div data-route="/clients" id="dd-mobile" class="container right dropdown-content grey lighten-2"></div>
		<li data-route="/doc" id="li-doc-mobile" class="waves-effect waves center">Общая техническая документация</li>
		<a id="exit-mobile" href="<?=$_SERVER['PHP_SELF'].'?destroy=yes'?>" class="center grey lighten-0 white-text waves-effect waves exit">Выход</a>
	</ul>
		<p class="hide-me">Добро пожаловать, <span id="login-mobile"><?=$_SESSION['user']['sAMAccountName'][0]?></span></p>
</nav>
	<a href="#" data-activates="slide-out" class="button-collapse hide-on-large-only"><i class="material-icons">menu</i></a>