<?php

namespace App\Policies;

use App\Car;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class CarPolicy
{
    use HandlesAuthorization;

    /**
     * @param User $user
     * @param Car $car
     * @return bool
     */
    public function view(User $user, Car $car): bool
    {
        return $car->user_id === $user->id;
    }

    /**
     * @param User $user
     * @param Car $car
     * @return bool
     */
    public function delete(User $user, Car $car): bool
    {
        return $car->user_id === $user->id;
    }
}
