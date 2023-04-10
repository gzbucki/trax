<?php

namespace Tests\Feature\Trip;

use Tests\TestCase;

class TripIndexTest extends TestCase
{

    public function testUnauthenticated()
    {
        $response = $this->getJson(route('trips.index'));

        $response->assertUnauthorized();
    }

}
