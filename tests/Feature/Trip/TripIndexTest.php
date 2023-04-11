<?php

namespace Tests\Feature\Trip;

use App\Car;
use App\CarTrip;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Feature\AbstractFeatureTest;

class TripIndexTest extends AbstractFeatureTest
{

    use RefreshDatabase;

    const ROUTE_NAME = 'trips.index';

    public function testUnauthenticated()
    {
        $this->getJson($this->route())
            ->assertUnauthorized();
    }

    public function testNoTripsSuccessful()
    {
        $this->authenticate()
            ->getJson($this->route())
            ->assertSuccessful()
            ->assertJsonStructure(['data'])
            ->assertJsonCount(0, 'data');
    }

    public function testSuccessful()
    {
        $user = User::factory()->create();
        $car = Car::factory()->for($user)->create();
        $trip = CarTrip::factory()->for($user)->for($car)->create();

        $this->authenticate($user)
            ->getJson($this->route())
            ->assertSuccessful()
            ->assertJsonCount(1, 'data')
            ->assertJsonStructure(['data' => [CarTrip::PUBLIC_FIELDS]])
            ->assertJsonFragment(
                [
                    'id' => $trip->id,
                    'date' => $trip->date->format('m/d/Y'),
                    'miles' => $trip->miles,
                    'total' => $trip->total,
                ]
            )
            ->assertJsonFragment($car->only(Car::PUBLIC_FIELDS));
    }

    public function testSumTotalMiles()
    {
        $user = User::factory()->create();
        $car1 = Car::factory()->for($user)->create();
        $car2 = Car::factory()->for($user)->create();

        $trip1 = CarTrip::factory()->for($user)->for($car1)->create();
        $trip2 = CarTrip::factory()->for($user)->for($car1)->create();

        $total = round($trip1->miles + $trip2->miles, 2);
        $this->assertEquals($total, $trip1->total);
        $this->assertEquals($total, $trip2->total);

        $trip3 = CarTrip::factory()->for($user)->for($car2)->create();
        $this->assertEquals($trip3->miles, $trip3->total);

        $this->authenticate($user)
            ->getJson($this->route())
            ->assertSuccessful()
            ->assertJsonCount(3, 'data')
            ->assertJsonFragment(
                [
                    'id' => $trip1->id,
                    'date' => $trip1->date->format('m/d/Y'),
                    'miles' => $trip1->miles,
                    'total' => $total,
                ]
            )
            ->assertJsonFragment(
                [
                    'id' => $trip2->id,
                    'date' => $trip2->date->format('m/d/Y'),
                    'miles' => $trip2->miles,
                    'total' => $trip3->miles,
                ]
            );
    }

}
