<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // Para makuha ang user

class DashboardController extends Controller
{
    /**
     * Ipakita ang dashboard homepage.
     */
    public function index(Request $request)
    {
        // 1. Kunin ang naka-log in na user (gamit ang $request)
        $user = $request->user();

        // 2. Kunin ang LAHAT ng posts ng user na 'yon, pinakabago muna
        $posts = $user->posts()->latest()->get(); // 'latest()' ay 'order by created_at DESC'

        // 3. Kwentahin ang totoong stats
        $totalPosts = $posts->count();
        $publishedPosts = $posts->where('is_published', true)->count();
        $drafts = $posts->where('is_published', false)->count();

        // 4. Ipadala ang data sa view
        return view('dashboard.index', [
            'totalPosts' => $totalPosts,
            'publishedPosts' => $publishedPosts,
            'drafts' => $drafts,
            'posts' => $posts, // Ipadala ang listahan ng posts
        ]);
    }
}