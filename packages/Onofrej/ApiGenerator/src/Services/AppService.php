<?php
namespace Onofrej\ApiGenerator\Services;

use DB;
use Illuminate\Database\Eloquent\Model;
use Symfony\Component\Yaml\Yaml;
use Schema;

class AppService
{

  public function createModels($source, $withController = true)
  {
    $yaml = Yaml::parseFile($source);

    foreach($yaml as $data) {
      $this->createModel($data['model'], $data['table']);
      $this->createController($data['model']);
    }
  }

  public function createTables($source)
  {
    $yaml = Yaml::parseFile($source);

    foreach($yaml as $data) {
      $tableName = $data['table'];

      $columns = Schema::getColumnListing($tableName);
      $dropColumns = array_diff($columns, array_keys($data['dbFields']),['id']);

      Schema::table($tableName, function ($table) use($dropColumns) {
        foreach($dropColumns as $column) {
          $table->dropColumn($column);
        }
      });

      if (!Schema::hasTable($tableName)) {
        Schema::create($data['table'], function($table)
        {
          $table->increments('id');
        });
      }

      $migrateColumns = '';
      foreach($data['dbFields'] as $field => $prop) {
        Schema::table($tableName, function ($table) use($tableName, $field, $prop, &$migrateColumns) {
            $setType = $prop['type'] ?? $prop;
            $setNullable = $prop['nullable'] ?? false;
            $setLength = $prop['length'] ?? null;

            $tab = "\t\t\t\t\t\t";
            $migrateColumns .= "\$table->{$setType}('{$field}');\n{$tab}";
            if (!Schema::hasColumn($tableName, $field)) {

              $dbCol = $table->$setType($field, $setLength)->nullable($setNullable);
              return;
            }

            $col = $this->getColumn($tableName, $field);

            $type = $col->getType()->getName();
            $nullable = !$col->getNotNull();
            $length = $col->getLength();

            if($type !== $setType || $length != $setLength) {
              $table->$setType($field, $setLength)->change();
            }
            if($nullable != $setNullable) {
              $table->$setType($field)->nullable($setNullable)->change();
            }
        });
      }

      $this->createMigration($tableName, $migrateColumns);
    }
  }

  public function getColumn($table, $column)
  {
    return DB::connection()->getDoctrineColumn($table, $column);
  }

  public function createController($modelClass)
  {
    $modelName = substr(strrchr($modelClass, '\\'), 1);
    $controllerName = $modelName.'Controller';

    $dir = base_path('app/Http/Controllers');
    $output = $dir.'/'.$controllerName.'.php';

    $this->createFromTemplate('RestController.tpl', [
      'class' => $controllerName,
      'namespace' => 'App\\Http\\Controllers',
      'model' => $modelClass
    ], $output);
  }

  public function createModel($class, $table)
  {
    list($namespace, $className) = str_split($class, strrpos($class, '\\') + 1);
    $namespace = rtrim($namespace, '\\');

    $dir = base_path('app');
    $output = $dir.'/'.$className.'.php';

    $this->createFromTemplate('Model.tpl', [
      'class' => $className,
      'namespace' => $namespace,
      'table' => $table
    ], $output);
  }

  private function createMigration($table, $columns)
  {
      $output = base_path('database/migrations/abc.php');

      $this->createFromTemplate('Migration.tpl', [
        'columns' => $columns,
        'table' => $table
      ], $output);
  }

  private function createFromTemplate($template, $args, $output)
  {
    $template = $this->parseTemplate($template, $args);

    $file = fopen($output, "w") or die("Unable to open file!");
    fwrite($file, $template);
    fclose($file);
  }

  public function parseTemplate( $name, $args)
  {
    $templateDir = realpath(__DIR__.'/../templates');
    $template = $templateDir.'/' . $name;
    extract( $args );

    ob_start();
    include $template;

    return ob_get_clean();
  }

  /*public function getAllTables($connection = null)
  {
    return collect(\DB::connection($connection)->select('show tables'))->map(function ($val) {
      foreach ($val as $key => $tbl) {
        return $tbl;
      }
    });
  }

  protected function camelize($input, $separator = '_')
  {
    return str_replace($separator, '', ucwords($input, $separator));
  }*/

}
