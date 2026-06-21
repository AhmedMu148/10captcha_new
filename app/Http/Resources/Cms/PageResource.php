<?php

namespace App\Http\Resources\Cms;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PageResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'slug' => $this->slug,
            'title' => $this->title,
            'status' => $this->status,
            'visibility' => $this->visibility,
            'seo' => [
                'title' => $this->seo_title,
                'description' => $this->seo_description,
                'keywords' => $this->seo_keywords,
                'canonical_url' => $this->canonical_url,
            ],
            'source' => $this->source,
            'published_at' => $this->published_at?->toIso8601String(),
            'sections' => $this->whenLoaded('pageSections', function () {
                return $this->pageSections->map(function ($pageSection) {
                    return [
                        'section_id' => $pageSection->section_id,
                        'order' => $pageSection->order,
                        'is_visible' => (bool)$pageSection->is_visible,
                        'overrides' => $pageSection->overrides ?? new \stdClass(),
                        'section' => $pageSection->relationLoaded('section') && $pageSection->section 
                            ? new SectionResource($pageSection->section) 
                            : null,
                    ];
                });
            }),
            'created_at' => $this->created_at?->toIso8601String(),
            'updated_at' => $this->updated_at?->toIso8601String(),
        ];
    }
}
