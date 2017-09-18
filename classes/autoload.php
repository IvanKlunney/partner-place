<?php
define("PREFIX", "{$_SERVER['DOCUMENT_ROOT']}/classes/");
define("SUFFIX", ".class.php");
define("NS_LEVEL_TREE", 2);

	spl_autoload_register(function ($class){	
			if(__NAMESPACE__) {
				require_once PREFIX .$class .SUFFIX;
			} else {
				require_once PREFIX .explode('\\', $class)[NS_LEVEL_TREE] .SUFFIX;
			}
	});
?>