<?php

namespace Tests\Unit\Policies;

use App\Models\Post;
use App\Models\User;
use App\Enums\RoleEnum;
use Tests\TestCase;

class PostPolicyTest extends TestCase
{
    public function test_user_can_update_own_post()
    {
        $user = User::factory()->create();
        $post = Post::factory()->create(['user_id' => $user->id]);

        $this->assertTrue($user->can('update', $post));
    }

    public function test_admin_can_update_any_post()
    {
        $admin = User::factory()->asAdmin()->create();

        $post = Post::factory()->create();

        $this->assertTrue($admin->can('update', $post));
    }

    public function test_user_cannot_update_other_users_post()
    {
        $user = User::factory()->create();
        $post = Post::factory()->create();

        $this->assertFalse($user->can('update', $post));
    }
}
