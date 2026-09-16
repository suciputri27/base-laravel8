<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StrukturOrganisasiRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $rules = [
            'berkas' => ['nullable', 'image', 'mimes:jpeg,png,webp,gif', 'max:2048'],
            'is_active' => ['nullable', 'boolean']
        ];
    
        return $rules;
    }
}
