<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CategoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'is_active' => ['nullable', 'boolean'],
        ];

        if ($this->isMethod('post')) {
            $rules['name'][] = 'unique:categories,name';
        } else {
            $id = $this->route('category') ? id_decode((string) $this->route('category')) : null;
            $rules['name'][] = Rule::unique('categories', 'name')->ignore($id);
        }

        return $rules;
    }
}
