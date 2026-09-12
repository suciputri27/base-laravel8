<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PostRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $rules = [
            'category_id' => ['nullable', 'integer', 'exists:categories,id'],
            'title' => ['required', 'string', 'max:255'],
            'excerpt' => ['nullable', 'string'],
            'content' => ['required', 'string'],
            'thumbnail' => ['nullable', 'image', 'mimes:jpeg,png,webp,gif', 'max:2048'],
            'status' => ['required', 'in:draft,published'],
        ];

        if ($this->isMethod('post')) {
            $rules['title'][] = 'unique:posts,title';
        } else {
            $id = $this->route('post') ? id_decode((string) $this->route('post')) : null;
            $rules['title'][] = Rule::unique('posts', 'title')->ignore($id);
        }

        return $rules;
    }

    protected function prepareForValidation()
    {
        if ($this->has('category_id') && $this->filled('category_id')) {
            $this->merge(['category_id' => id_decode((string) $this->input('category_id'))]);
        }
    }
}
