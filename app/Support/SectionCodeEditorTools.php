<?php

namespace App\Support;

class SectionCodeEditorTools
{
    /**
     * Prebuilt HTML structures configured for the host design system.
     */
    public static function recipes(): array
    {
        return [
            'section-shell' => [
                'name' => 'Section Shell',
                'html' => '<section class="py-12 bg-white">' . "\n" .
                          '    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">' . "\n" .
                          '        <!-- Content Here -->' . "\n" .
                          '    </div>' . "\n" .
                          '</section>'
            ],
            'split-feature' => [
                'name' => 'Split Feature',
                'html' => '<section class="py-12 bg-white">' . "\n" .
                          '    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col md:flex-row items-center gap-8">' . "\n" .
                          '        <div class="md:w-1/2">' . "\n" .
                          '            <h2 class="text-3xl font-bold text-gray-900 mb-4">Feature Title</h2>' . "\n" .
                          '            <p class="text-gray-600 mb-6">Describe the feature details here using the Figtree font layout.</p>' . "\n" .
                          '            <a href="#" class="inline-block px-6 py-3 bg-green-600 text-white font-bold rounded-lg hover:bg-green-700 transition">Get Started</a>' . "\n" .
                          '        </div>' . "\n" .
                          '        <div class="md:w-1/2">' . "\n" .
                          '            <img src="/assets/img/var.png" class="w-full object-contain max-h-[300px]" alt="" loading="lazy">' . "\n" .
                          '        </div>' . "\n" .
                          '    </div>' . "\n" .
                          '</section>'
            ],
            'card-grid' => [
                'name' => 'Card Grid',
                'html' => '<section class="py-12 bg-gray-50">' . "\n" .
                          '    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">' . "\n" .
                          '        <h2 class="text-3xl font-bold text-center text-gray-900 mb-8">Grid Title</h2>' . "\n" .
                          '        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">' . "\n" .
                          '            <!-- Card 1 -->' . "\n" .
                          '            <div class="bg-white shadow rounded-xl p-6 border border-gray-100">' . "\n" .
                          '                <i class="las la-clock text-4xl text-green-600 mb-4"></i>' . "\n" .
                          '                <h3 class="font-bold text-gray-950 text-lg mb-2">Card 1</h3>' . "\n" .
                          '                <p class="text-gray-500 text-sm">Description goes here.</p>' . "\n" .
                          '            </div>' . "\n" .
                          '            <!-- Card 2 -->' . "\n" .
                          '            <div class="bg-white shadow rounded-xl p-6 border border-gray-100">' . "\n" .
                          '                <i class="las la-hand-holding-usd text-4xl text-green-600 mb-4"></i>' . "\n" .
                          '                <h3 class="font-bold text-gray-950 text-lg mb-2">Card 2</h3>' . "\n" .
                          '                <p class="text-gray-500 text-sm">Description goes here.</p>' . "\n" .
                          '            </div>' . "\n" .
                          '            <!-- Card 3 -->' . "\n" .
                          '            <div class="bg-white shadow rounded-xl p-6 border border-gray-100">' . "\n" .
                          '                <i class="las la-percent text-4xl text-green-600 mb-4"></i>' . "\n" .
                          '                <h3 class="font-bold text-gray-950 text-lg mb-2">Card 3</h3>' . "\n" .
                          '                <p class="text-gray-500 text-sm">Description goes here.</p>' . "\n" .
                          '            </div>' . "\n" .
                          '        </div>' . "\n" .
                          '    </div>' . "\n" .
                          '</section>'
            ],
            'stat-strip' => [
                'name' => 'Stat Strip',
                'html' => '<section class="bg-gray-900 text-white py-12">' . "\n" .
                          '    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 grid grid-cols-2 md:grid-cols-4 gap-6 text-center">' . "\n" .
                          '        <div>' . "\n" .
                          '            <div class="text-3xl font-extrabold text-green-500">100k+</div>' . "\n" .
                          '            <div class="mt-2 text-gray-400 text-sm">Active Users</div>' . "\n" .
                          '        </div>' . "\n" .
                          '        <div>' . "\n" .
                          '            <div class="text-3xl font-extrabold text-green-500">99.9%</div>' . "\n" .
                          '            <div class="mt-2 text-gray-400 text-sm">Uptime</div>' . "\n" .
                          '        </div>' . "\n" .
                          '        <div>' . "\n" .
                          '            <div class="text-3xl font-extrabold text-green-500">3x</div>' . "\n" .
                          '            <div class="mt-2 text-gray-400 text-sm">Cheaper rates</div>' . "\n" .
                          '        </div>' . "\n" .
                          '        <div>' . "\n" .
                          '            <div class="text-3xl font-extrabold text-green-500">24/7</div>' . "\n" .
                          '            <div class="mt-2 text-gray-400 text-sm">Support</div>' . "\n" .
                          '        </div>' . "\n" .
                          '    </div>' . "\n" .
                          '</section>'
            ],
            'cta-row' => [
                'name' => 'CTA Row',
                'html' => '<section class="bg-green-600 py-12">' . "\n" .
                          '    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col md:flex-row justify-between items-center gap-6 text-white">' . "\n" .
                          '        <div>' . "\n" .
                          '            <h3 class="text-2xl font-bold">Ready to solve captchas?</h3>' . "\n" .
                          '            <p class="text-white/80 text-sm mt-1">Register now and enjoy fast, cheap recognition.</p>' . "\n" .
                          '        </div>' . "\n" .
                          '        <a href="#" class="px-6 py-3 bg-gray-900 text-white font-bold rounded-lg hover:bg-gray-800 transition">Get Started</a>' . "\n" .
                          '    </div>' . "\n" .
                          '</section>'
            ],
        ];
    }

    /**
     * Whitelisted class presets mapping class strings to names.
     */
    public static function classTokenOptions(): array
    {
        return [
            'max-w-7xl mx-auto px-4 sm:px-6 lg:px-8' => 'Content Container Width',
            'grid grid-cols-1 md:grid-cols-3 gap-6' => '3-Column Responsive Grid',
            'flex flex-col md:flex-row justify-between items-center' => 'Spaced Flex Row',
            'bg-white shadow rounded-xl p-6 border border-gray-100' => 'Bordered Card',
            'inline-block px-6 py-3 bg-green-600 text-white font-bold rounded-lg hover:bg-green-700 transition' => 'Primary Brand Button',
        ];
    }

    /**
     * Design tokens for typography and background mappings.
     */
    public static function designTokenOptions(): array
    {
        return [
            'text-gray-900 font-bold' => 'Theme Heading',
            'text-gray-500 text-sm' => 'Theme Muted / Body Text',
            'text-green-600 font-semibold' => 'Theme Accent Color',
            'bg-white' => 'Theme Main Background',
            'bg-gray-50' => 'Theme Soft Background',
            'bg-gray-900' => 'Theme Dark Background',
        ];
    }

    /**
     * Guidelines list for code authoring.
     */
    public static function designReferenceItems(): array
    {
        return [
            'Start from a pattern (recipe), then edit copy — don\'t build layouts from scratch.',
            'Only use approved section classes (keeps spacing/width consistent).',
            'Use theme tokens, never hard-coded hex colors, so dark modes stay compatible.',
            'Wrap custom block content in a <section> (code without one is automatically wrapped).',
            'Use [[shortcodes]] to reuse prebuilt blocks.',
        ];
    }

    /**
     * Return custom HTML image template snippet.
     */
    public static function insertUploadedImage(string $path): string
    {
        return '<img src="/storage/' . $path . '" alt="" class="w-full h-auto object-cover rounded-lg" loading="lazy">';
    }
}
