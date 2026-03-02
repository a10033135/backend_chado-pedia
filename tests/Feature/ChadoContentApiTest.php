<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\MainCate;
use App\Models\SubCate;
use App\Models\ChadoContent;

class ChadoContentApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_create_chado_content_with_relations()
    {
        $mainCate = MainCate::create(['title' => 'Main']);
        $subCate = SubCate::create(['title' => 'Sub']);

        $payload = [
            'title' => 'New Tea Guide',
            'description' => 'Hello World',
            'main_cate_ids' => [$mainCate->id],
            'sub_cate_ids' => [$subCate->id],
        ];

        $response = $this->postJson('/api/admin/chado-content', $payload);

        $response->assertStatus(201)
            ->assertJsonPath('data.title', 'New Tea Guide');

        $this->assertDatabaseHas('ChadoContent', ['title' => 'New Tea Guide']);

        $contentId = $response->json('data.id');
        $this->assertDatabaseHas('ChadoContent_MainCate', ['chado_content_id' => $contentId, 'main_cate_id' => $mainCate->id]);
        $this->assertDatabaseHas('ChadoContent_SubCate', ['chado_content_id' => $contentId, 'sub_cate_id' => $subCate->id]);
    }

    public function test_public_index_filters_by_category()
    {
        $mainCate = MainCate::create(['title' => 'Main']);

        $content = ChadoContent::create(['title' => 'Content 1', 'enable' => true]);
        $content->mainCategories()->attach($mainCate->id);

        $content2 = ChadoContent::create(['title' => 'Content 2', 'enable' => true]);

        // Fetch without filter
        $response = $this->getJson('/api/chado-content');
        $response->assertStatus(200)->assertJsonCount(2, 'data');

        // Fetch with main_cate_id filter
        $responseFiltered = $this->getJson('/api/chado-content?main_cate_id=' . $mainCate->id);
        $responseFiltered->assertStatus(200)
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.title', 'Content 1');
    }
}
