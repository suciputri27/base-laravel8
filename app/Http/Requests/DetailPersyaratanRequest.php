<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class DetailPersyaratanRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $rules = [
            'pelayanan_id' => ['required', 'exists:pelayanan,id'],
            'persyaratan_id' => ['required', 'exists:persyaratan,id'],
            'berkas' => ['file', 'mimes:pdf,jpg,jpeg,png', 'max:2048'],
            'is_active' => ['nullable', 'boolean'],
        ];

        return $rules;
    }
}
