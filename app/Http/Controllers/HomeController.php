<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Comment;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class HomeController extends Controller
{
    public function index()
    {
        $isLoggedIn = Auth::check();

        $data = [
            'isLoggedIn' => $isLoggedIn,
            'loginLink' => route('login'),
            'registerLink' => route('register'),
            'postsLink' => route('posts.index'),
        ];

        $recentPosts = Post::with('user')
            ->latest()
            ->take(5)
            ->get();

        $recentComments = Comment::with(['user', 'post'])
            ->latest()
            ->take(5)
            ->get();
        $data['recentPosts'] = $recentPosts;
        $data['recentComments'] = $recentComments;
        return Inertia::render('Home', $data);
    }
}
