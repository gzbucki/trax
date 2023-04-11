<?php

namespace Car;

use App\Car;
use App\Http\Resources\CarResource;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Feature\AbstractFeatureTest;

class CarDeleteTest extends AbstractFeatureTest
{
    use RefreshDatabase;

    public const ROUTE_NAME = 'cars.destroy';

    public function testUnauthenticated()
    {
        $this->deleteJson($this->route(parameters: [1]))
            ->assertUnauthorized();
    }

    public function testUnauthorizedWhenBelongsToDifferentUser()
    {
        $user1 = User::factory()->create();
        $car = Car::factory()->for($user1)->create();

        $user2 = User::factory()->create();

        $this->authenticate($user2)
            ->deleteJson($this->route(parameters: [$car->id]))
            ->assertForbidden();

        $this->assertDatabaseCount(Car::class, 1);
    }

    public function testSuccessful()
    {
        $user = User::factory()->create();
        $car = Car::factory()->for($user)->create();
        $carData = $car->only(Car::PUBLIC_FIELDS);

        $this->assertDatabaseCount(Car::class, 1);

        $this->authenticate($user)
            ->deleteJson($this->route(parameters: [$car->id]))
            ->assertNoContent();

        $this->assertDatabaseMissing(Car::class, $carData);
        $this->assertDatabaseCount(Car::class, 0);
    }

    public function testCarNotFound()
    {
        $this->authenticate()
            ->deleteJson($this->route(parameters: [1]))
            ->assertNotFound();
    }

}
