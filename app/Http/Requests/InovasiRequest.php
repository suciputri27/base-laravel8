<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class InovasiRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'judul'          => ['nullable', 'string', 'max:255'],
            'jenis'          => ['required', 'integer'],
            'deskripsi'      => ['nullable', 'string'],
            'is_active'      => ['nullable', 'boolean'],
            'berkas'         => ['nullable', 'array'],
            'berkas.*'       => ['image', 'mimes:jpeg,png,webp,gif', 'max:2048'],
            'deleted_berkas' => ['nullable', 'string'], // "1,2,3"
        ];
    }
}