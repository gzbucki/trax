<?php

namespace App\Factories;

use App\Car as CarModel;
use App\DTO\Car;

class CarFactory
{

    /**
     * @param int $id
     * @param string $make
     * @param string $model
     * @param int $year
     * @return Car
     */
    public function create(
        int $id,
        string $make,
        string $model,
        int $year
    ): Car {
        return new Car($id, $make, $model, $year);
    }

    /**
     * @param CarModel|null $car
     * @return Car|null
     */
    public function createFromModel(?CarModel $car): ?Car
    {
        if ($car === null) {
            return null;
        }

        return new Car($car->id, $car->make, $car->model, $car->year);
    }

}
