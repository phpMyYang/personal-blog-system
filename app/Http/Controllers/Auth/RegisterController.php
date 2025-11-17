<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Services\MailService; 
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Str; 
use App\Rules\Recaptcha; 
use Illuminate\Validation\ValidationException;

class RegisterController extends Controller
{
    /**
     * Ilagay dito ang ating MailService.
     */
    protected $mailService;

    /**
     * Constructor: Awtomatikong ipapasok ng Laravel ang MailService dito.
     */
    public function __construct(MailService $mailService)
    {
        $this->mailService = $mailService;
    }

    /**
     * Ipakita ang registration view.
     */
    public function create()
    {
        return view('auth.register');
    }

    /**
     * Handle ang isang registration request.
     */
    public function store(Request $request)
    {
        try {
            // 1. VALIDATION (Gawin sa loob ng try block)
            $validatedData = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => [
                    'required',
                    'confirmed',
                    Password::min(8)
                ],
                'g-recaptcha-response' => ['required', new Recaptcha],
                'terms' => 'required',
            ], [
                'terms.required' => 'You must agree to the Terms of Use and Privacy Policy.'
            ]);

        } catch (ValidationException $e) {
            // KUNIN ANG UNANG ERROR at gawing Toast
            $firstError = collect($e->errors())->flatten()->first();

            // I-redirect pabalik, ipadala ang error sa Toast, at ibalik ang input
            return redirect()->back()->withInput()->with('error', $firstError);
        }

        // 2. USER CREATION
        $user = User::create([
            'name' => $validatedData['name'],
            'email' => $validatedData['email'],
            'password' => Hash::make($validatedData['password']),
        ]);

        // 3. GUMAWA NG VERIFICATION TOKEN AT LINK
        $token = Str::random(60); // Gumawa ng secure, random token
        $user->verification_token = $token; // Ilagay ang token sa user
        $user->save();

        // Gumawa ng verification link gamit ang route name
        $verificationLink = route('auth.verify', ['token' => $token]);

        // 4. IPADALA ANG EMAIL GAMIT ANG MAILSERVICE
        $subject = "Verify your Blog Account";
        $body = "
            <html>
            <body>
                <h3>Hi {$user->name}, welcome to Personal Blog!</h3>
                <p>Click the link below to verify your account:</p>
                <a href='{$verificationLink}'>Verify Account</a>
                <p>If you did not create this account, you can safely ignore this email.</p>
            </body>
            </html>
        ";

        // Gamitin ang service na ginawa
        $this->mailService->sendEmail($user->email, $user->name, $subject, $body);

        // 5. REDIRECT TO VERIFY PAGE
        // Ilagay ang email sa session para magamit sa verify page
        session(['verification_email' => $user->email]);

        return redirect()->route('verification.notice')->with(
            'success',
            'A fresh verification link has been sent to your email address.'
        );
    }
}