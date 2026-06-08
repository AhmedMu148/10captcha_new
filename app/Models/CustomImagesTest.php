<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomImagesTest extends Model
{
    protected $table = 'custom_images_tests';

    protected $fillable = [
        'uid',
        'base64',
        'module',
        'result',
        'result_ocr',
        'hash',
        'loop',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'uid' => 'integer',
            'loop' => 'integer',
        ];
    }
}
