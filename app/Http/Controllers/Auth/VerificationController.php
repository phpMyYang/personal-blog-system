<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Services\MailService; // Para sa PHPMailer
use Illuminate\Support\Str;      // Para sa paggawa ng bagong token
use Illuminate\Support\Carbon;   // Para sa timestamp checking

class VerificationController extends Controller
{
    protected $mailService;

    /**
     * Constructor - Ipasok ang MailService para magamit sa pag-resend.
     */
    public function __construct(MailService $mailService)
    {
        $this->mailService = $mailService;
    }

    /**
     * Handle ang email verification request (kapag na-click ang link sa email).
     */
    public function verify(Request $request)
    {
        // 1. Kunin ang token mula sa URL
        $token = $request->query('token');

        // 2. Hanapin ang user na may-ari ng token na iyon
        $user = User::where('verification_token', $token)->first();

        // 3. Kung 'di mahanap o invalid ang token
        if (!$user) {
            // I-redirect sa login na may error
            return redirect()->route('login')->with('error', 'Verification failed. The link is invalid or expired.');
        }

        // 4. Kung mahanap, i-verify ang user
        $user->email_verified_at = Carbon::now(); // Itakda ang 'verified' status
        $user->verification_token = null; // Burahin ang token para 'di na magamit
        $user->save();

        // 5. I-redirect sa login na may success message
        return redirect()->route('login')->with('success', 'Your account has been verified successfully. You can now login.');
    }

    /**
     * Ipakita ang "Verify Email" advisory page pagkatapos mag-register.
     */
    public function showNoticePage()
    {
        // Kunin ang email mula sa session na nilagay ng RegisterController
        if (!session('verification_email')) {
            // Kung walang email (hal. na-refresh ang page o direktang pumunta dito), ibalik sa login
            return redirect()->route('login');
        }
        return view('auth.verify');
    }

    /**
     * Handle ang "Resend" verification email request.
     */
    public function resendVerificationEmail(Request $request)
    {
        $request->validate(['email' => 'required|email']);
        $user = User::where('email', $request->email)->first();

        // Kung may user at HINDI pa verified
        if ($user && !$user->email_verified_at) {
            
            // Gumawa ng bagong token
            $token = Str::random(60);
            $user->verification_token = $token;
            $user->save();

            $verificationLink = route('auth.verify', ['token' => $token]);

            // Ipadala ang email gamit ang MailService
            $subject = "Verify your Blog Account (New Link)";
            $body = "
                <html><body>
                    <h3>Hi {$user->name},</h3>
                    <p>You requested a new verification link. Click the link below:</p>
                    <a href='{$verificationLink}'>Verify Account</a>
                </body></html>
            ";
            
            $this->mailService->sendEmail($user->email, $user->name, $subject, $body);

            // Ibalik sa verify page na may success message
            return redirect()->route('verification.notice')->with('success', 'A new verification link has been sent to your email.');
        }

        // Kung verified na sila, ipadala na lang sa login
        if ($user && $user->email_verified_at) {
            return redirect()->route('login')->with('success', 'Your account is already verified. You can login.');
        }

        // Kung walang user (o nag-iba ang email), ibalik sa login
        return redirect()->route('login');
    }
}