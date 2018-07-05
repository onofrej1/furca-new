<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
  protected $table = 'role';
  public $timestamps = false;

  public function permissions()
  {
      return $this->belongsToMany('App\Permission', 'role_permission', 'role_id', 'permission_id');
  }
}
