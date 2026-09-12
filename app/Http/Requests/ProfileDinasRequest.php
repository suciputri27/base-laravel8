<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProfileDinasRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $rules = [
            'nama_website' => ['required', 'string', 'max:255'],
            'tentang' => ['required', 'string'],     
            'alamat' => ['required', 'string']        

        ];

        if ($this->isMethod('post')) {
            $rules['name'][] = 'unique:profile_dinas,name';
        } else {
            $id = $this->route('profiledinas') ? id_decode((string) $this->route('profiledinas')) : null;
            $rules['name'][] = Rule::unique('profile_dinas', 'name')->ignore($id);
        }

        return $rules;
    }
}
