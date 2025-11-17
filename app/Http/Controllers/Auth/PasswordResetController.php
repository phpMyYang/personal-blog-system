<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\MailService; // Para sa PHPMailer
use App\Models\User;
use App\Rules\Recaptcha;
use Illuminate\Support\Facades\DB; // Para sa 'password_reset_tokens' table
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash; 
use Illuminate\Validation\Rules\Password; 
use Illuminate\Validation\ValidationException;

class PasswordResetController extends Controller
{
    protected $mailService;

    public function __construct(MailService $mailService)
    {
        $this->mailService = $mailService;
    }

    /**
     * Ipakita ang "Forgot Password" form.
     */
    public function showForgotPasswordForm()
    {
        return view('auth.forgot-password');
    }

    /**
     * I-validate ang email, gumawa ng token, at ipadala ang reset link.
     */
    public function sendResetLinkEmail(Request $request)
    {
        try {
            // 1. Validation (kasama ang reCAPTCHA )
            $request->validate([
                'email' => 'required|email',
                'g-recaptcha-response' => ['required', new Recaptcha],
            ]);
        } catch (ValidationException $e) {
            // KUNIN ANG UNANG ERROR at gawing Toast (para sa maling email format, reCAPTCHA, atbp.)
            $firstError = collect($e->errors())->flatten()->first();
            return redirect()->back()->withInput()->with('error', $firstError);
        }

        // 2. Hanapin ang user at siguraduhin na verified
        $user = User::where('email', $request->email)->first();

        // 3. BAGO: Logic Failure (Walang User o Hindi Verified)
        if (!$user || $user->email_verified_at === null) {

            $errorMessage = "Account not found or email address is not verified.";

            // I-redirect pabalik na may Toast Error
            return redirect()->back()->withInput()->with('error', $errorMessage);
        }

            // Burahin ang lumang token kung meron
            DB::table('password_reset_tokens')->where('email', $user->email)->delete();

            // Gumawa ng bagong secure token
            $token = Str::random(60);

            // I-save ang token sa database
            DB::table('password_reset_tokens')->insert([
                'email' => $user->email,
                'token' => $token,
                'created_at' => Carbon::now()
            ]);

            // 4. Ipadala ang email gamit ang MailService (PHPMailer)
            $resetLink = route('password.reset.form', ['token' => $token]);

            $subject = "Reset Your Password";
            $body = "
                <html>
                <body>
                    <p>You requested to reset your password.</p>
                    <p>Click the link below to reset it:</p>
                    <a href='{$resetLink}'>Reset Password</a>
                    <p>This link will expire.</p>
                    <p>If you did not request this, you can ignore this email.</p>
                </body>
                </html>
            ";

            $this->mailService->sendEmail($user->email, $user->name, $subject, $body);

        // 5. Palaging magpakita ng success message (para sa security)
        return redirect()->route('password.forgot')->with(
            'success',
            'If a matching account was found, a password reset link has been sent to your email.'
        );
    }

    /**
     * Ipakita ang "Reset Password" form kapag na-click ang link.
     */
    public function showResetPasswordForm(Request $request, $token)
    {
        // 1. Hanapin ang token sa database
        $tokenRecord = DB::table('password_reset_tokens')->where('token', $token)->first();

        // 2. Kung wala ang token o expired na (lagyan natin ng 1 oras na expiry)
        if (!$tokenRecord || Carbon::parse($tokenRecord->created_at)->addMinutes(60)->isPast()) {

            // Burahin ang token kung expired na
            if ($tokenRecord) {
                DB::table('password_reset_tokens')->where('email', $tokenRecord->email)->delete();
            }

            return redirect()->route('login')->with('error', 'This password reset token is invalid or has expired.');
        }

        // 3. Kung valid, ipakita ang form
        return view('auth.reset-password', [
            'token' => $token,
            'email' => $tokenRecord->email
        ]);
    }

    /**
     * I-validate ang bagong password at i-update ito.
     */
    public function updatePassword(Request $request)
    {
        try {
            // 1. Validation (Handle maling password/format)
            $request->validate([
                'token' => 'required',
                'email' => 'required|email',
                'password' => [
                    'required',
                    'confirmed',
                    Password::min(8)
                ],
            ]);
        } catch (ValidationException $e) {
            // KUNIN ANG UNANG VALIDATION ERROR at gawing Toast
            $firstError = collect($e->errors())->flatten()->first();
            return redirect()->back()->withInput()->with('error', $firstError);
        }

        // 2. Muling i-check ang token at email
        $tokenRecord = DB::table('password_reset_tokens')
            ->where('email', $request->email)
            ->where('token', $request->token)
            ->first();

        // 3. Kung invalid ang token o expired na (Toast Error & Redirect to Login)
        if (!$tokenRecord || Carbon::parse($tokenRecord->created_at)->addMinutes(60)->isPast()) {
            // Burahin ang token kung meron
            if ($tokenRecord) {
                DB::table('password_reset_tokens')->where('email', $request->email)->delete();
            }
            return redirect()->route('login')->with('error', 'The reset link is invalid or has expired. Please request a new link.');
        }

        // 4. Hanapin ang user
        $user = User::where('email', $request->email)->first();

        // 5. Kung may user, i-update ang password
        if ($user) {
            $user->password = Hash::make($request->password);
            $user->save(); // I-save ang bagong password

            // 6. Burahin na ang token para 'di na magamit
            DB::table('password_reset_tokens')->where('email', $request->email)->delete();

            // 7. I-redirect sa login na may success message (Toast)
            return redirect()->route('login')->with('success', 'Your password has been reset successfully. You can now login.');
        }

        // 8. Final fallback error (bihi rang mangyari)
        return redirect()->route('login')->with('error', 'User account not found.');
    }
}