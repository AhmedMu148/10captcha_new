<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FaqLanguage extends Model
{
    protected $table = 'faq_languages';
    protected $fillable = ['name', 'code', 'status'];

    public function faqs()
    {
        return $this->hasMany(Faq::class, 'lang_id');
    }
}
