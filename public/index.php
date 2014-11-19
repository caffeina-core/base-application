<?php

/**
 * Front Controller
 */

define ('APP_DIR', dirname(__DIR__));
require APP_DIR.'/vendor/autoload.php';

// Load Classes
Loader::addPath(APP_DIR.'/classes');

// Load options
Options::loadPHP(APP_DIR.'/configs/common.php');
foreach (File::search(APP_DIR.'/configs/','*.php',false) as $opts) {
  (($prfx=basename($opts))!='common') and Options::loadPHP($opts,$prfx);
}

// Caching strategy
Cache::using([
  'redis',
  'files' => [
    'cache_dir' => APP_DIR.'/cache/objects'
  ],
]);

// Init Views
View::using(new View\Twig(APP_DIR.'/templates',[
    'cache'         => Options::get('cache.views',true) ? APP_DIR.'/cache/views' : false,
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
foreach (File::search(APP_DIR.'/routes/','*.php',false) as $routedef) include $routedef;

Event::trigger('app.dispatch');
Route::dispatch();
Response::send();