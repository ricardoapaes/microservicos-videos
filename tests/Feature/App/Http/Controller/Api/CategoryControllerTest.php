<?php

namespace Tests\Feature\App\Http\Controller\Api;

use App\Models\Category;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Lang;
use Tests\TestCase;

class CategoryControllerTest extends TestCase {
    use DatabaseMigrations;

    const ROUTE_NAME = 'categories';

    private function getRoute($name, ...$params) {
        return route(self::ROUTE_NAME . '.' . $name, ...$params);
    }

    public function testIndex() {
        $category = factory(Category::class)->create();
        $response = $this->get($this->getRoute('index'));

        $response
            ->assertStatus(200)
            ->assertJson([$category->toArray()]);
    }

    public function testShow() {
        /** @var Category $category */
        $category = factory(Category::class)->create();
        $response = $this->get(route('categories.show', ['category' => $category->id]));

        $response
            ->assertStatus(200)
            ->assertJson($category->toArray());
    }

    public function testInvalidNameRequired() {
        $response = $this->json('POST', $this->getRoute('store',[]));
        $this->assertValidationRequired($response);

        /** @var Category $category */
        $category = factory(Category::class)->create();
        $response = $this->json('PUT', $this->getRoute('update', ['category' => $category->id]));
        $this->assertValidationRequired($response);
    }

    private function assertValidationRequired($response) {
        $response
            ->assertStatus(422)
            ->assertJsonValidationErrors(['name'])
            ->assertJsonFragment([
                Lang::get('validation.required', ['attribute'=>'name'])
            ]);
    }

    public function testInvalidNameMaxLength() {
        $response = $this->json('POST', $this->getRoute('store'), [
            'name' => str_repeat('a',Category::NAME_MAX_LENGTH+1)
        ]);
        $this->assertValidationMaxLength($response);

        /** @var Category $category */
        $category = factory(Category::class)->create();
        $response = $this->json('PUT', $this->getRoute('update', ['category' => $category->id]), [
            'name' => str_repeat('a',Category::NAME_MAX_LENGTH+1)
        ]);
        $this->assertValidationMaxLength($response);
    }

    private function assertValidationMaxLength($response) {
        $response
            ->assertStatus(422)
            ->assertJsonValidationErrors(['name'])
            ->assertJsonFragment([
                Lang::get('validation.max.string', ['attribute'=>'name', 'max'=>Category::NAME_MAX_LENGTH])
            ]);
    }

    public function testInvalidIsActive() {
        $response = $this->json('POST', $this->getRoute('store'), [
            'is_active' => 's'
        ]);
        $this->assertValidationBoolean($response);

        /** @var Category $category */
        $category = factory(Category::class)->create();
        $response = $this->json('PUT', $this->getRoute('update', ['category' => $category->id]), [
            'is_active' => 's'
        ]);
        $this->assertValidationBoolean($response);
    }

    private function assertValidationBoolean($response) {
        $response
            ->assertStatus(422)
            ->assertJsonValidationErrors(['is_active'])
            ->assertJsonFragment([
                Lang::get('validation.boolean', ['attribute'=>'is active'])
            ]);
    }

}
