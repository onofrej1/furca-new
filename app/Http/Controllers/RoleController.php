<?php

namespace App\Http\Controllers;

use Onofrej\ApiGenerator\Http\Controllers\ResourceController;

class RoleController extends ResourceController
{
    public $model = 'App\Role';

    public function index()
    {
      if(count(request()->all()) > 0) {
          return parent::index();
      }

      return $this->model::with('permissions')->get();
    }
}
