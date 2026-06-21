<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Section extends Model
{
    use HasFactory;

    protected $fillable = [
        'key',
        'name',
        'type',
        'status',
        'data',
        'html_content',
        'wrapper_class',
        'anchor_id',
        'source',
    ];

    protected $casts = [
        'data' => 'array',
    ];

    const TYPE_HERO = 'hero';
    const TYPE_RICH_TEXT = 'rich_text';
    const TYPE_STATS = 'stats';
    const TYPE_CTA = 'cta';
    const TYPE_FEATURES = 'features';
    const TYPE_TESTIMONIAL = 'testimonial';
    const TYPE_TEAM = 'team';
    const TYPE_FAQ = 'faq';
    const TYPE_GALLERY = 'gallery';
    const TYPE_PRICING = 'pricing';
    const TYPE_TWO_COLUMN = 'two_column';
    const TYPE_CUSTOM = 'custom';

    const TYPES = [
        self::TYPE_HERO => 'Hero',
        self::TYPE_RICH_TEXT => 'Rich Text',
        self::TYPE_STATS => 'Stats',
        self::TYPE_CTA => 'CTA',
        self::TYPE_FEATURES => 'Features',
        self::TYPE_TESTIMONIAL => 'Testimonial',
        self::TYPE_TEAM => 'Team',
        self::TYPE_FAQ => 'FAQ',
        self::TYPE_GALLERY => 'Gallery',
        self::TYPE_PRICING => 'Pricing',
        self::TYPE_TWO_COLUMN => 'Two Column',
        self::TYPE_CUSTOM => 'Custom (HTML/Blade)',
    ];

    public function pageSections(): HasMany
    {
        return $this->hasMany(PageSection::class, 'section_id');
    }

    public function pages(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Page::class, 'page_sections')
            ->withPivot(['id', 'order', 'is_visible', 'overrides'])
            ->withTimestamps();
    }

    public function isPublished(): bool
    {
        return $this->status === 'published';
    }

    public function resolvedHtmlContent(): ?string
    {
        if (is_null($this->html_content)) {
            return null;
        }

        // Single normalization point for asset URLs if needed
        return $this->html_content;
    }
}
