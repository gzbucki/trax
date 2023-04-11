<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CarTripResource extends JsonResource
{

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->resource->id,
            'date' => $this->resource->date->format('m/d/Y'),
            'miles' => round($this->resource->miles, 2),
            'total' => round($this->resource->total, 2),
            'car' => new CarResource($this->resource->car)
        ];
    }
}
