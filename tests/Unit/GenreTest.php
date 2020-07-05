<?php

namespace Tests\Unit;

use App\Models\Genre;
use App\Models\Traits\Uuid;
use Illuminate\Database\Eloquent\SoftDeletes;
use Tests\IfUseTrait;
use Tests\TestCase;

class GenreTest extends TestCase
{

    use IfUseTrait;

    /**
     * @var Genre
     */
    private $genre;

    public function setUp():void {
        parent::setUp();

        $this->genre = new Genre();
    }

    public function testFillable() {
        $fillable = ['name', 'is_active'];
        $this->assertEquals($fillable, $this->genre->getFillable());
    }

    public function testDates() {
        $dates = ['deleted_at', 'created_at', 'updated_at'];
        $this->assertEquals($dates, array_values($this->genre->getDates()));
    }

    public function testCasts() {
        $casts = ['id' => 'string', 'is_active' => 'boolean'];
        $this->assertEquals($casts, $this->genre->getCasts());
    }

    public function testIncrementing() {
        $this->assertFalse($this->genre->incrementing);
    }

    public function testIfUseTraits()
    {
        $this->assertUseTrait(Genre::class,SoftDeletes::class);
        $this->assertUseTrait(Genre::class,Uuid::class);
    }

}
