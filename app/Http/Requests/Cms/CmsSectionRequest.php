<?php

namespace App\Http\Requests\Cms;

use App\Models\Section;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CmsSectionRequest extends FormRequest
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
        $section = $this->route('section');
        $sectionId = null;

        if ($section instanceof Section) {
            $sectionId = $section->id;
        } elseif (is_numeric($section)) {
            $sectionId = (int)$section;
        } elseif (is_string($section)) {
            $resolved = Section::where('id', $section)->orWhere('key', $section)->first();
            $sectionId = $resolved ? $resolved->id : null;
        }

        return [
            'name' => 'required|string|max:255',
            'type' => [
                'required',
                'string',
                Rule::in(array_keys(Section::TYPES)),
            ],
            'key' => [
                'nullable',
                'string',
                'alpha_dash',
                'max:255',
                Rule::unique('sections', 'key')->ignore($sectionId),
            ],
            'status' => 'nullable|string|in:draft,published',
            'data' => 'nullable|array',
            'html_content' => 'nullable|string',
            'wrapper_class' => 'nullable|string|max:255',
            'anchor_id' => 'nullable|string|max:255',
        ];
    }
}
