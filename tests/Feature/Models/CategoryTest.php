<?php

namespace Tests\Feature\Models;

use App\Models\Category;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class CategoryTest extends TestCase {
    use DatabaseMigrations;

    public function testList() {
        factory(Category::class, 1)->create();

        $categories = Category::all();
        $this->assertCount(1, $categories);

        $category = array_keys($categories->first()->getAttributes());
        $this->assertEqualsCanonicalizing(
            ['id', 'name', 'description', 'is_active', 'deleted_at', 'created_at', 'updated_at'],
            $category
        );
    }

    public function testCreateNoDescription() {
        $category = Category::create(['name' => 'Teste 01']);
        $category->refresh();

        $this->assertEquals(36, strlen($category->id));
        $this->assertEquals('Teste 01', $category->name);
        $this->assertNull($category->description);
        $this->assertTrue($category->is_active);
    }

    public function testCreateWithDescription() {
        $category = Category::create([
            'name' => 'Teste 02',
            'description' => 'Descrição'
        ]);
        $category->refresh();

        $this->assertEquals('Teste 02', $category->name);
        $this->assertEquals('Descrição', $category->description);
        $this->assertTrue($category->is_active);
    }

    public function testCreateInactive() {
        $category = Category::create([
            'name' => 'Teste 03',
            'is_active' => false
        ]);
        $category->refresh();

        $this->assertEquals('Teste 03', $category->name);
        $this->assertNull($category->description);
        $this->assertFalse($category->is_active);
    }

    public function testUpdate() {
        /** @var Category $category */
        $category = factory(Category::class)->create([
            'description' => 'Minha descrição',
            'is_active' => false
        ]);

        $category->update([
            'name' => 'Teste 04',
            'description' => 'Outra descrição',
            'is_active' => true
        ]);

        $this->assertEquals('Teste 04', $category->name);
        $this->assertEquals('Outra descrição', $category->description);
        $this->assertTrue($category->is_active);
    }

    public function testDelete() {
        /** @var Category $category */
        $category = factory(Category::class)->create();

        $category->delete();
        $this->assertNull(Category::find($category->id));

        $category->restore();
        $this->assertNotNull(Category::find($category->id));
    }

}
