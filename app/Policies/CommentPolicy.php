<?php

namespace App\Policies;

use App\Models\Comment;
use App\Models\User;
use App\Enums\RoleEnum;

class CommentPolicy
{
    public function delete(User $user, Comment $comment): bool
    {
        return $user->id === $comment->user_id
            || $user->id === $comment->post->user_id
            || $user->hasRole(RoleEnum::ADMIN);
    }
}
