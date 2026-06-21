<?php

namespace App\Services\Cms;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Log;

class HtmlContentProcessor
{
    private const SHORTCODES = [
        '[[cms.hero]]' => 'components.cms.sections.hero',
        '[[cms.rich-text]]' => 'components.cms.sections.rich_text',
        '[[cms.stats]]' => 'components.cms.sections.stats',
        '[[cms.cta]]' => 'components.cms.sections.cta',
    ];

    /**
     * Process custom HTML content by compiling Blade directives and expanding shortcodes.
     */
    public function process(string $htmlContent, array $scope = []): string
    {
        // 1. Compile inline Blade directives
        try {
            $compiled = Blade::render($htmlContent, $scope);
        } catch (\Throwable $e) {
            Log::warning("CMS Blade rendering failed: " . $e->getMessage(), [
                'exception' => $e,
                'content_preview' => substr($htmlContent, 0, 100),
            ]);
            $compiled = $htmlContent;
        }

        // 2. Expand whitelisted shortcodes
        foreach (self::SHORTCODES as $shortcode => $viewName) {
            if (str_contains($compiled, $shortcode)) {
                try {
                    $renderedShortcode = view($viewName, ['data' => $scope])->render();
                    $compiled = str_replace($shortcode, $renderedShortcode, $compiled);
                } catch (\Throwable $e) {
                    Log::warning("CMS Shortcode expansion failed for {$shortcode}: " . $e->getMessage(), [
                        'exception' => $e,
                    ]);
                }
            }
        }

        return $compiled;
    }
}
