<?php

namespace App\Http\Controllers;

use Onofrej\ApiGenerator\Http\Controllers\ResourceController;

class NewsController extends ResourceController
{
    public $model = 'App\News';
}
