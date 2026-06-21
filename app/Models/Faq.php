<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class Faq extends Model
{
    protected $table = 'faqs';

    public $timestamps = false;

    protected $fillable = [
        'cat_id',
        'question',
        'answer',
        'status',
    ];

    public function category()
    {
        return $this->belongsTo(FaqCategory::class, 'cat_id');
    }
}
