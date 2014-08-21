#!/usr/bin/env php
<?php

define ('APP_DIR', __DIR__);
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
    'cache_dir' => Options::get('cache.dir',APP_DIR.'/cache')
  ],
]);

// App bootstrap
include APP_DIR.'/boot.php';

Event::trigger('app.run');

// Routes
foreach (File::search(APP_DIR.'/commands/','*.php',false) as $routedef) include $routedef;

CLI::run();
