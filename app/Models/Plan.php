<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Plan extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'name',
        'ocr_cap_id',
        'price',
        'img',
        'success',
        'speed',
        'sort',
        'status',

    ];
}
