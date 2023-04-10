<?php

namespace App\Http\Controllers;

use App\Http\Requests\CarStoreRequest;
use App\Http\Resources\CarResource;
use App\Services\UserCarService;
use Illuminate\Http\Request;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Symfony\Component\HttpFoundation\Response;

class CarController extends Controller
{

    /**
     * @param UserCarService $service
     */
    public function __construct(private UserCarService $service)
    {
        //
    }

    /**
     * @param Request $request
     * @return AnonymousResourceCollection
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        return CarResource::collection(
            $this->service->getCars($request->user('api'))
        );
    }

    /**
     * @param CarStoreRequest $request
     * @return Response
     */
    public function store(CarStoreRequest $request): Response
    {
        $car = $this->service->create($request->user(), $request->validated());

        return (new CarResource($car))->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    /**
     * @param int $id
     * @return CarResource
     * @throws AuthorizationException
     */
    public function show(int $id): CarResource
    {
        $car = $this->service->find($id);

        abort_if($car === null, Response::HTTP_NOT_FOUND);
        $this->authorize('view', $car);

        return new CarResource($car);
    }

    /**
     * @param int $id
     * @return Response
     * @throws AuthorizationException
     */
    public function destroy(int $id): Response
    {
        $car = $this->service->find($id);

        abort_if($car === null, Response::HTTP_NOT_FOUND);
        $this->authorize('delete', $car);

        $this->service->delete($car);

        return \response()->noContent();
    }
}
