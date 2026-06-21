<?php

namespace Tests\Feature;

use App\Models\CmsApiToken;
use App\Models\Page;
use App\Models\Section;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CmsApiTest extends TestCase
{
    use RefreshDatabase;

    protected function createToken(array $abilities = ['cms.all'], bool $isActive = true, $expiresAt = null, $revokedAt = null)
    {
        $raw = 'cms_live_' . \Illuminate\Support\Str::random(48);
        CmsApiToken::create([
            'name' => 'Test Token',
            'token_hash' => hash('sha256', $raw),
            'token_prefix' => substr($raw, 0, 12),
            'abilities' => $abilities,
            'is_active' => $isActive,
            'expires_at' => $expiresAt,
            'revoked_at' => $revokedAt,
        ]);
        return $raw;
    }

    /** @test */
    public function it_returns_401_without_token()
    {
        $response = $this->getJson('/api/v1/cms/pages');
        $response->assertStatus(401);
    }

    /** @test */
    public function it_returns_403_with_insufficient_scope()
    {
        $token = $this->createToken(['cms.pages.read']);
        
        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->postJson('/api/v1/cms/pages', [
                'title' => 'API Page',
                'slug' => 'api-page',
            ]);

        $response->assertStatus(403);
    }

    /** @test */
    public function it_allows_access_with_correct_scope()
    {
        $token = $this->createToken(['cms.pages.read']);
        
        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->getJson('/api/v1/cms/pages');

        $response->assertStatus(200);
    }

    /** @test */
    public function it_supports_fallback_env_token_as_cms_all()
    {
        config(['services.cms.api_token' => 'env_super_token_abc123']);

        $response = $this->withHeader('Authorization', 'Bearer env_super_token_abc123')
            ->getJson('/api/v1/cms/pages');

        $response->assertStatus(200);
    }

    /** @test */
    public function it_stamps_last_used_information_on_successful_authentication()
    {
        $token = $this->createToken(['cms.pages.read']);
        
        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->getJson('/api/v1/cms/pages');

        $response->assertStatus(200);

        $dbToken = CmsApiToken::first();
        $this->assertNotNull($dbToken->last_used_at);
        $this->assertNotNull($dbToken->last_used_ip);
    }

    /** @test */
    public function it_rejects_revoked_and_expired_tokens()
    {
        $revokedToken = $this->createToken(['cms.all'], true, null, now());
        $response1 = $this->withHeader('Authorization', 'Bearer ' . $revokedToken)
            ->getJson('/api/v1/cms/pages');
        $response1->assertStatus(401);

        $expiredToken = $this->createToken(['cms.all'], true, now()->subDay());
        $response2 = $this->withHeader('Authorization', 'Bearer ' . $expiredToken)
            ->getJson('/api/v1/cms/pages');
        $response2->assertStatus(401);

        $inactiveToken = $this->createToken(['cms.all'], false);
        $response3 = $this->withHeader('Authorization', 'Bearer ' . $inactiveToken)
            ->getJson('/api/v1/cms/pages');
        $response3->assertStatus(401);
    }

    /** @test */
    public function it_executes_crud_happy_paths_and_matches_exact_page_resource_shape()
    {
        $token = $this->createToken(['cms.all']);
        
        $section = Section::create([
            'name' => 'API Section',
            'type' => 'hero',
            'data' => ['heading' => 'Initial Heading'],
        ]);

        // 1. Create (Store) Page
        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->postJson('/api/v1/cms/pages', [
                'title' => 'First API Page',
                'slug' => 'first-api-page',
                'status' => 'published',
                'visibility' => 'public',
                'seo_title' => 'SEO Page Title',
                'seo_description' => 'SEO Page Desc',
                'sections' => [
                    [
                        'section_id' => $section->id,
                        'order' => 1,
                        'is_visible' => true,
                        'overrides' => ['heading' => 'Override Heading'],
                    ]
                ]
            ]);

        $response->assertStatus(201);
        
        // Validate structure
        $response->assertJsonStructure([
            'data' => [
                'id', 'slug', 'title', 'status', 'visibility',
                'seo' => ['title', 'description', 'keywords', 'canonical_url'],
                'source', 'published_at',
                'sections' => [
                    [
                        'section_id', 'order', 'is_visible', 'overrides',
                        'section' => ['id', 'key', 'name', 'type', 'status', 'content', 'html_content', 'wrapper_class', 'anchor_id', 'source', 'created_at', 'updated_at']
                    ]
                ],
                'created_at', 'updated_at'
            ]
        ]);

        $this->assertEquals('api', $response->json('data.source'));
        $this->assertEquals('Override Heading', $response->json('data.sections.0.overrides.heading'));

        // 2. Update Page
        $updateResponse = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->putJson('/api/v1/cms/pages/first-api-page', [
                'title' => 'Updated API Page Title',
                'slug' => 'updated-api-page-slug',
                'status' => 'draft',
            ]);

        $updateResponse->assertStatus(200);
        $this->assertEquals('Updated API Page Title', $updateResponse->json('data.title'));
        $this->assertEquals('draft', $updateResponse->json('data.status'));
    }
}
