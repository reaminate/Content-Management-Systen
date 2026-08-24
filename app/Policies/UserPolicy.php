<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\Response;

class UserPolicy
{
    public function before(User $user, string $ability): bool|null
    {
        if($user->isAdmin()){
            return true;
        }
        return null;
    }

    public function updateDelete(User $user, User $model): Response
    {
        return $user->id === $model->id
        ? Response::allow()
        : Response::denyAsNotFound();
    }

}
