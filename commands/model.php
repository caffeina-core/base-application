<?php

/**
 * Server utilities
 */



CLI::on('model :action',function($action){
  switch($action){
    case 'generate':
      CLI::writeln("<yellow>Building models from database</yellow>...\n");

      foreach (SQL::column("show tables") as $table) {
        $model = (object)[];
        $model->name = preg_replace(['(ies$)','(s$)'],['y',''],strtr(ucwords(strtr(strtolower($table),['_'=>' '])),[' '=>'']));
        $fields  = SQL::all("describe $table");
        $primary = ''; $valids  = [];
        $ml   = array_reduce($fields, function($r,$x){ return max($r,strlen($x->Field)); },0);
        $last = end($fields)->Field;
        $params  = implode('         ',array_map(function($e=null) use (&$primary, &$valids, $ml, $last){
          if (!$e) return;
          $d = ''; $validations = [];
          if ($e->Key == 'PRI') $primary = $e->Field;
          if ($e->{'Null'} == 'NO') $validations[] = 'required';
          if (substr($e->Type,0,4) == 'enum') $validations[] = 'in_array:['.str_replace("'",'"',substr($e->Type,5,-1)).']';
          $validations = implode(' | ',$validations);
          $_s = str_repeat(' ', $ml - strlen($e->Field));
          $d = " $_s = " . (is_numeric($e->Default) ? $e->Default : ($e->Default===null ? 'null' : "'{$e->Default}'"));
          $valids[] = "'{$e->Field}' $_s => '$validations',\n";
          $t = $e->Field == $last ? ';' : ',';
          return "\${$e->Field}$d$t // {$e->Type}\n";
        }, $fields));
        if ($params) $params = "  public $params";

        $create = str_replace(
          ["CREATE TABLE","\n"],
          ["CREATE TABLE IF NOT EXISTS","\n      "],
        addslashes(SQL::value("show create table $table",[],1)));

        $valids = rtrim(implode('      ',$valids),"\n");
        $date = date("Y-m-d H:i:s");
        $model->code =
<<<"EOC"
<?php

/**
 * Model class for table $table
 * Automatically generated on $date
 */

class {$model->name} extends Model {
  const _PRIMARY_KEY_ = '$table.$primary';

$params

  public static function createTable(){
    SQL::exec('
      $create
    ');
  }

  public static function validate(\$data){
    return Check::valid([
      $valids
    ], \$data);
  }

}
EOC;
        $models[$table] = $model;
      }

      $force = CLI::input('force', false);
      foreach($models as $table => $model){
        $file = dirname(__DIR__)."/models/{$model->name}.php";
        if (!$force && file_exists($file)){
          CLI::writeln("<red>Already founded a model named <b>{$model->name}</b>, skipping...</red>");
        } else {
          CLI::writeln("<green>Compiling</green> model : <b>{$model->name}</b>");
          file_put_contents($file, $model->code);
        }
      }
      break;
    default:
        echo "Model utilities.",PHP_EOL;
        echo "- Available actions: generate --force",PHP_EOL;
      break;
  }
});


