<?php
$file = <<<EOT
<?php

namespace {$namespace};

class {$class} extends ResourceController
{
    public \$model = '{$model}';
}
EOT;

echo $file;
