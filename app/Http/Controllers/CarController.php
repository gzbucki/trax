<?php

namespace App\Http\Controllers;

use App\Http\Requests\CarStoreRequest;
use App\Http\Resources\CarResource;
use App\Services\UserCarService;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Symfony\Component\HttpFoundation\Response;

class CarController extends Controller
{

    public function __construct(private UserCarService $service)
    {
        //
    }

    /**
     * Display a listing of the resource.
     *
     * @return AnonymousResourceCollection
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        return CarResource::collection(
            $this->service->getCars($request->user('api'))
        );
    }

    /**
     * Store a newly created resource in storage.
     *
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
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
