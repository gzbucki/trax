<?php

namespace App\Services;

use App\Car;
use App\CarTrip;
use App\DTO\CarTrip as CarTripDTO;
use App\Factories\CarFactory;
use App\Factories\CarTripFactory;
use App\User;
use Carbon\Carbon;
use Illuminate\Support\LazyCollection;
use stdClass;

class UserCarTripService
{

    /**
     * @param CarFactory $carFactory
     * @param CarTripFactory $carTripFactory
     */
    public function __construct(
        private CarFactory $carFactory,
        private CarTripFactory $carTripFactory
    ) {
        //
    }

    /**
     * @param User $user
     * @param Car $car
     * @param array $data
     * @return CarTrip
     */
    public function create(User $user, Car $car, array $data): CarTrip
    {
        $trip = new CarTrip();
        $trip->date = Carbon::parse($data['date'])->format('Y-m-d');
        $trip->miles = $data['miles'];
        $trip->user()->associate($user);

        return $car->trips()->save($trip);
    }

    /**
     * @param User $user
     * @return LazyCollection
     */
    public function getTrips(User $user): LazyCollection
    {
        $connection = CarTrip::resolveConnection();

        $subQuery = Car::resolveConnection()->query()
            ->from(Car::TABLE_NAME);

        return $connection->query()
            ->select(
                [
                    'car_trip.id',
                    'car_trip.date',
                    'car_trip.miles',
                    'car.id as car_id',
                    'car.make as car_make',
                    'car.model as car_model',
                    'car.year as car_year',
                ]
            )
            ->selectSub(
                fn ($query) => $query
                    ->selectRaw('SUM(ct.miles)')
                    ->from(CarTrip::TABLE_NAME, 'ct')
                    ->whereRaw('car_trip.car_id = ct.car_id'),
            'total'
            )
            ->from(CarTrip::TABLE_NAME, 'car_trip')
            ->joinSub(
                $subQuery,
                'car',
                fn ($join) => $join->on('car_trip.car_id', '=', 'car.id')
            )
            ->where('car_trip.user_id', $user->id)
            ->where('car.user_id', $user->id)
            ->orderBy('car_trip.date', 'DESC')
            ->cursor();
    }

    /**
     * @param LazyCollection $collection
     * @return LazyCollection
     */
    public function mapLazyStdClassToDTO(LazyCollection $collection): LazyCollection
    {
        return $collection->map(fn ($record) => $this->stdClassToDTO($record));
    }

    /**
     * @param CarTrip $model
     * @return CarTripDTO|null
     */
    public function modelToDTO(CarTrip $model): ?CarTripDTO
    {
        return $this->carTripFactory->createFromModel($model);
    }

    /**
     * @param stdClass $record
     * @return CarTripDTO
     */
    private function stdClassToDTO(stdClass $record): CarTripDTO
    {
        $car = $this->carFactory->create(
            $record->car_id,
            $record->car_make,
            $record->car_model,
            $record->car_year
        );

        return $this->carTripFactory->create(
            $record->id,
            Carbon::createFromFormat('Y-m-d', $record->date),
            $record->miles,
            $car,
            $record->total
        );
    }

}
