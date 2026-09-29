<?php

namespace App\Http\Middleware;

use App\Rules\Recaptcha;
use Closure;
use Illuminate\Http\Request;

class VerifyRecaptcha
{
    public function handle(Request $request, Closure $next, $threshold = 0.5)
    {
        $rule = new Recaptcha((float) $threshold);

        if (! $rule->passes('recaptcha_token', $request->input('recaptcha_token'))) {
            return response()->json([
                'success' => false,
                'message' => $rule->message(),
            ], 422);
        }

        return $next($request);
    }
}