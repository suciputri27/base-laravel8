<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PublikasiRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $rules = [
            'judul' => ['nullable', 'string', 'max:255'],
            'jenis_dokumen' => ['required', 'integer'],
            'deskripsi' => ['nullable', 'string'],
            'berkas' => ['file', 'mimes:pdf,jpg,jpeg,png', 'max:2048'],
            'is_active' => ['nullable', 'boolean'],
        ];

        // if ($this->isMethod('publikasi')) {
        //     $rules['judul'][] = 'unique:publikasi,judul';
        // } else {
        //     $id = $this->route('post') ? id_decode((string) $this->route('publikasi')) : null;
        //     $rules['judul'][] = Rule::unique('publikasi', 'judul')->ignore($id);
        // }

        return $rules;
    }
}
