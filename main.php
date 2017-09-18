<?php
	require_once "classes/autoload.php";
	use itsrb\classes\application as app;

	$app = app::make_app();
	$app->check_session_time();

	if(isset($_GET['destroy'])) {
		$app->destroy_session();
	}

	$o   = $app->make_dir_object($_SESSION);
	$d   = $app->make_dir_iterator();
	$p   = $app->make_db();

	require_once "./templates/main.tpl";
?>
