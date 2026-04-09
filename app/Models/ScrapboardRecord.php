<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ScrapboardRecord extends Model
{
    protected $fillable = [
        'code',
        'classification',
    ];
}
