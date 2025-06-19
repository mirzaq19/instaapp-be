<?php

namespace App\Http\Controllers;

use App\Exceptions\AuthorizationException;
use App\Exceptions\InvariantException;
use App\Exceptions\NotFoundException;
use App\Http\Requests\Post\PostStoreRequest;
use App\Http\Resources\Post\PostResource;
use App\Models\Post;
use App\Models\PostImage;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PostController extends Controller
{
    public function index()
    {
        try {
            $posts = Post::with(['user', 'images'])->latest()->paginate(10);
            $postResource = PostResource::collection($posts)->response()->getData(true);
            return $this->successWithData([
                'posts' => $postResource['data'],
                'pagination' => [
                    'current_page' => $posts->currentPage(),
                    'last_page' => $posts->lastPage(),
                    'per_page' => $posts->perPage(),
                    'total' => $posts->total(),
                ],
            ], 'Posts retrieved successfully.');
        } catch (Exception $e) {
            return $this->error($e);
        }
    }

    public function store(PostStoreRequest $request)
    {
        try {
            DB::beginTransaction();
            $userId = Auth::id();
            $post = Post::create([
                'user_id' => $userId,
                'content' => $request->input('content'),
            ]);

            if ($request->hasFile('images')) {
                /* Validate and store images using Storage facade
                    image filename using format: {user_id}_{post_id}_{timestamp}_{order}.extension
                    where order is the index of the image in the array
                    e.g., 1_123_20230619135700_0.jpg
                */
                $images = $request->file('images');
                $imageData = [];
                $currentTime = now();
                $imagePath = 'posts/' . $post->id;
                foreach ($images as $index => $image) {
                    $imageName = sprintf(
                        '%d_%d_%s_%d.%s',
                        $userId,
                        $post->id,
                        $currentTime->timestamp,
                        $index,
                        $image->getClientOriginalExtension()
                    );
                    $image->storeAs($imagePath, $imageName, 'public');

                    $imageData[] = [
                        'post_id' => $post->id,
                        'image_path' => $imagePath . '/' . $imageName,
                        'image_name' => $imageName,
                        'image_type' => $image->getClientMimeType(),
                        'created_at' => $currentTime,
                        'updated_at' => $currentTime,
                    ];
                }

                if (empty($imageData)) throw new InvariantException("Failed to store images for the post.");

                PostImage::insert($imageData);
            }

            DB::commit();
            return $this->successWithData([
                'post_id' => $post->id,
            ], 'Post created successfully with images.', 201);
        } catch (Exception $e) {
            DB::rollBack();
            return $this->error($e);
        }
    }

    public function show(int $id)
    {
        try {
            $post = Post::with(['user', 'images'])->find($id);
            if (!$post) throw new NotFoundException("Post not found.");
            return $this->successWithData([
                'post' => new PostResource($post),
            ], 'Post retrieved successfully.');
        } catch (Exception $e) {
            return $this->error($e);
        }
    }

    public function destroy(int $id)
    {
        try {
            DB::beginTransaction();
            $post = Post::find($id);
            if (!$post) throw new NotFoundException("Post not found.");

            // Check if the authenticated user is the owner of the post
            if ($post->user_id !== Auth::id()) {
                throw new AuthorizationException("You are not authorized to delete this post.");
            }

            // Delete associated images
            $post->images()->each(function ($image) {
                $image->delete();
            });

            foreach ($post->images as $image) {
                if (file_exists(public_path('storage/' . $image->image_path))) {
                    unlink(public_path('storage/' . $image->image_path));
                }
            }

            // Delete the post
            $post->delete();

            DB::commit();
            return $this->success('Post deleted successfully.');
        } catch (Exception $e) {
            DB::rollBack();
            return $this->error($e);
        }
    }
}
