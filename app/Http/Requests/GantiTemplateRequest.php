<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class GantiTemplateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'detail_id' => id_decode((string) $this->route('detail')),
        ]);
    }
    

    public function rules(): array
    {
        $rules = [
            'detail_id' => ['required', 'integer', 'exists:detail_persyaratan,id'],
            'berkas' => ['required', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:2048'],
        ];

        return $rules;
    }
}
