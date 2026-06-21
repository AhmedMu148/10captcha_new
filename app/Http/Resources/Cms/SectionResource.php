<?php

namespace App\Http\Resources\Cms;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SectionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'key' => $this->key,
            'name' => $this->name,
            'type' => $this->type,
            'status' => $this->status,
            'content' => $this->data ?? new \stdClass(),
            'html_content' => $this->html_content,
            'wrapper_class' => $this->wrapper_class,
            'anchor_id' => $this->anchor_id,
            'source' => $this->source,
            'created_at' => $this->created_at?->toIso8601String(),
            'updated_at' => $this->updated_at?->toIso8601String(),
        ];
    }
}
