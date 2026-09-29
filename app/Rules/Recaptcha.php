<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Http;

class Recaptcha implements Rule
{
    protected float $threshold;
    protected ?float $score = null;

    public function __construct(float $threshold = 0.5)
    {
        $this->threshold = $threshold;
    }

    public function passes($attribute, $value): bool
    {
        if (empty($value)) {
            return false;
        }

        $response = Http::asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
            'secret' => config('services.recaptcha.secret_key'),
            'response' => $value,
            'remoteip' => request()->ip(),
        ]);

        $result = $response->json();

        $this->score = $result['score'] ?? 0;

        return ($result['success'] ?? false) && $this->score >= $this->threshold;
    }

    public function message(): string
    {
        return 'Verifikasi keamanan gagal, silakan coba lagi.';
    }
}