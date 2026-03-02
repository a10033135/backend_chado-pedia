<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\MainCate;
use App\Models\SubCate;

class SubCateApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_list_public_sub_categories()
    {
        SubCate::create(['title' => 'Visible', 'enable' => true]);
        SubCate::create(['title' => 'Hidden', 'enable' => false]);

        $response = $this->get('/api/sub-cate');

        $response->assertStatus(200)
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.title', 'Visible');
    }

    public function test_can_create_sub_category()
    {
        $mainCate = MainCate::create(['title' => 'Parent', 'enable' => true]);

        $payload = [
            'main_cate_id' => $mainCate->id,
            'title' => 'Child Cate',
            'sort' => 5,
        ];

        $response = $this->postJson('/api/admin/sub-cate', $payload);

        $response->assertStatus(201)
            ->assertJsonPath('data.title', 'Child Cate')
            ->assertJsonPath('data.sort', 5);

        $this->assertDatabaseHas('SubCate', ['title' => 'Child Cate', 'main_cate_id' => $mainCate->id]);
    }
}
