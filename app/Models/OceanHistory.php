<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OceanHistory extends Model
{
    protected $fillable = ['ocean_id', 'transparency', 'raw_html'];
}
