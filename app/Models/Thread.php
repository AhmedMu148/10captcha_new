<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Thread extends Model
{
    protected $table = 'threads';

    public $timestamps = false;

    protected $fillable = [
        'bigger_than',
        'threads',
        'status',
    ];
}
