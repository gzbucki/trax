<?php

namespace Tests\Feature\Car;

use Tests\TestCase;

class CarIndexTest extends TestCase
{

    public function testUnauthenticated()
    {
        $response = $this->getJson(route('cars.index'));

        $response->assertUnauthorized();
    }

}
