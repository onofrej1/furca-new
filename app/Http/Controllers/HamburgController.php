<?php

namespace App\Http\Controllers;

use Onofrej\ApiGenerator\Http\Controllers\ResourceController;

class HamburgController extends ResourceController
{
    public $model = 'App\Hamburg';

    public function getResults($event)
    {
        $file = fopen("storage/vysledky-hamburg/".$event.".csv", "r");
        $results = [];

        while ( ($data = fgetcsv($file, 1000, ",")) !==FALSE )
        {
             array_push($results, $data);
        }
        fclose($file);

        return response()->json($results);
    }
}
