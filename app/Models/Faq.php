<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class Faq extends Model
{
    protected $table = 'faqs';

    public $timestamps = false;

    protected $fillable = [
        'lang_id',
        'cat_id',
        'question',
        'answer',
        'show',
        'status',
    ];

    public function lang()
    {
        return $this->belongsTo(FaqLanguage::class, 'lang_id');
    }

    public function category()
    {
        return $this->belongsTo(FaqCategory::class, 'cat_id');
    }

    public function page()
    {
        return $this->belongsTo(FaqPages::class, 'show');
    }
}
