<?php

/**
 * Server utilities
 */

CLI::on('server :action',function($action){
  switch($action){
    case 'run':
        $host = CLI::input("host",'0.0.0.0');
        $port = CLI::input("port",8888);
        CLI::write("[<white>APP</white>] <purple>Starting</purple> webserver on <cyan>$host</cyan>:<green>$port</green>");
        CLI::writeln("");
        exec("(which open && open http://$host:$port/); php -S $host:$port -t ".dirname(__DIR__)."/public");
      break;
    default:
        echo "Server utilities.",PHP_EOL;
        echo "- Available actions: run",PHP_EOL;
      break;
  }
});
