<?php

namespace Tests\Feature\Trip;

use App\Car;
use App\CarTrip;
use App\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Feature\AbstractFeatureTest;

class TripStoreTest extends AbstractFeatureTest
{

    use RefreshDatabase;

    const ROUTE_NAME = 'trips.store';

    public function testUnauthenticated()
    {
        $this->postJson($this->route())
            ->assertUnauthorized();
    }

    public function testUnauthorized()
    {
        $user = User::factory()->create();
        $car = Car::factory()->for($user)->create();
        CarTrip::factory()->for($user)->for($car)->create();

        $data = CarTrip::factory()->for($car)->raw();

        $this->authenticate()
            ->postJson($this->route(), $data)
            ->assertForbidden();
    }

    public function testCarNotFoundSuccessful()
    {
        $user = User::factory()->create();

        $data = CarTrip::factory()->raw(['car_id' => 1]);

        $this->authenticate($user)
            ->postJson($this->route(), $data)
            ->assertNotFound();

        $this->assertDatabaseMissing(CarTrip::class, $data);
    }

    public function testSuccessful()
    {
        $user = User::factory()->create();
        $car = Car::factory()->for($user)->create();

        $data = CarTrip::factory()->for($car)->raw();

        $formattedDate = Carbon::createFromFormat('Y-m-d', $data['date']);

        $this->authenticate($user)
            ->postJson($this->route(), $data)
            ->assertCreated()
            ->assertJsonStructure(['data' => CarTrip::PUBLIC_FIELDS])
            ->assertJsonFragment(
                [
                    'date' => $formattedDate->format('m/d/Y'),
                    'miles' => $data['miles'],
                    'total' => $data['miles'],
                    'car' => [
                        'id' => $car->id,
                        'make' => $car->make,
                        'model' => $car->model,
                        'year' => $car->year,
                    ]
                ]
            );

        $this->assertDatabaseHas(CarTrip::class, $data);
    }

    public function testValidationErrors()
    {
        $carData = ['date' => null, 'miles' => null, 'car_id' => null];

        $this->authenticate()
            ->postJson($this->route(), $carData)
            ->assertUnprocessable()
            ->assertJsonStructure(
                ['message', 'errors' => ['date', 'miles', 'car_id']]
            );

        $this->assertDatabaseCount(Car::class, 0);
    }

}
