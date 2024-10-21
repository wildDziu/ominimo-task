<?php

namespace Tests\Unit\Http\Controllers;

use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use App\Enums\RoleEnum;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CommentControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_store_comment()
    {
        $user = User::factory()->create();
        $post = Post::factory()->create();
        $this->actingAs($user);

        $data = [
            'comment' => 'This is a test comment.',
        ];

        $response = $this->post(route('comments.store', $post), $data);

        $this->assertDatabaseHas('comments', [
            'comment' => 'This is a test comment.',
            'user_id' => $user->id,
            'post_id' => $post->id,
        ]);

        $response->assertRedirect();
    }

    public function test_delete_comment()
    {
        $user = User::factory()->create();
        $post = Post::factory()->create();
        $comment = Comment::factory()->create([
            'post_id' => $post->id,
            'user_id' => $user->id,
        ]);

        $this->actingAs($user);

        $response = $this->delete(route('comments.destroy', $comment));

        $this->assertModelMissing($comment);

        $response->assertRedirect();
    }

    public function test_store_comment_validation_fails()
    {
        $user = User::factory()->create();
        $post = Post::factory()->create();
        $this->actingAs($user);

        $data = [
            'comment' => '', // Empty comment should fail validation
        ];

        $response = $this->post(route('comments.store', $post), $data);

        $response->assertSessionHasErrors('comment');
    }

    public function test_user_cannot_delete_others_comment()
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();
        $post = Post::factory()->create();
        $comment = Comment::factory()->create([
            'post_id' => $post->id,
            'user_id' => $otherUser->id,
        ]);

        $this->actingAs($user);

        $response = $this->delete(route('comments.destroy', $comment));

        $response->assertStatus(403);
        $this->assertModelExists($comment);
    }

    public function test_admin_can_delete_any_comment()
    {
        $admin = User::factory()->asAdmin()->create();
        $post = Post::factory()->create();
        $comment = Comment::factory()->create([
            'post_id' => $post->id,
        ]);

        $this->actingAs($admin);

        $response = $this->delete(route('comments.destroy', $comment));

        $this->assertModelMissing($comment);
        $response->assertRedirect();
    }
}
