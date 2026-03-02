<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\MainCate;

class MainCateApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_list_public_main_categories()
    {
        MainCate::create(['title' => 'Enabled Cate', 'enable' => true]);
        MainCate::create(['title' => 'Disabled Cate', 'enable' => false]);

        $response = $this->get('/api/main-cate');

        $response->assertStatus(200)
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.title', 'Enabled Cate');
    }

    public function test_can_create_main_category()
    {
        $payload = [
            'title' => 'New Cate',
            'description' => 'Test Desc',
            'has_image' => true,
            'enable' => true,
        ];

        $response = $this->postJson('/api/admin/main-cate', $payload);

        $response->assertStatus(201)
            ->assertJsonPath('data.title', 'New Cate');

        $this->assertDatabaseHas('MainCate', ['title' => 'New Cate']);
    }

    public function test_can_update_main_category()
    {
        $cate = MainCate::create(['title' => 'Old Title', 'enable' => true]);

        $response = $this->putJson("/api/admin/main-cate/{$cate->id}", [
            'title' => 'Updated Title'
        ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('MainCate', ['title' => 'Updated Title']);
    }

    public function test_can_delete_main_category()
    {
        $cate = MainCate::create(['title' => 'To Delete', 'enable' => true]);

        $response = $this->deleteJson("/api/admin/main-cate/{$cate->id}");

        $response->assertStatus(200);
        $this->assertDatabaseMissing('MainCate', ['id' => $cate->id]);
    }
}
