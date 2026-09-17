<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SyncDetailPersyaratanRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation()
    {
        $decodedIds = collect($this->input('persyaratan_ids', []))
            ->map(function ($id) {
                return id_decode((string) $id);
            })
            ->filter()
            ->values()
            ->toArray();

        $this->merge([
            'pelayanan_id' => id_decode((string) $this->route('pelayanan')),
            'persyaratan_ids' => $decodedIds,
        ]);
    }

    public function rules(): array
    {
        return [
            'pelayanan_id' => ['required', 'integer', 'exists:pelayanan,id'],
            'persyaratan_ids' => ['nullable', 'array'],
            'persyaratan_ids.*' => ['integer', 'exists:persyaratan,id'],
        ];
    }
}