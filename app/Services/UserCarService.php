<?php

namespace App\Services;

use App\Car;
use App\User;
use Illuminate\Database\Eloquent\Collection;

class UserCarService
{

    /**
     * @param User $user
     * @return Collection
     */
    public function getCars(User $user): Collection
    {
        return $user->cars()->get();
    }

    /**
     * @param User $user
     * @param array $data
     * @return Car
     */
    public function create(User $user, array $data): Car
    {
        return $user->cars()->create($data);
    }

}
