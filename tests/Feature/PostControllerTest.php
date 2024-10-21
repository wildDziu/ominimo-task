<?php

namespace Tests\Feature;

use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class PostControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
    }

    public function test_index_displays_posts()
    {
        Post::query()->delete();
        Post::factory(5)->create();

        $response = $this->get(route('posts.index'));
        $response->assertStatus(200)
            ->assertInertia(fn($assert) => $assert
                ->component('Posts/Index')
                ->has('posts.data', 5)
            );
    }

    public function test_create_returns_correct_view()
    {
        $response = $this->actingAs($this->user)->get(route('posts.create'));

        $response->assertStatus(200)
            ->assertInertia(fn($assert) => $assert
                ->component('Posts/Create')
            );
    }

    public function test_store_creates_new_post()
    {
        $postData = [
            'title' => $this->faker->sentence,
            'content' => $this->faker->paragraph,
        ];

        $response = $this->actingAs($this->user)
            ->post(route('posts.store'), $postData);

        $response->assertRedirect();
        $this->assertDatabaseHas('posts', $postData);
    }

    public function test_show_displays_post()
    {
        $post = Post::factory()->create();

        $response = $this->get(route('posts.show', $post));

        $response->assertStatus(200)
            ->assertInertia(fn($assert) => $assert
                ->component('Posts/Show')
                ->has('post')
                ->where('post.id', $post->id)
            );
    }

    public function test_edit_returns_correct_view()
    {
        $post = Post::factory()->create(['user_id' => $this->user->id]);

        $response = $this->actingAs($this->user)
            ->get(route('posts.edit', $post));

        $response->assertStatus(200)
            ->assertInertia(fn($assert) => $assert
                ->component('Posts/Edit')
                ->has('post')
                ->where('post.id', $post->id)
            );
    }

    public function test_update_modifies_post()
    {
        $post = Post::factory()->create(['user_id' => $this->user->id]);
        $updatedData = [
            'title' => $this->faker->sentence,
            'content' => $this->faker->paragraph,
        ];

        $response = $this->actingAs($this->user)
            ->put(route('posts.update', $post), $updatedData);

        $response->assertRedirect();
        $this->assertDatabaseHas('posts', $updatedData);
    }

    public function test_destroy_deletes_post()
    {
        $post = Post::factory()->create(['user_id' => $this->user->id]);

        $response = $this->actingAs($this->user)
            ->delete(route('posts.destroy', $post));

        $response->assertRedirect(route('posts.index'));
        $this->assertDatabaseMissing('posts', ['id' => $post->id]);
    }

    public function test_unauthorized_user_cannot_edit_post()
    {
        $post = Post::factory()->create();

        $response = $this->actingAs($this->user)
            ->get(route('posts.edit', $post));

        $response->assertForbidden();
    }

    public function test_unauthorized_user_cannot_update_post()
    {
        $post = Post::factory()->create();
        $updatedData = [
            'title' => $this->faker->sentence,
            'content' => $this->faker->paragraph,
        ];

        $response = $this->actingAs($this->user)
            ->put(route('posts.update', $post), $updatedData);

        $response->assertForbidden();
    }

    public function test_unauthorized_user_cannot_delete_post()
    {
        $post = Post::factory()->create();

        $response = $this->actingAs($this->user)
            ->delete(route('posts.destroy', $post));

        $response->assertForbidden();
    }
}
