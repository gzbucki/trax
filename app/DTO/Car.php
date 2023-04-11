<?php

namespace App\DTO;

class Car
{

    /**
     * @param int $id
     * @param string $make
     * @param string $model
     * @param int $year
     */
    public function __construct(
        public int $id,
        public string $make,
        public string $model,
        public int $year
    ) {
        //
    }

}
