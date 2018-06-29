<?php
$file = <<<EOT
<?php

namespace {$namespace};

use Onofrej\ApiGenerator\Http\Controllers\ResourceController;

class {$class} extends ResourceController
{
    public \$model = '{$model}';
}
EOT;

echo $file;
