<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class JadwalLayananRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $rules = [
            'jenis'   => ['required', 'integer'],
            'day'     => ['required_if:jenis,1', 'nullable', 'string', 'max:255'],
            'tanggal' => ['required_if:jenis,2', 'nullable', 'date'],
            'tempat'  => ['required_if:jenis,2', 'nullable', 'string', 'max:255'],
            'open'    => ['required', 'date_format:H:i'],
            'close'   => ['required', 'date_format:H:i', 'after:open'],
        ];

        return $rules;
    }
}
