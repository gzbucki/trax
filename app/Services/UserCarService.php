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

    /**
     * @param User $user
     * @param int $id
     * @return Car|null
     */
    public function find(User $user, int $id): ?Car
    {
        return $user->cars()->find($id);
    }

}
