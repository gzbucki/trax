<?php

namespace App\Http\Factories;

use App\DTO\Car;
use App\DTO\CarTrip;
use Carbon\Carbon;

class CarTripFactory
{

    /**
     * @param int $id
     * @param Carbon $date
     * @param float $miles
     * @param Car $car
     * @param float $total
     * @return CarTrip
     */
    public function create(
        int $id,
        Carbon $date,
        float $miles,
        Car $car,
        float $total = 0
    ): CarTrip {
        return new CarTrip($id, $date, $miles, $car, $total);
    }

}
