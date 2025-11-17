<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use App\Models\Comment; 
use App\Services\MailService; 
use Illuminate\Support\Str; 

class CommentController extends Controller
{
    protected $mailService;

    // I-inject ang MailService
    public function __construct(MailService $mailService)
    {
        $this->mailService = $mailService;
    }

    /**
     * I-save ang bagong comment sa database.
     */
    public function store(Request $request, Post $post)
    {
        $rules = [ 'content' => 'required|string|max:1000', ];

        $commentData = [];
        $is_guest = Auth::guest();

        if ($is_guest) {
            // Kung guest, i-require ang name at email
            $rules['guest_name'] = 'required|string|max:255';
            $rules['guest_email'] = 'required|email|max:255';
        }

        try {
            $validated = $request->validate($rules);
        } catch (ValidationException $e) {
            $firstError = collect($e->errors())->flatten()->first();
            return redirect()->back()->withInput()->with('error', $firstError);
        }

        // Ihanda ang data
        $commentData['content'] = $validated['content'];

        if (Auth::check()) {
            // --- LOGIC PARA SA LOGGED-IN USER ---
            $commentData['user_id'] = Auth::id();
            $commentData['is_verified'] = true; // Auto-verified
        } else {
            // --- LOGIC PARA SA GUEST ---
            $commentData['guest_name'] = $validated['guest_name'];
            $commentData['guest_email'] = $validated['guest_email'];
            $commentData['is_verified'] = false; // Kailangan i-verify
            $commentData['verification_token'] = Str::random(60);
        }

        // I-save ang comment
        $comment = $post->comments()->create($commentData);

        // Kung guest, magpadala ng verification email
        if ($is_guest) {
            $this->sendVerificationEmail($comment);
            return redirect()->back()->with('success', 'Comment submitted. Please check your email to verify it.');
        }

        // Kung logged-in user, success na agad
        return redirect()->back()->with('success', 'Comment posted successfully!');
    }

    /**
     * Magpadala ng email sa guest.
     */
    protected function sendVerificationEmail(Comment $comment)
    {
        $verificationLink = route('comments.verify', ['token' => $comment->verification_token]);

        $subject = "Verify Your Comment on Personal Blog";
        $body = "
            <html><body>
                <h3>Hi {$comment->guest_name},</h3>
                <p>Thank you for your comment. Please click the link below to verify it and make it public:</p>
                <a href='{$verificationLink}'>Verify Comment</a>
            </body></html>
        ";

        $this->mailService->sendEmail($comment->guest_email, $comment->guest_name, $subject, $body);
    }

    /**
     * Handle ang pag-click sa verification link.
     */
    public function verify(string $token)
    {
        $comment = Comment::where('verification_token', $token)->first();

        if (!$comment) {
            // Kung invalid ang token
            return redirect()->route('home')->with('error', 'Invalid verification link.');
        }

        // I-verify ang comment
        $comment->is_verified = true;
        $comment->verification_token = null; // Burahin ang token
        $comment->save();

        // Ibalik sa post na may success message
        return redirect()->route('posts.show', $comment->post_id)
                         ->with('success', 'Your comment has been verified and is now public!');
    }
}