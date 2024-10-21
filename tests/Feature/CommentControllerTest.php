<?php

namespace Tests\Feature;

use App\Enums\RoleEnum;
use App\Models\Role;
use App\Models\User;
use App\Models\Post;
use App\Models\Comment;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CommentControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected $user;
    protected $post;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
        $this->post = Post::factory()->create(['user_id' => $this->user->id]);
    }

    public function test_store_comment()
    {
        $commentData = ['comment' => $this->faker->sentence];

        $response = $this->actingAs($this->user)
            ->post(route('comments.store', $this->post->id), $commentData);

        $response->assertRedirect();
        $this->assertDatabaseHas('comments', [
            'comment' => $commentData['comment'],
            'user_id' => $this->user->id,
            'post_id' => $this->post->id,
        ]);
    }

    public function test_destroy_own_comment()
    {
        $comment = Comment::factory()->create([
            'user_id' => $this->user->id,
            'post_id' => $this->post->id,
        ]);

        $response = $this->actingAs($this->user)
            ->delete(route('comments.destroy', $comment->id));

        $response->assertRedirect();
        $this->assertDatabaseMissing('comments', ['id' => $comment->id]);
    }

    public function test_destroy_other_user_comment_as_post_owner()
    {
        $otherUser = User::factory()->create();
        $comment = Comment::factory()->create([
            'user_id' => $otherUser->id,
            'post_id' => $this->post->id,
        ]);

        $response = $this->actingAs($this->user)
            ->delete(route('comments.destroy', $comment->id));

        $response->assertRedirect();
        $this->assertDatabaseMissing('comments', ['id' => $comment->id]);
    }

    public function test_destroy_comment_as_admin()
    {
        $adminRole = Role::where('name', RoleEnum::ADMIN)->first() ?? Role::create(['name' => RoleEnum::ADMIN]);
        $adminUser = User::factory()->create();
        $adminUser->roles()->attach($adminRole);

        $comment = Comment::factory()->create([
            'user_id' => $this->user->id,
            'post_id' => $this->post->id,
        ]);
        $response = $this->actingAs($adminUser)
            ->delete(route('comments.destroy', $comment->id));

        $response->assertRedirect();
        $this->assertDatabaseMissing('comments', ['id' => $comment->id]);
    }

    public function test_cannot_destroy_other_user_comment()
    {
        $otherUser = User::factory()->asUser()->create();
        $otherPost = Post::factory()->create(['user_id' => $otherUser->id]);
        $comment = Comment::factory()->create([
            'user_id' => $otherUser->id,
            'post_id' => $otherPost->id,
        ]);
        $response = $this->actingAs($this->user)
            ->delete(route('comments.destroy', $comment->id));
        $response->assertForbidden();
        $this->assertDatabaseHas('comments', ['id' => $comment->id]);
    }
}
