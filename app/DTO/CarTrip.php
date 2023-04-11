<?php

namespace App\DTO;

use Carbon\Carbon;

class CarTrip
{

    /**
     * @param int $id
     * @param Carbon $date
     * @param float $miles
     * @param Car $car
     * @param float $total
     */
    public function __construct(
        public int $id,
        public Carbon $date,
        public float $miles,
        public Car $car,
        public float $total = 0
    ) {
        //
    }

}
