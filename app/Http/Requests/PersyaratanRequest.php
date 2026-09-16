<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PersyaratanRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $rules = [
            'nama_persyaratan' => ['required', 'string', 'max:255'],
            'is_active' => ['nullable', 'boolean'],
        ];

        if ($this->isMethod('post')) {
            $rules['name'][] = 'unique:persyaratan,nama_persyaratan';
        } else {
            $id = $this->route('persyaratan') ? id_decode((string) $this->route('persyaratan')) : null;
            $rules['name'][] = Rule::unique('persyaratan', 'nama_persyaratan')->ignore($id);
        }

        return $rules;
    }
}
