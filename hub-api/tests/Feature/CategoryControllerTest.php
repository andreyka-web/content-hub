<?php 

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Category;

class CategoryControllerTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
    }

    // Create category tests
    public function test_user_can_create_category(): void
    {
        $payload = [
            'name' => 'Test Category',
            'parent_id' => null,
        ];
        $response = $this->actingAs($this->user)->postJson('/api/category', $payload);
        $response->assertCreated()
            ->assertJsonPath('name', $payload['name']);
    }

    public function test_user_can_create_nested_category(): void
    {
        $category = Category::factory()->create(['user_id' => $this->user->id]);
        $payload = [
            'name' => 'Test Subcategory',
            'parent_id' => $category->id,
        ];
        $response = $this->actingAs($this->user)->postJson('/api/category', $payload);
        $response->assertCreated()
            ->assertJsonPath('name', $payload['name']);
    }

    public function test_user_cannot_create_subcategory_in_another_user_category(): void
    {
        $anotherUser = User::factory()->create();
        $category = Category::factory()->create(['user_id' => $anotherUser->id]);

        $payload = [
            'name' => 'Test Subcategory',
            'parent_id' => $category->id,
        ];

        $response = $this->actingAs($this->user)->postJson('/api/category', $payload);

        $response->assertForbidden();
    }

    // View category tests
    public function test_user_can_view_categories(): void
    {
        $categories = Category::factory()->count(5)->create(['user_id' => $this->user->id]);

        $response = $this->actingAs($this->user)->get('/api/category');

        $response->assertOk()
            ->assertJsonCount(5, 'data')
            ->assertJson(['total' => 5]);
    }

    public function test_user_cannot_view_categories_of_another_user(): void
    {
        $categories = Category::factory()->count(5)->create(['user_id' => $this->user->id]);
        $unauthorizedUser = User::factory()->create();

        $response = $this->actingAs($unauthorizedUser)->get('/api/category');

        $response->assertOk()
            ->assertJsonCount(0, 'data')
            ->assertJson(['total' => 0]);
    }

    public function test_user_can_view_category(): void
    {
        $category = Category::factory()->create(['user_id' => $this->user->id]);
        
        $response = $this->actingAs($this->user)->get("/api/category/{$category->id}");
        
        $response->assertOk();
    }

    public function test_user_cannot_view_category_of_another_user(): void
    {
        $anotherUser = User::factory()->create();
        $category = Category::factory()->create(['user_id' => $anotherUser->id]);

        $response = $this->actingAs($this->user)->get("/api/category/{$category->id}");

        $response->assertNotFound();
    }

    // Update category tests
    public function test_user_can_update_category()
    {
        $category = Category::factory()->create(['user_id' => $this->user->id]);

        $response = $this->actingAs($this->user)->put("/api/category/{$category->id}", [
            'name' => 'Updated Category',
        ]);
 
        $response->assertOk();
    }

    public function test_user_cannot_update_another_user_category()
    {
        $anotherUser = User::factory()->create();
        $category = Category::factory()->create(['user_id' => $anotherUser->id]);

        $payload = [
            'name' => 'Updated Category',
        ];

        $response = $this->actingAs($this->user)->put("/api/category/{$category->id}", $payload);

        $response->assertForbidden();
    }

    // Delete category tests
    public function test_user_can_delete_category()
    {
        $category = Category::factory()->create(['user_id' => $this->user->id]);
        
        $response = $this->actingAs($this->user)->delete("/api/category/{$category->id}");
        
        $response->assertNoContent();

        $this->assertSoftDeleted('categories', ['id' => $category->fresh()->id]);
    }

    public function test_user_cannot_delete_category_of_another_user()
    {
        $anotherUser = User::factory()->create();
        $category = Category::factory()->create(['user_id' => $anotherUser->id]);

        $response = $this->actingAs($this->user)->delete("/api/category/{$category->id}");

        $response->assertNotFound();

        $this->assertNotSoftDeleted('categories', ['id' => $category->id]);
    }
}