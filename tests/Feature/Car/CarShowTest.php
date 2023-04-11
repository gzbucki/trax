<?php

namespace Car;

use App\Car;
use App\Http\Resources\CarResource;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Feature\AbstractFeatureTest;

class CarShowTest extends AbstractFeatureTest
{
    use RefreshDatabase;

    public const ROUTE_NAME = 'cars.show';

    public function testUnauthenticated()
    {
        $this->getJson($this->route(parameters: [1]))
            ->assertUnauthorized();
    }

    public function testUnauthorizedWhenBelongsToDifferentUser()
    {
        $user1 = User::factory()->create();
        $car = Car::factory()->for($user1)->create();

        $user2 = User::factory()->create();

        $this->authenticate($user2)
            ->getJson($this->route(parameters: [$car->id]))
            ->assertForbidden();
    }

    public function testSuccess()
    {
        $user = User::factory()->create();
        $car = Car::factory()->for($user)->create();

        $this->authenticate($user)
            ->getJson($this->route(parameters: [$car->id]))
            ->assertSuccessful()
            ->assertJsonStructure(['data' => Car::PUBLIC_FIELDS]);
    }

    public function testCarNotFound()
    {
        $this->authenticate()
            ->getJson($this->route(parameters: [1]))
            ->assertNotFound();
    }

}
