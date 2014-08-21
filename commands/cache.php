<?php

/**
 * Cache utilities
 */
 
CLI::on('cache :action',function($action){
  switch($action){
    case 'clear':
        Shell::sequence(
          Shell::rm('-rf '.APP_DIR.'/cache/*'),
          Shell::mkdir(APP_DIR.'/cache/objects',APP_DIR.'/cache/views')
        )->run();
      break;
    default:
      echo "Cache utilities.",PHP_EOL;
      echo "Available actions: clear",PHP_EOL;
      break;
  }
});
