<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
  protected $table = 'article';
  public $timestamps = false;

  public function tags()
  {
     return $this->belongsToMany(\App\Tag::class, 'article_tag', 'article_id', 'tag_id');
  }
}
