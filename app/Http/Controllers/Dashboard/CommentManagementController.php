<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Comment; 
use Illuminate\Http\Request;

class CommentManagementController extends Controller
{
    /**
     * Ipakita ang lahat ng comments.
     */
    public function index()
    {
        // Kunin ang LAHAT ng comments (pati unverified)
        // I-load ang 'user' (kung meron) at 'post' (para sa post title)
        $comments = Comment::with(['user', 'post'])->latest()->get();

        return view('dashboard.comments.index', ['comments' => $comments]);
    }

    /**
     * I-approve ang isang comment.
     */
    public function approve(Comment $comment)
    {
        $comment->is_verified = true;
        $comment->verification_token = null;
        $comment->save();

        return redirect()->route('dashboard.comments.index')->with('success', 'Comment has been approved.');
    }

    /**
     * Burahin ang isang comment.
     */
    public function destroy(Comment $comment)
    {
        $comment->delete();

        return redirect()->route('dashboard.comments.index')->with('success', 'Comment has been deleted.');
    }
}