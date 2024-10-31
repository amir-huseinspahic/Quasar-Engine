<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class PostStoreRequest extends FormRequest
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
            'title' => ['required', 'string', 'max:254'],
            'category_id' => ['required', 'integer', 'exists:post_categories,id'],
            'forewords' => ['nullable', 'string'],
            'content' => ['required', 'string'],
            'thumbnail' => ['nullable', 'image', 'mimes:jpeg,jpg,png,svg', 'max:10240'],
            'published' => ['required', 'boolean'],
            'media' => ['nullable', 'array', 'max:10'],
            'media.*' => ['nullable', 'image', 'mimes:jpeg,jpg,png,svg', 'max:10240'],
        ];
    }
}
