<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PageSection extends Model
{
    use HasFactory;

    protected $table = 'page_sections';

    protected $fillable = [
        'page_id',
        'section_id',
        'order',
        'is_visible',
        'overrides',
    ];

    protected $casts = [
        'is_visible' => 'boolean',
        'overrides' => 'array',
    ];

    public function page(): BelongsTo
    {
        return $this->belongsTo(Page::class, 'page_id');
    }

    public function section(): BelongsTo
    {
        return $this->belongsTo(Section::class, 'section_id');
    }

    public function resolvedData(): array
    {
        $sectionData = $this->section->data ?? [];
        $overridesData = $this->overrides ?? [];

        return array_merge($sectionData, $overridesData);
    }
}
