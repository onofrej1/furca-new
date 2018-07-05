<?php

namespace App\Http\Controllers;

use Onofrej\ApiGenerator\Http\Controllers\ResourceController;

class UserController extends ResourceController
{
    public $model = 'App\User';

    public function index()
    {
      if(count(request()->all()) > 0) {
          return parent::index();
      }

      return $this->model::with('roles')->get();
    }
}
