<?php

namespace Car;

use App\Car;
use App\Http\Resources\CarResource;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Feature\AbstractFeatureTest;

class CarStoreTest extends AbstractFeatureTest
{
    use RefreshDatabase;

    public const ROUTE_NAME = 'cars.store';

    public function testUnauthenticated()
    {
        $this->getJson($this->route())
            ->assertUnauthorized();
    }

    public function testSuccessful()
    {
        $carData = Car::factory()->raw();

        $this->assertDatabaseCount(Car::class, 0);

        $this->authenticate()
            ->postJson($this->route(), $carData)
            ->assertCreated()
            ->assertJsonStructure(['data' => CarResource::VISIBLE_FIELDS])
            ->assertJsonFragment($carData);

        $this->assertDatabaseHas(Car::class, $carData);
        $this->assertDatabaseCount(Car::class, 1);
    }

    public function testValidationErrors()
    {
        $carData = ['make' => null, 'model' => null, 'year' => null];

        $this->authenticate()
            ->postJson($this->route(), $carData)
            ->assertUnprocessable()
            ->assertJsonStructure(
                ['message', 'errors' => ['make', 'model', 'year']]
            );

        $this->assertDatabaseCount(Car::class, 0);
    }

}
