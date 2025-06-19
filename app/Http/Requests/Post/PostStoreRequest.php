<?php

namespace App\Http\Requests\Post;

use App\Http\Requests\BaseApiRequest;

class PostStoreRequest extends BaseApiRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'content' => 'required|string|max:1000',
            'images' => 'required|array',
            'images.*' => 'required|image|mimes:jpeg,png,jpg|max:4096', // Maximum size of 4MB
        ];
    }
}
