<?php

namespace Tests\Unit\Policies;

use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use App\Enums\RoleEnum;
use Tests\TestCase;

class CommentPolicyTest extends TestCase
{
    public function test_user_can_delete_own_comment()
    {
        $user = User::factory()->create();
        $comment = Comment::factory()->create(['user_id' => $user->id]);

        $this->assertTrue($user->can('delete', $comment));
    }

    public function test_admin_can_delete_any_comment()
    {
        $admin = User::factory()->asAdmin()->create();
        $comment = Comment::factory()->create();

        $this->assertTrue($admin->can('delete', $comment));
    }

    public function test_post_owner_can_delete_comment_on_their_post()
    {
        $user = User::factory()->create();
        $post = Post::factory()->create(['user_id' => $user->id]);
        $comment = Comment::factory()->create(['post_id' => $post->id]);

        $this->assertTrue($user->can('delete', $comment));
    }

    public function test_user_cannot_delete_other_users_comment_on_other_posts()
    {
        $user = User::factory()->create();
        $comment = Comment::factory()->create();

        $this->assertFalse($user->can('delete', $comment));
    }
}
