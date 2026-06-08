<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FaqPages extends Model
{
    protected $fillable = ['name', 'status'];

    public function faqs()
    {
        return $this->hasMany(Faq::class, 'show');
    }
}
