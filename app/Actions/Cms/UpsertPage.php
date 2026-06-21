<?php

namespace App\Actions\Cms;

use App\Models\Page;
use Illuminate\Support\Facades\DB;

class UpsertPage
{
    /**
     * Upsert a CMS page and its ordering layout pivot row mappings in a transaction.
     */
    public function execute(array $data, ?Page $page = null): Page
    {
        return DB::transaction(function () use ($data, $page) {
            if (!$page) {
                $page = new Page();
            }

            $page->fill([
                'title' => $data['title'],
                'slug' => $data['slug'],
                'status' => $data['status'] ?? 'draft',
                'visibility' => $data['visibility'] ?? 'public',
                'seo_title' => $data['seo_title'] ?? null,
                'seo_keywords' => $data['seo_keywords'] ?? null,
                'seo_description' => $data['seo_description'] ?? null,
                'canonical_url' => $data['canonical_url'] ?? null,
                'source' => $data['source'] ?? 'admin',
            ]);

            $page->save();

            // Sync layout pivots if explicit array is supplied
            if (array_key_exists('sections', $data) && is_array($data['sections'])) {
                $page->pageSections()->delete();

                foreach ($data['sections'] as $sec) {
                    $page->pageSections()->create([
                        'section_id' => $sec['section_id'],
                        'order' => $sec['order'] ?? 0,
                        'is_visible' => $sec['is_visible'] ?? true,
                        'overrides' => $sec['overrides'] ?? null,
                    ]);
                }
            }

            return $page;
        });
    }
}
