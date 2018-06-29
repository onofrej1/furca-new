<?php

namespace App\Http\Controllers;

use Onofrej\ApiGenerator\Http\Controllers\ResourceController;

class UserController extends ResourceController
{
    public $model = 'App\User';
}