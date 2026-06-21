<?php

namespace App\Http\Controllers;

use App\Models\Page;
use App\Services\Cms\SectionRenderer;
use Illuminate\Http\Request;

class CmsPageController extends Controller
{
    protected SectionRenderer $renderer;

    public function __construct(SectionRenderer $renderer)
    {
        $this->renderer = $renderer;
    }

    /**
     * Show page by slug under /pages/{slug}.
     */
    public function show(string $slug)
    {
        $page = Page::publishedBySlug($slug)
            ->withPublicSections()
            ->firstOrFail();

        $renderedSections = $this->renderer->renderSections($page->pageSections);

        return view('cms.show', [
            'page' => $page,
            'renderedSections' => $renderedSections,
        ]);
    }

    /**
     * Show page using fallback root-level slugs.
     */
    public function showAtRoot(Request $request)
    {
        $slug = $request->path();

        $page = Page::publishedBySlug($slug)
            ->withPublicSections()
            ->first();

        if (!$page) {
            abort(404);
        }

        $renderedSections = $this->renderer->renderSections($page->pageSections);

        return view('cms.show', [
            'page' => $page,
            'renderedSections' => $renderedSections,
        ]);
    }
}
