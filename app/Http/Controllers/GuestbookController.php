<?php

namespace App\Http\Controllers;

use Onofrej\ApiGenerator\Http\Controllers\ResourceController;

class GuestbookController extends ResourceController
{
    public $model = 'App\Guestbook';
}
