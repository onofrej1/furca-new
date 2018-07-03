<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MenuItem extends Model
{
  protected $table = 'menu_item';
  public $timestamps = false;

  public function menu()
  {
      return $this->belongsTo('App\Menu');
  }

  public function page()
  {
      return $this->belongsTo('App\Page');
  }

  public function parent()
  {
      return $this->belongsTo('App\MenuItem');
  }
}
