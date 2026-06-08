<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FaqCategory extends Model
{
    protected $table = 'faq_categories';

    public $timestamps = false;

    protected $fillable = [
        'name',
        'status',
    ];

    public function faqs()
    {
        return $this->hasMany(Faq::class, 'cat_id');
    }
}
