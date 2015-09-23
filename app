#!/usr/bin/env php
<?php

define ('APP_DIR', __DIR__);
define ('APP_MODE_CLI', true);
require APP_DIR.'/vendor/autoload.php';

// Mock $_SERVER environment
$_SERVER['DOCUMENT_ROOT'] = APP_DIR.'/public';
$_SERVER['HTTP_HOST']     = gethostname();
$_SERVER['REQUEST_URI']   = '';

// Load Classes
Loader::addPath(APP_DIR.'/classes');

// Load options
Options::loadPHP(APP_DIR.'/config.php');

// Caching strategy
Cache::using([
	'redis',
	'files' => [
		'cache_dir' => Options::get('cache.dir',APP_DIR.'/cache')
	],
]);

// App bootstrap
include APP_DIR.'/boot.php';

Event::trigger('app.run');

// Routes
foreach (glob(APP_DIR.'/commands/*.php') as $routedef) include $routedef;

CLI::run();
