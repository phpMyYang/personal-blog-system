<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\Http;

class Recaptcha implements ValidationRule
{
    /**
     * Run the validation rule.
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        // 'g-recaptcha-response' ang pangalan ng field na ipinapasa ng Google
        // $value ay ang token mula sa form

        $response = Http::asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
            'secret' => config('services.recaptcha.secret'), 
            'response' => $value,
            // 'remoteip' => request()->ip(), // Opsyonal
        ]);

        $result = $response->json();

        // Kung ang 'success' ay false o may 'error-codes'
        if (!($result['success'] ?? false)) {
            $fail('The reCAPTCHA verification failed. Please try again.');
        }
    }
}