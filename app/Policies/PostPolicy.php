<?php

namespace App\Policies;

use App\Post;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class PostPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }
    public function update(\App\User $user, Post $post)
    {
        return $post->user_id == $user->id;
    }

    public function delete(\App\User $user, \App\Post $post)
    {
        return $post->user_id == $user->id;
    }
}
