<?php
$file = <<<EOT
<?php

namespace {$namespace};

use Illuminate\Database\Eloquent\Model;

class {$class} extends Model
{
  protected \$table = '{$table}';
  public \$timestamps = false;
}
EOT;

echo $file;
