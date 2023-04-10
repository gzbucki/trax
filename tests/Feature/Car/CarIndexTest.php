<?php

namespace Tests\Feature\Car;

use App\Car;
use App\Http\Resources\CarResource;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Feature\AbstractFeatureTest;

class CarIndexTest extends AbstractFeatureTest
{
    use RefreshDatabase;

    const ROUTE_NAME = 'cars.index';

    public function testUnauthenticated()
    {
        $this->getJson($this->route())
            ->assertUnauthorized();
    }

    public function testOneCarSuccessful()
    {
        $user1 = User::factory()->create();
        Car::factory()->for($user1)->create();

        $user2 = User::factory()->create();
        $car = Car::factory()->for($user2)->create();

        $this->authenticate($user2)
            ->getJson($this->route())
            ->assertSuccessful()
            ->assertJsonCount(1, 'data')
            ->assertExactJson(
                ['data' => [$car->only(CarResource::VISIBLE_FIELDS)]]
            );

        $this->assertDatabaseCount(Car::class, 2);
    }

    public function testFiveCarsSuccessful()
    {
        $user1 = User::factory()->create();
        Car::factory()->for($user1)->create();

        $user2 = User::factory()->create();
        Car::factory(5)->for($user2)->create();

        $this->authenticate($user2)
            ->getJson($this->route())
            ->assertSuccessful()
            ->assertJsonStructure(['data' => [CarResource::VISIBLE_FIELDS]])
            ->assertJsonCount(5, 'data');

        $this->assertDatabaseCount(Car::class, 6);
    }

    public function testNoCarsSuccessful()
    {
        $user1 = User::factory()->create();
        Car::factory()->for($user1)->create();

        $user2 = User::factory()->create();

        $this->authenticate($user2)
            ->getJson($this->route())
            ->assertSuccessful()
            ->assertJsonStructure(['data'])
            ->assertJsonCount(0, 'data');

        $this->assertDatabaseCount(Car::class, 1);
    }

}
