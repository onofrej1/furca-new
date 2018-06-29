<?php

namespace App\Http\Controllers;

use Onofrej\ApiGenerator\Http\Controllers\ResourceController;

class ArticleController extends ResourceController
{
    public $model = 'App\Article';
}