<?php

namespace Onofrej\ApiGenerator\Http\Controllers;

use Illuminate\Http\Request;
use Onofrej\ApiGenerator\Services\AppService;

class AppController extends Controller
{

  private $appService;

  public function __construct(AppService $appService)
  {
      //parent::__construct();

      $this->appService = $appService;
  }


  public function test()
  {
    $source = public_path('data.yaml');
    //$this->appService->createTables($source);
    dump('test');
  }

}
