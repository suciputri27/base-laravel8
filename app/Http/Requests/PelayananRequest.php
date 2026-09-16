<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PelayananRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $rules = [
            'nama_pelayanan' => ['required', 'string', 'max:255'],
            'deskripsi' => ['nullable', 'string'],
            'is_active' => ['nullable', 'boolean'],
        ];

        if ($this->isMethod('post')) {
            $rules['name'][] = 'unique:pelayanan,nama_pelayanan';
        } else {
            $id = $this->route('pelayanan') ? id_decode((string) $this->route('pelayanan')) : null;
            $rules['name'][] = Rule::unique('categories', 'nama_pelayanan')->ignore($id);
        }

        return $rules;
    }
}
