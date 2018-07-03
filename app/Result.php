<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Result extends Model
{
  protected $table = 'result';
  public $timestamps = false;

  public function runner()
  {
      return $this->belongsTo('App\Runner');
  }

  public function event()
  {
      return $this->belongsTo('App\Event');
  }
}
