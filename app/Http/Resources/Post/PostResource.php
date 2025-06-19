<?php

namespace App\Http\Resources\Post;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PostResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'content' => $this->content,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'user' => [
                'id' => $this->user->id,
                'name' => $this->user->name,
                'username' => $this->user->username,
            ],
            'images' => $this->images->map(function ($image) {
                return [
                    'id' => $image->id,
                    'image_url' => $this->getImageUrl($image),
                ];
            }),
        ];
    }

    private function getImageUrl($image)
    {
        return asset('storage/' . $image->image_path);
    }
}
