<?php
	require_once "classes/autoload.php";
	use itsrb\classes\application as app;

	$app = app::make_app();
	$app->get_session();

	if($_SERVER['REQUEST_METHOD'] === "POST") {
		$app->make_session($_REQUEST['l'], $_REQUEST['p']);
	}

	if(isset($_REQUEST['redirect']) && $_REQUEST['redirect'] === 'true' && $_REQUEST['h'] === $_SESSION['h']) {
		unset($_SESSION['h']);
		unset($_SESSION['redirect']);
		$app->_require("./templates/redirect.tpl");
		exit;
	}

	$app->check_session_error();
	$app->_require("./templates/idx.tpl");
?>