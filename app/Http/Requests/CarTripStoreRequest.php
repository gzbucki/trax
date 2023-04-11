<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CarTripStoreRequest extends FormRequest
{

    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            'date' => 'required|date', // ISO 8601 string
            'car_id' => 'required|integer',
            'miles' => 'required|numeric'
        ];
    }

}
