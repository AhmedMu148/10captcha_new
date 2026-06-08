<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomImage extends Model
{
    use HasFactory;

    protected $table = 'custom_images';

    protected $fillable = [
        'code',
        'name',
        'description',
        'type',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'type' => 'integer',
            'status' => 'string',
        ];
    }

    /**
     * Scope to get only active custom images
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'Active');
    }

    /**
     * Scope to get only inactive custom images
     */
    public function scopeInactive($query)
    {
        return $query->where('status', 'Inactive');
    }

    /**
     * Check if custom image is active
     */
    public function isActive(): bool
    {
        return $this->status === 'Active';
    }

    /**
     * Check if custom image is inactive
     */
    public function isInactive(): bool
    {
        return $this->status === 'Inactive';
    }
}
