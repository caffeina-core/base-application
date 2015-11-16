<?php

/**
 * Server utilities
 */

CLI::on('server :action',function($action){
  switch($action){
    case 'run':
        $port = 8888;
        Shell::php("-S","0.0.0.0:$port","-t",__DIR__.'/../public')->run();
      break;
    default:
        echo "Server utilities.",PHP_EOL;
        echo "- Available actions: run",PHP_EOL;
      break;
  }
});
