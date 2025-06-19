<?php

namespace App\Http\Controllers;

use App\Exceptions\NotFoundException;
use App\Http\Requests\Comment\CommentStoreRequest;
use App\Models\Comment;
use App\Models\Post;
use Exception;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    public function store(CommentStoreRequest $request, $postId)
    {
        try {
            $validated = $request->validated();

            $post = Post::find($postId);
            if (!$post) throw new NotFoundException('Post not found.');

            $comment = Comment::create([
                'post_id' => $postId,
                'user_id' => Auth::id(),
                'content' => $validated['content'],
            ]);

            return $this->successWithData([
                'comment_id' => $comment->id,
            ], 'Comment created successfully.', 201);
        } catch (Exception $e) {
            return $this->error($e);
        }
    }
}
