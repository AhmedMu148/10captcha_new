<?php

namespace App\Http\Controllers\Api\V1;

use App\Actions\Cms\UpsertSection;
use App\Http\Controllers\Controller;
use App\Http\Requests\Cms\CmsSectionRequest;
use App\Http\Resources\Cms\SectionResource;
use App\Models\Section;

class CmsSectionController extends Controller
{
    protected UpsertSection $upsertSection;

    public function __construct(UpsertSection $upsertSection)
    {
        $this->upsertSection = $upsertSection;
    }

    /**
     * Get paginated CMS sections.
     */
    public function index()
    {
        $sections = Section::latest()->paginate(25);
        return SectionResource::collection($sections);
    }

    /**
     * Get a single section.
     */
    public function show(Section $section)
    {
        return new SectionResource($section);
    }

    /**
     * Create a new section.
     */
    public function store(CmsSectionRequest $request)
    {
        $data = $request->validated();
        $data['source'] = 'api';

        $section = $this->upsertSection->execute($data);

        return (new SectionResource($section))
            ->response()
            ->setStatusCode(201);
    }

    /**
     * Update an existing section.
     */
    public function update(CmsSectionRequest $request, Section $section)
    {
        $data = $request->validated();
        $data['source'] = 'api';

        $updated = $this->upsertSection->execute($data, $section);

        return new SectionResource($updated);
    }
}
