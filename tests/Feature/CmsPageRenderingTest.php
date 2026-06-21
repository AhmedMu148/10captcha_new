<?php

namespace Tests\Feature;

use App\Models\Page;
use App\Models\Section;
use App\Models\PageSection;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CmsPageRenderingTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_renders_only_published_and_visible_sections_in_order()
    {
        $page = Page::create([
            'title' => 'Test Page',
            'slug' => 'test-page',
            'status' => 'published',
            'visibility' => 'public',
        ]);

        $publishedVisibleSec = Section::create([
            'name' => 'Hero Section',
            'type' => 'hero',
            'status' => 'published',
            'data' => ['heading' => 'Hello Visible World'],
        ]);

        $draftSec = Section::create([
            'name' => 'Draft Section',
            'type' => 'hero',
            'status' => 'draft',
            'data' => ['heading' => 'Hello Draft World'],
        ]);

        $hiddenSec = Section::create([
            'name' => 'Hidden Section',
            'type' => 'hero',
            'status' => 'published',
            'data' => ['heading' => 'Hello Hidden World'],
        ]);

        PageSection::create([
            'page_id' => $page->id,
            'section_id' => $publishedVisibleSec->id,
            'order' => 10,
            'is_visible' => true,
        ]);

        PageSection::create([
            'page_id' => $page->id,
            'section_id' => $draftSec->id,
            'order' => 5,
            'is_visible' => true,
        ]);

        PageSection::create([
            'page_id' => $page->id,
            'section_id' => $hiddenSec->id,
            'order' => 1,
            'is_visible' => false,
        ]);

        $response = $this->get('/pages/test-page');

        $response->assertStatus(200);
        $response->assertSee('Hello Visible World');
        $response->assertDontSee('Hello Draft World');
        $response->assertDontSee('Hello Hidden World');
    }

    /** @test */
    public function it_applies_per_page_field_overrides()
    {
        $page = Page::create([
            'title' => 'Override Page',
            'slug' => 'override-page',
            'status' => 'published',
        ]);

        $section = Section::create([
            'name' => 'Original Hero',
            'type' => 'hero',
            'status' => 'published',
            'data' => ['heading' => 'Original Heading', 'subheading' => 'Original Sub'],
        ]);

        PageSection::create([
            'page_id' => $page->id,
            'section_id' => $section->id,
            'order' => 0,
            'is_visible' => true,
            'overrides' => ['heading' => 'Overridden Heading'],
        ]);

        $response = $this->get('/pages/override-page');

        $response->assertStatus(200);
        $response->assertSee('Overridden Heading');
        $response->assertSee('Original Sub');
        $response->assertDontSee('Original Heading');
    }

    /** @test */
    public function it_auto_wraps_custom_sections_without_section_tag()
    {
        $page = Page::create([
            'title' => 'Custom Page',
            'slug' => 'custom-page',
            'status' => 'published',
        ]);

        $section = Section::create([
            'name' => 'Custom Block',
            'type' => 'custom',
            'status' => 'published',
            'html_content' => '<div class="raw-content">No section tag here</div>',
            'wrapper_class' => 'my-custom-wrapper-class',
            'anchor_id' => 'custom-anchor',
        ]);

        PageSection::create([
            'page_id' => $page->id,
            'section_id' => $section->id,
            'order' => 1,
            'is_visible' => true,
        ]);

        $response = $this->get('/pages/custom-page');

        $response->assertStatus(200);
        $response->assertSee('<section class="my-custom-wrapper-class" id="custom-anchor">', false);
        $response->assertSee('<div class="raw-content">No section tag here</div>', false);
    }

    /** @test */
    public function it_refuses_reserved_slugs()
    {
        $this->expectException(\Illuminate\Validation\ValidationException::class);

        $request = new \App\Http\Requests\Cms\CmsPageRequest();
        
        $validator = validator([
            'title' => 'Test reserved',
            'slug' => 'admin',
        ], $request->rules());

        $validator->validate();
    }
}
