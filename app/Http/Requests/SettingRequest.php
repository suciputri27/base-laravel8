<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SettingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nama_website' => ['required', 'string', 'max:255'],
            'tentang' => ['required', 'string'],
            'email' => ['nullable', 'email', 'max:255'],
            'alamat' => ['required', 'string'],
            'no_telepon' => ['nullable', 'string', 'regex:/^[0-9]{6,15}$/'],
            'no_whatsapp' => ['nullable', 'string', 'regex:/^[0-9]{8,16}$/'],
            'twitter' => ['nullable', 'string', 'max:255'],
            'facebook' => ['nullable', 'string', 'max:255'],
            'youtube' => ['nullable', 'string', 'max:255'],
            'tiktok' => ['nullable', 'string', 'max:255'],
        ];
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'no_telepon' => $this->cleanDigits($this->input('no_telepon')),
            'no_whatsapp' => $this->cleanWhatsapp($this->input('no_whatsapp')),
        ]);
    }

    protected function cleanDigits($value)
    {
        if (! is_string($value)) {
            return $value;
        }

        return preg_replace('/\D/', '', $value);
    }

    protected function cleanWhatsapp($value)
    {
        $digits = $this->cleanDigits($value);

        if (is_string($digits) && $digits !== '' && strpos($digits, '0') === 0) {
            $digits = '62' . substr($digits, 1);
        }

        return $digits;
    }
}
