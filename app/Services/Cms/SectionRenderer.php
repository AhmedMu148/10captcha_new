<?php

namespace App\Services\Cms;

use App\Models\Section;
use App\Models\PageSection;
use Illuminate\Support\Facades\View;

class SectionRenderer
{
    protected HtmlContentProcessor $processor;

    public function __construct(HtmlContentProcessor $processor)
    {
        $this->processor = $processor;
    }

    /**
     * Render an iterable list of page sections into DTO structures.
     *
     * @param iterable|PageSection[] $pageSections
     * @param array $context
     * @return RenderedSection[]
     */
    public function renderSections(iterable $pageSections, array $context = []): array
    {
        $rendered = [];

        foreach ($pageSections as $pageSection) {
            $section = $pageSection->section;
            if (!$section || !$section->isPublished() || !array_key_exists($section->type, Section::TYPES)) {
                continue;
            }

            $scope = array_merge($context, $pageSection->resolvedData());
            $html = $this->renderSection($section, $scope);

            $rendered[] = new RenderedSection(
                $section->id,
                $section->name,
                $section->wrapper_class,
                $section->anchor_id,
                $html
            );
        }

        return $rendered;
    }

    /**
     * Render a single Section record.
     */
    public function renderSection(Section $section, array $scope = [], ?string $overrideHtml = null): string
    {
        if ($section->type === Section::TYPE_CUSTOM) {
            $html = $overrideHtml ?? $section->resolvedHtmlContent() ?? '';
            $processed = $this->processor->process($html, $scope);

            // Wrap in section shell if no <section> tag exists in output
            if (stripos($processed, '<section') === false) {
                return View::make('cms.partials.section-wrapper', [
                    'content' => $processed,
                    'wrapperClass' => $section->wrapper_class,
                    'anchorId' => $section->anchor_id,
                ])->render();
            }

            return $processed;
        }

        $viewPath = "components.cms.sections.{$section->type}";
        if (View::exists($viewPath)) {
            return View::make($viewPath, ['data' => $scope])->render();
        }

        return '';
    }
}
