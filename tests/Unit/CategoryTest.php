<?php

namespace Tests\Unit;

use App\Models\Category;
use App\Models\Traits\Uuid;
use Illuminate\Database\Eloquent\SoftDeletes;
use Tests\IfUseTrait;
use Tests\TestCase;

class CategoryTest extends TestCase
{
    use IfUseTrait;

    /**
     * @var Category
     */
    private $category;

    public function setUp():void {
        parent::setUp();

        $this->category = new Category();
    }

    public function testFillable() {
        $fillable = ['name', 'description', 'is_active'];
        $this->assertEquals($fillable, $this->category->getFillable());
    }

    public function testDates() {
        $dates = ['deleted_at', 'created_at', 'updated_at'];
        $this->assertEquals($dates, array_values($this->category->getDates()));
    }

    public function testCasts() {
        $casts = ['id' => 'string', 'is_active' => 'boolean'];
        $this->assertEquals($casts, $this->category->getCasts());
    }

    public function testIncrementing() {
        $this->assertFalse($this->category->incrementing);
    }

    public function testIfUseTraits()
    {
        $this->assertUseTrait(Category::class,SoftDeletes::class);
        $this->assertUseTrait(Category::class,Uuid::class);
    }

}
