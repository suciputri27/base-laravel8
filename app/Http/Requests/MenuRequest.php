<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MenuRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'icon' => ['nullable', 'string', 'max:255'],
            'route_or_url' => ['nullable', 'string', 'max:255'],
            'permission_name' => ['nullable', 'string', 'max:255'],
            'order_no' => ['nullable', 'integer'],
            'parent_id' => ['nullable', 'integer', 'exists:menus,id'],
            'is_active' => ['nullable', 'boolean'],
        ];
    }

    protected function prepareForValidation()
    {
        if ($this->has('parent_id') && $this->filled('parent_id')) {
            $this->merge(['parent_id' => id_decode((string) $this->input('parent_id'))]);
        }
    }
}
