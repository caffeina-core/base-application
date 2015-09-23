<?php

/**
 * Front Controller
 */

define ('APP_DIR', dirname(__DIR__));
define ('APP_MODE_CLI', false);
require APP_DIR.'/vendor/autoload.php';

// Load Classes
Loader::addPath(APP_DIR.'/classes');

// Load options
Options::loadPHP(APP_DIR.'/config.php');

// Temp directory
define ('TEMP_DIR', Options::get('cache.directory',sys_get_temp_dir()));

// Caching strategy
Cache::using([
  'files' => [
    'cache_dir' => TEMP_DIR
  ],
]);

// Init Views
View::using(new View\Twig(APP_DIR.'/templates',[
    'cache'         => Options::get('cache.views',true) ? TEMP_DIR : false,
    'auto_reload'   => Options::get('debug',false),
]));

View::addGlobals([
  'BASE_URL'  => rtrim(dirname($_SERVER['PHP_SELF']),'/').'/',
  'CACHEBUST' => Options::get('debug',false) ? '?v='.time() : '',
]);

// App bootstrap
include APP_DIR.'/boot.php';

Event::trigger('app.run');

// Routes
foreach (glob(APP_DIR.'/routes/*.php') as $routedef) include $routedef;

Event::trigger('app.dispatch');
Route::dispatch();
Response::send();
