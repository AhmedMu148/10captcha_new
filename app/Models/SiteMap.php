<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class SiteMap
 *
 * @property int $id
 * @property string $url
 * @property int $status
 */
class SiteMap extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $table = 'site_map';

    protected $fillable = [
        'url',
        'status',
    ];

    protected $casts = [
        'id' => 'integer',
        'status' => 'integer',
    ];
}
