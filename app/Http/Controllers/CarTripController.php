<?php

namespace App\Http\Controllers;

use App\Http\Requests\CarTripStoreRequest;
use App\Http\Resources\CarTripResource;
use App\Services\UserCarService;
use App\Services\UserCarTripService;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Symfony\Component\HttpFoundation\Response;

class CarTripController extends Controller
{

    /**
     * @param UserCarService $carService
     * @param UserCarTripService $carTripService
     */
    public function __construct(
        private UserCarService $carService,
        private UserCarTripService $carTripService
    ) {
        //
    }

    /**
     * @param Request $request
     * @return AnonymousResourceCollection
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        $trips = $this->carTripService->getTrips($request->user());

        return CarTripResource::collection(
            $this->carTripService->mapLazyStdClassToDTO($trips)
        );
    }

    /**
     * @param CarTripStoreRequest $request
     * @return Response
     * @throws AuthorizationException
     */
    public function store(CarTripStoreRequest $request): Response
    {
        $car = $this->carService->find($request->validated('car_id'));

        abort_if($car === null, Response::HTTP_NOT_FOUND);
        $this->authorize('create_trip', $car);

        $trip = $this->carTripService->create(
            $request->user(),
            $car,
            [
                'date' => $request->validated('date'),
                'miles' => $request->validated('miles'),
            ]
        );

        $resource = new CarTripResource(
            $this->carTripService->modelToDTO($trip)
        );

        return $resource->response()->setStatusCode(Response::HTTP_CREATED);
    }
}
