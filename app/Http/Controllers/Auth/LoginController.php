<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; 
// use Illuminate\Validation\ValidationException;
use App\Rules\Recaptcha;

class LoginController extends Controller
{
    /**
     * Ipakita ang login view.
     */
    public function create()
    {
        return view('auth.login');
    }

    /**
     * Handle ang isang login request.
     */
    public function store(Request $request)
    {
        // 1. VALIDATION (Ihiwalay na natin)
        // Ito ay magpapatakbo ng validation, kasama ang reCAPTCHA.
        // Kung pumalya, awtomatiko itong hihinto.
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
            'g-recaptcha-response' => ['required', new Recaptcha],
        ]);

        // 2. KUNIN ANG CREDENTIALS
        // Ngayon, kunin lang natin ang 'email' at 'password' para sa Auth::attempt
        $credentials = $request->only('email', 'password');

        // 3. Subukang i-authenticate
        if (! Auth::attempt($credentials, $request->boolean('remember'))) {
            // Kung mali ang email o password
            return redirect()->back()->with(
                'error', 
                'Invalid credentials or your account is not yet verified.'
            );
        }

        // 4. Suriin kung VERIFIED ang user (Base sa plano)
        if (Auth::user()->email_verified_at === null) {
            // Kung HINDI pa verified
            Auth::logout(); // I-logout sila ulit
            
            return redirect()->route('login')->with(
                'error', // Gumamit tayo ng 'error' para iba ang kulay
                'Your account is not verified. Please check your email.'
            );
        }

        // 5. Kung pasado lahat: Login Success
        $request->session()->regenerate(); // Para sa security

        // I-redirect sa dashboard
        return redirect()->intended('dashboard');
    }
}