<?php

namespace App\Actions\Cms;

use App\Models\Section;

class UpsertSection
{
    /**
     * Upsert a reusable CMS section.
     */
    public function execute(array $data, ?Section $section = null): Section
    {
        if (!$section) {
            $section = new Section();
        }

        $section->fill([
            'key' => $data['key'] ?? null,
            'name' => $data['name'],
            'type' => $data['type'],
            'status' => $data['status'] ?? 'published',
            'data' => $data['data'] ?? null,
            'html_content' => $data['html_content'] ?? null,
            'wrapper_class' => $data['wrapper_class'] ?? null,
            'anchor_id' => $data['anchor_id'] ?? null,
            'source' => $data['source'] ?? 'admin',
        ]);

        $section->save();

        return $section;
    }
}
