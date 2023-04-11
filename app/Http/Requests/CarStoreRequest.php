<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CarStoreRequest extends FormRequest
{

    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            'make' => 'required|string',
            'model' => 'required|string',
            'year' => 'required|integer',
        ];
    }

}
