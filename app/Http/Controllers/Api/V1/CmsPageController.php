<?php

namespace App\Http\Controllers\Api\V1;

use App\Actions\Cms\UpsertPage;
use App\Http\Controllers\Controller;
use App\Http\Requests\Cms\CmsPageRequest;
use App\Http\Resources\Cms\PageResource;
use App\Models\Page;

class CmsPageController extends Controller
{
    protected UpsertPage $upsertPage;

    public function __construct(UpsertPage $upsertPage)
    {
        $this->upsertPage = $upsertPage;
    }

    /**
     * Get paginated CMS pages.
     */
    public function index()
    {
        $pages = Page::latest()->paginate(25);
        return PageResource::collection($pages);
    }

    /**
     * Get single CMS page with loaded sections.
     */
    public function show(Page $page)
    {
        $page->load('pageSections.section');
        return new PageResource($page);
    }

    /**
     * Create a new page.
     */
    public function store(CmsPageRequest $request)
    {
        $data = $request->validated();
        $data['source'] = 'api';

        $page = $this->upsertPage->execute($data);
        $page->load('pageSections.section');

        return (new PageResource($page))
            ->response()
            ->setStatusCode(201);
    }

    /**
     * Update an existing page.
     */
    public function update(CmsPageRequest $request, Page $page)
    {
        $data = $request->validated();
        $data['source'] = 'api';

        $updated = $this->upsertPage->execute($data, $page);
        $updated->load('pageSections.section');

        return new PageResource($updated);
    }
}
