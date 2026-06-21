<?php

namespace App\Http\Requests\Cms;

use App\Models\Page;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CmsPageRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        $page = $this->route('page');
        $pageId = null;

        if ($page instanceof Page) {
            $pageId = $page->id;
        } elseif (is_string($page)) {
            $resolved = Page::where('slug', $page)->first();
            $pageId = $resolved ? $resolved->id : null;
        }

        return [
            'title' => 'required|string|max:255',
            'slug' => [
                'required',
                'string',
                'alpha_dash',
                'max:255',
                Rule::notIn(Page::RESERVED_SLUGS),
                Rule::unique('pages', 'slug')->ignore($pageId),
            ],
            'status' => 'nullable|string|in:draft,published',
            'visibility' => 'nullable|string|in:public,private',
            'seo_title' => 'nullable|string|max:255',
            'seo_keywords' => 'nullable|string|max:255',
            'seo_description' => 'nullable|string|max:500',
            'canonical_url' => 'nullable|url|max:255',
            'sections' => 'nullable|array',
            'sections.*.section_id' => 'required|exists:sections,id',
            'sections.*.order' => 'required|integer|min:0',
            'sections.*.is_visible' => 'nullable|boolean',
            'sections.*.overrides' => 'nullable|array',
        ];
    }
}
