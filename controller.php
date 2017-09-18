<?php
	require_once 'classes/autoload.php';
	use itsrb\classes\application as app;

	$app = app::make_app();
	$a = $app->check_session_time();

	if(isset($_REQUEST['d']) && !empty($_REQUEST['d'])) {
		$app->download($_REQUEST['d']);
		exit;
	}

	if($a['is_ajax'] && $a['timeout']) {
		$app->write_json_object($a);
		exit;
	}

	$app->make_http_request( $app->read_json_object() );
?>
