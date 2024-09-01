<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class PostUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool {
        return Auth::check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array {
        return [
            'user_id' => ['required', 'integer', 'exists:users,id'],
            'category_id' => ['required', 'integer', 'exists:post_categories,id'],
            'title' => ['required', 'string', 'max:100'],
            'forewords' => ['nullable', 'string'],
            'content' => ['required', 'string'],
            'thumbnail' => ['nullable', 'string'],
            'published' => ['boolean'],
            'media' => ['nullable', 'array', 'max:10'],
            'new_thumbnail' => ['nullable', 'image', 'mimes:jpeg,jpg,png,svg', 'max:10240'],
            'new_media' => ['nullable', 'array', 'max:10'],
            'new_media.*' => ['nullable', 'image', 'mimes:jpeg,jpg,png,svg', 'max:10240'],
            'media_marked_for_removal' => ['nullable', 'array'],
            'media_marked_for_removal.*' => ['nullable', 'string'],
        ];
    }
}
