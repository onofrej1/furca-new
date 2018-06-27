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

      foreach($data['dbFields'] as $field => $prop) {
        Schema::table($tableName, function ($table) use($tableName, $field, $prop) {
            $existingType = $this->getColumn($tableName, $field);
            $type = $prop['type'] ?? $prop;

            $nullable = $prop['nullable'] ?? false;
            $isNullable = !$existingType->getNotNull();

            if (!Schema::hasColumn($tableName, $field)) {
              $table->$type($field);
            }
            if($existingType->getType()->getName() !== $type) {
              $table->$type($field)->change();
            }
            if($isNullable != $nullable) {
              $table->$type($field)->nullable($nullable)->change();
            }
        });
      }
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

    $dir = base_path('app/Http/Controllers/Rest');
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

    $dir = base_path('app/Models');
    $output = $dir.'/'.$className.'.php';

    $this->createFromTemplate('Model.tpl', [
      'class' => $className,
      'namespace' => $namespace,
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

  public function parseTemplate( $names, $args ){
    $templateDir = base_path('resources/templates');
    if ( !is_array( $names ) ) {
      $names = array( $names );
    }

    $template_found = false;
    foreach ( $names as $name ) {
      $file = $templateDir.'/' . $name;
      if ( file_exists( $file ) ) {
        $template_found = $file;
        break;
      }
    }

    if ( ! $template_found ) {
      return '';
    }

    if ( is_array( $args ) ){
      extract( $args );
    }

    ob_start();
    include $template_found;
    return ob_get_clean();
  }

  public function getAllTables($connection = null)
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
  }

}
