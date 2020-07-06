<?php

namespace Tests\Feature\Models;

use App\Models\Genre;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class GenreTest extends TestCase {

    use DatabaseMigrations;

    public function testList() {
        factory(Genre::class, 1)->create();

        $genres = Genre::all();
        $this->assertCount(1, $genres);

        $genre = array_keys($genres->first()->getAttributes());
        $this->assertEqualsCanonicalizing(
            ['id', 'name', 'is_active', 'deleted_at', 'created_at', 'updated_at'],
            $genre
        );
    }

    public function testDelete() {
        /** @var Genre $genre */
        $genre = factory(Genre::class)->create();

        $genre->delete();
        $this->assertNull(Genre::find($genre->id));

        $genre->restore();
        $this->assertNotNull(Genre::find($genre->id));
    }

    public function testCreateNoDescription() {
        $genre = Genre::create(['name' => 'Teste 01']);
        $genre->refresh();

        $this->assertEquals(36, strlen($genre->id));
        $this->assertEquals('Teste 01', $genre->name);
        $this->assertTrue($genre->is_active);
    }

    public function testCreateWithDescription() {
        $genre = Genre::create([
            'name' => 'Teste 02',
            'description' => 'Descrição'
        ]);
        $genre->refresh();

        $this->assertEquals('Teste 02', $genre->name);
        $this->assertTrue($genre->is_active);
    }

    public function testCreateInactive() {
        $genre = Genre::create([
            'name' => 'Teste 03',
            'is_active' => false
        ]);
        $genre->refresh();

        $this->assertEquals('Teste 03', $genre->name);
        $this->assertFalse($genre->is_active);
    }

    public function testUpdate() {
        /** @var Genre $genre */
        $genre = factory(Genre::class)->create([
            'is_active' => false
        ]);

        $genre->update([
            'name' => 'Teste 04',
            'is_active' => true
        ]);

        $this->assertEquals('Teste 04', $genre->name);
        $this->assertTrue($genre->is_active);
    }

}
