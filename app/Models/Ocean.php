<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Ocean extends Model
{
    protected $fillable = ['name', 'nickname', 'transparency', 'url', 'key'];
}
