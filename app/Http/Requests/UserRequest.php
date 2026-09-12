<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'role' => ['nullable', 'string'],
            'password' => ['nullable', 'string', 'min:8'],
        ];

        if ($this->isMethod('post')) {
            $rules['email'] = ['required', 'email', 'unique:users,email'];
            $rules['password'] = ['required', 'string', 'min:8'];
        } else {
            $userId = $this->route('user') ? id_decode((string) $this->route('user')) : null;
            $rules['email'] = ['required', 'email', Rule::unique('users', 'email')->ignore($userId)];
        }

        return $rules;
    }
}
