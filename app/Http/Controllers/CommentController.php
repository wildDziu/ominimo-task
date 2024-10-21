<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Post;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CommentController extends Controller
{
    use AuthorizesRequests;
    use ValidatesRequests;

    public function __construct()
    {
        $this->middleware('auth')->only(['destroy']);
    }

    public function store(Request $request, Post $post)
    {
        $validated = $request->validate([
            'comment' => 'required',
        ]);

        $comment = new Comment([
            'comment' => $validated['comment'],
            'user_id' => auth()->id(),
            'post_id' => $post->id,
        ]);

        $post->comments()->save($comment);

        return back();
    }

    public function destroy(Comment $comment)
    {
        $this->authorize('delete', $comment);

        $comment->delete();

        return back();
    }
}
