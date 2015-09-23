<?php

/**
 * Application Bootstrap
 */

// Define your custom logic to retrieve the current APP_ENV
if (strrpos($_SERVER['HTTP_HOST'],'.local')===0){
	define ('APP_ENV','development');
} else if (strpos($_SERVER['HTTP_HOST'],'stage.')===0){
	define ('APP_ENV','staging');
} else {
	define ('APP_ENV','production');
}

// Load environment-specific config
Options::loadPHP(APP_DIR.'/configs/'.APP_ENV.'.php');
