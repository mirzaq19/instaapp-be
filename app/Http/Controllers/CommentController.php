<?php

namespace App\Http\Controllers;

use App\Exceptions\NotFoundException;
use App\Http\Requests\Comment\CommentStoreRequest;
use App\Http\Resources\Comment\CommentResource;
use App\Models\Comment;
use App\Models\Post;
use Exception;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    public function index($postId)
    {
        try {
            $post = Post::find($postId);
            if (!$post) throw new NotFoundException('Post not found.');

            $comments = Comment::where('post_id', $postId)
                ->with('user:id,name')
                ->latest()
                ->get();

            return $this->successWithData([
                'comments' => CommentResource::collection($comments),
            ], 'Comments retrieved successfully.');
        } catch (Exception $e) {
            return $this->error($e);
        }
    }

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
