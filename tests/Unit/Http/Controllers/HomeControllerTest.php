<?php

namespace Tests\Unit\Http\Controllers;

use App\Models\Post;
use App\Models\Comment;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HomeControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_index_returns_correct_view()
    {
        $response = $this->get(route('home'));

        $response->assertStatus(200);
        $response->assertSee('Home');
    }

    public function test_index_returns_correct_data_for_guest()
    {
        $response = $this->get(route('home'));

        $props = $response->viewData('page')['props'];

        $this->assertEquals(false, $props['isLoggedIn']);
        $this->assertEquals(route('login'), $props['loginLink']);
        $this->assertEquals(route('register'), $props['registerLink']);
        $this->assertEquals(route('posts.index'), $props['postsLink']);
    }

    public function test_index_returns_correct_data_for_authenticated_user()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->get(route('home'));
        $isLoggedIn = $response->viewData('page')['props']['isLoggedIn'];
        $this->assertEquals(true, $isLoggedIn);
    }

    public function test_index_returns_recent_posts()
    {
        Post::factory()->count(10)->create();

        $response = $this->get(route('home'));

        $recentPosts = $response->viewData('page')['props']['recentPosts'];
        $this->assertCount(5, $recentPosts);
        $this->assertEquals(Post::latest()->take(5)->pluck('id')->toArray(), array_column($recentPosts, 'id'));
    }

    public function test_index_returns_recent_comments()
    {
        Comment::factory()->count(10)->create();

        $response = $this->get(route('home'));
        $recentComments = $response->viewData('page')['props']['recentComments'];
        $this->assertCount(5, $recentComments);
        $this->assertEquals(Comment::latest()->take(5)->pluck('id')->toArray(), array_column($recentComments, 'id'));
    }
}
