<?php

namespace App\Factories;

use App\CarTrip as CarTripModel;
use App\DTO\Car;
use App\DTO\CarTrip;
use Carbon\Carbon;

class CarTripFactory
{

    /**
     * @param CarFactory $carFactory
     */
    public function __construct(private CarFactory $carFactory)
    {
        //
    }

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

    /**
     * @param CarTripModel $carTrip
     * @return CarTrip
     */
    public function createFromModel(CarTripModel $carTrip): CarTrip
    {
        $car = $this->carFactory->createFromModel($carTrip->car);

        return new CarTrip(
            $carTrip->id,
            $carTrip->date,
            $carTrip->miles,
            $car,
            $carTrip->total
        );
    }

}
