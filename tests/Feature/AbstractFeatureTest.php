<?php

namespace Tests\Feature;

use App\User;
use Exception;
use Tests\TestCase;
use Throwable;

abstract class AbstractFeatureTest extends TestCase
{

    const ROUTE_NAME = null;
    const AUTH_GUARD = 'api';

    /**
     * @param string|null $route
     * @return string
     * @throws Throwable
     */
    protected function route(?string $route = null): string
    {
        if ($route === null) {
            $route = static::ROUTE_NAME;
        }

        throw_if(
            $route === null,
            new Exception('ROUTE_NAME missing in ' . static::class)
        );

        return route(static::ROUTE_NAME);
    }

    /**
     * @return AbstractFeatureTest
     */
    protected function authenticate(?User $user = null): AbstractFeatureTest
    {
        if ($user === null) {
            $user = $this->createUser();
        }

        return $this->actingAs($user, $this->getAuthGuard());
    }

    /**
     * @return User
     */
    protected function createUser(): User
    {
        return User::factory()->create();
    }

    /**
     * @return string
     */
    protected function getAuthGuard(): string
    {
        return self::AUTH_GUARD;
    }

}
