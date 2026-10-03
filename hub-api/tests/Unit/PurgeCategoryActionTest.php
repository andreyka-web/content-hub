<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Actions\PurgeCategory;
use App\Models\Category;

class PurgeCategoryActionTest extends TestCase
{
    public function test_it_deletes_category_and_children()
    {
        $category = Category::factory()->create(['user_id' => $this->user->id]);
        $child = Category::factory()->create(['parent_id' => $category->id, 'user_id' => $this->user->id]);

        app(PurgeCategory::class)->execute($category);

        $this->assertSoftDeleted('categories', [
            'id' => $category->id,
        ]);

        $this->assertSoftDeleted('categories', [
            'id' => $child->id,
        ]);
    }
}