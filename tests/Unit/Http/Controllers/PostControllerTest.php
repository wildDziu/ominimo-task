<?php

namespace Tests\Unit\Http\Controllers;

use App\Models\Post;
use App\Models\User;
use App\Enums\RoleEnum;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PostControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_index_displays_posts()
    {
        $this->actingAs(User::factory()->create());

        Post::factory()->count(3)->create();

        $response = $this->get(route('posts.index'));

        $response->assertStatus(200);
        $response->assertSee('Posts');
    }

    public function test_create_returns_view()
    {
        $this->actingAs(User::factory()->create());

        $response = $this->get(route('posts.create'));

        $response->assertStatus(200);
    }

    public function test_show_displays_post()
    {
        $this->actingAs(User::factory()->create());
        $post = Post::factory()->create();

        $response = $this->get(route('posts.show', $post));

        $response->assertStatus(200);
        $response->assertSee($post->title);
    }

    public function test_store_creates_new_post()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $data = [
            'title' => 'Sample Post Title',
            'content' => 'Sample Post Content',
        ];

        $response = $this->post(route('posts.store'), $data);

        $this->assertDatabaseHas('posts', [
            'title' => 'Sample Post Title',
            'content' => 'Sample Post Content',
        ]);

        $response->assertRedirect(route('posts.show', Post::first()->id));
    }

    public function test_update_post()
    {
        $user = User::factory()->create();
        $post = Post::factory()->create(['user_id' => $user->id]);
        $this->actingAs($user);

        $updatedData = [
            'title' => 'Updated Title',
            'content' => 'Updated Content',
        ];

        $response = $this->put(route('posts.update', $post), $updatedData);

        $post->refresh();
        $this->assertEquals('Updated Title', $post->title);
        $this->assertEquals('Updated Content', $post->content);

        $response->assertRedirect(route('posts.show', $post->id));
    }

    public function test_delete_post()
    {
        $user = User::factory()->create();
        $post = Post::factory()->create(['user_id' => $user->id]);
        $this->actingAs($user);

        $response = $this->delete(route('posts.destroy', $post));

        $this->assertModelMissing($post);

        $response->assertRedirect(route('posts.index'));
    }

    public function test_user_cannot_update_others_post()
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();
        $post = Post::factory()->create(['user_id' => $otherUser->id]);
        $this->actingAs($user);

        $updatedData = [
            'title' => 'Updated Title',
            'content' => 'Updated Content',
        ];

        $response = $this->put(route('posts.update', $post), $updatedData);

        $response->assertStatus(403);
    }

    public function test_admin_can_update_any_post()
    {
        $admin = User::factory()->asAdmin()->create();
        $post = Post::factory()->create();
        $this->actingAs($admin);

        $updatedData = [
            'title' => 'Updated by Admin',
            'content' => 'Updated Content',
        ];

        $response = $this->put(route('posts.update', $post), $updatedData);

        $post->refresh();
        $this->assertEquals('Updated by Admin', $post->title);
        $response->assertRedirect(route('posts.show', $post->id));
    }
}
