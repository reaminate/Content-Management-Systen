<?php

namespace App\Policies;

use App\Models\Blog;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class BlogPolicy
{
    public function before(User $user, string $ability): bool|null
    {
        if($user->isAdmin()){
            return true;
        }
        return null;
    }

    public function update(User $user, Blog $blog): Response
    {
        return $user->author?->id === $blog->author_id
            ? Response::allow()
            : Response::denyAsNotFound();
    }

    public function delete(User $user, Blog $blog): Response
    {
        return $user->author?->id === $blog->author_id
            ? Response::allow()
            : Response::denyAsNotFound();
    }
}
