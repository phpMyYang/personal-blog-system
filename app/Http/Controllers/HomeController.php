<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post; 
use App\Models\Category;

class HomeController extends Controller
{
    /**
     * Ipakita ang homepage na may listahan ng published posts.
     */
    public function index(Request $request)
    {
        // 1. Kunin ang query parameters mula sa URL
        $search = $request->input('search');
        $categorySlug = $request->input('category');

        // 2. Kunin ang lahat ng categories na mayroon man lang isang PUBLISHED post
        $categories = Category::whereHas('posts', function ($query) {
            $query->where('is_published', true);
        })->orderBy('name')->get();

        // 3. Simulan ang query para sa published posts
        $postsQuery = Post::with('user')
                        ->where('is_published', true);

        // 4. I-filter base sa Search Term (kung meron)
        if ($search) {
            $postsQuery->where(function($query) use ($search) {
                $query->where('title', 'like', "%{$search}%")
                    ->orWhere('content', 'like', "%{$search}%");
            });
        }

        // 5. I-filter base sa Category (kung meron)
        $currentCategory = null;
        if ($categorySlug) {
            // Hanapin ang category gamit ang slug
            $currentCategory = Category::where('slug', $categorySlug)->first();

            if ($currentCategory) {
                // Gamitin ang 'whereHas' para i-filter ang posts
                $postsQuery->whereHas('categories', function ($query) use ($currentCategory) {
                    $query->where('category_id', $currentCategory->id);
                });
            }
        }

        // 6. Kunin ang pinal na resulta
        $posts = $postsQuery->latest()->get();

        // 7. Ipadala ang lahat ng data sa view
        return view('home', [
            'posts' => $posts,
            'categories' => $categories,
            'currentCategory' => $currentCategory,
            'search' => $search
        ]);
    }

    /**
     * Ipakita ang isang buong post.
     */
    public function show(Post $post)
    {
        if (!$post->is_published) {
            abort(404);
        }

        // I-load ang 'user' (author) at 'comments.user' (commenters)
        $post->load(['user', 'comments.user']);

        return view('post', ['post' => $post]);
    }
}