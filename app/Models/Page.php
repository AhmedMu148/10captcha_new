<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Page extends Model
{
    use HasFactory;

    protected $fillable = [
        'slug',
        'title',
        'status',
        'visibility',
        'seo_title',
        'seo_description',
        'seo_keywords',
        'canonical_url',
        'source',
        'published_at',
    ];

    protected $casts = [
        'published_at' => 'datetime',
    ];

    const RESERVED_SLUGS = [
        'admin',
        'api',
        'login',
        'register',
        'logout',
        'pages',
        'livewire',
        'up',
        'dashboard',
        'faq',
        'api-docs',
        'tos',
        'privacy-policy',
        'profile',
        'topup',
        'payments',
        'reports',
        'wallet',
        'custom-images',
        'tickets',
        'partnership',
        'partnership-option',
        'option',
        'partnership-register-relation',
        'partnership-withdraw',
        'withdraw'
    ];

    protected static function boot()
    {
        parent::boot();

        static::saving(function (Page $page) {
            if ($page->status === 'published') {
                if (is_null($page->published_at)) {
                    $page->published_at = now();
                }
            } elseif ($page->status === 'draft') {
                $page->published_at = null;
            }
        });
    }

    public function pageSections(): HasMany
    {
        return $this->hasMany(PageSection::class, 'page_id');
    }

    public function sections(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Section::class, 'page_sections')
            ->withPivot(['id', 'order', 'is_visible', 'overrides'])
            ->withTimestamps();
    }

    public function scopePublishedBySlug($query, string $slug)
    {
        return $query->where('slug', $slug)->where('status', 'published');
    }

    public function scopeWithPublicSections($query)
    {
        return $query->with(['pageSections' => function ($q) {
            $q->where('is_visible', true)
              ->whereHas('section', function ($sq) {
                  $sq->where('status', 'published');
              })
              ->orderBy('order', 'asc');
        }, 'pageSections.section']);
    }
}
