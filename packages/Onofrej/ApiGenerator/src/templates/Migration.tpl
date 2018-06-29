<?php
$tableName = ucfirst($table);
$file = <<<EOT

<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Create{$tableName}Table extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('{$table}', function (Blueprint \$table) {
            \$table->increments('id');\n
EOT;

foreach($columns as $column) {
$file.= <<<EOT
            $column
EOT;
}

$file .= <<<EOT
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('{$table}');
    }
}

EOT;

echo $file;
