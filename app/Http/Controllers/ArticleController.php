<?php

namespace App\Http\Controllers;

use Onofrej\ApiGenerator\Http\Controllers\ResourceController;

class ArticleController extends ResourceController
{
    public $model = 'App\Article';

    public function index()
    {
      if(count(request()->all()) > 0) {
          return parent::index();
      }

      return $this->model::with('tags')->get();
    }
}
