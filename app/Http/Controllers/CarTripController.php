<?php

namespace App\Http\Controllers;

use App\Http\Resources\CarTripResource;
use App\Services\UserCarTripService;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class CarTripController extends Controller
{

    /**
     * @param UserCarTripService $service
     */
    public function __construct(private UserCarTripService $service)
    {
        //
    }

    /**
     * @param Request $request
     * @return AnonymousResourceCollection
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        $trips = $this->service->getTrips($request->user());

        return CarTripResource::collection(
            $this->service->mapLazyStdClassToDTO($trips)
        );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }
}
