<?php

namespace App\Services;

use App\Car;
use App\DTO\Car as CarDTO;
use App\Http\Factories\CarFactory;
use App\User;
use Illuminate\Support\LazyCollection;

class UserCarService
{

    /**
     * @param CarFactory $carFactory
     */
    public function __construct(private CarFactory $carFactory)
    {
        //
    }

    /**
     * @param User $user
     * @return LazyCollection
     */
    public function getCars(User $user): LazyCollection
    {
        return $user->cars()
            ->cursor();
    }

    /**
     * @param User $user
     * @param array $data
     * @return Car
     */
    public function create(User $user, array $data): Car
    {
        return $user->cars()->create($data);
    }

    /**
     * @param int $id
     * @return Car|null
     */
    public function find(int $id): ?Car
    {
        return Car::query()->find($id);
    }

    /**
     * @param Car $car
     * @return bool|null
     */
    public function delete(Car $car): ?bool
    {
        return $car->delete();
    }

    /**
     * @param Car $car
     * @return CarDTO
     */
    public function modelToDTO(Car $car): CarDTO
    {
        return $this->carFactory->createFromModel($car);
    }

    /**
     * @param LazyCollection $collection
     * @return LazyCollection
     */
    public function mapLazyModelsToDTO(LazyCollection $collection): LazyCollection {
        return $collection->map(fn ($car) => $this->modelToDTO($car));
    }

}
