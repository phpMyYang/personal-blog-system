<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // Para makuha ang logged-in user
use App\Models\Post; 
use Illuminate\Support\Str; // Para sa pag-handle ng file names
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Validation\ValidationException;
use App\Models\Category;

class PostController extends Controller
{
    use AuthorizesRequests;

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Kunin lahat ng categories, naka-sort alphabetically
        $categories = Category::orderBy('name')->get(); 

        return view('dashboard.posts.create', [
            'categories' => $categories 
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try{
            // 1. Validation (Base sa Seksyon 6.1 ng plano mo)
            $validated = $request->validate([
                'title' => 'required|string|max:255',
                'content' => 'required|string',
                'featured_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'categories' => 'nullable|array',
                'categories.*' => 'exists:categories,id'
                // 'is_published' ay 'nullable'
            ]);
            } catch (ValidationException $e) {
            $firstError = collect($e->errors())->flatten()->first();
            return redirect()->back()->withInput()->with('error', $firstError);
        }

        $imagePath = null;

        // 2. Handle ang File Upload
        if ($request->hasFile('featured_image')) {
            // I-save ang image sa 'storage/app/public/featured_images'
            // Ang 'public' ay nangangahulugang gagamitin natin ang 'public' disk
            $imagePath = $request->file('featured_image')->store('featured_images', 'public');
        }

        // 3. I-save ang Post sa Database
        // Gagamitin ang relationship para awtomatikong makuha ang user_id
        $request->user()->posts()->create([
            'title' => $validated['title'],
            'content' => $validated['content'],
            'featured_image' => $imagePath,
            // Kung naka-check, magiging 'true'. Kung hindi, 'false'.
            'is_published' => $request->has('is_published'),
        ]);

        // 4. Bumalik sa Dashboard na may success message [cite: 79]
        return redirect()->route('dashboard')->with('success', 'Post saved successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Post $post)
    {
        $this->authorize('update', $post);

        // Kunin lahat ng categories
        $categories = Category::orderBy('name')->get(); 

        return view('dashboard.posts.edit', [
            'post' => $post,
            'categories' => $categories
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Post $post)
    {
        // 1. Titingnan kung ang user ang may-ari ng post
        $this->authorize('update', $post);

        try {
            // 2. Validation
            $validated = $request->validate([
                'title' => 'required|string|max:255',
                'content' => 'required|string',
                'featured_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'categories' => 'nullable|array', 
                'categories.*' => 'exists:categories,id' 
            ]);
            } catch (ValidationException $e) {
            $firstError = collect($e->errors())->flatten()->first();
            return redirect()->back()->withInput()->with('error', $firstError);
        }

        // 3. I-update ang post title at content
        $post->title = $validated['title'];
        $post->content = $validated['content'];
        $post->is_published = $request->has('is_published');

        // 4. Handle ang Image

        // Kung pinindot ang "Remove Image" checkbox
        if ($request->has('remove_image')) {
            if ($post->featured_image) {
                Storage::disk('public')->delete($post->featured_image);
                $post->featured_image = null;
            }
        }

        // Kung may bagong image na in-upload
        if ($request->hasFile('featured_image')) {
            // Burahin ang luma kung meron
            if ($post->featured_image) {
                Storage::disk('public')->delete($post->featured_image);
            }
            // I-save ang bago
            $imagePath = $request->file('featured_image')->store('featured_images', 'public');
            $post->featured_image = $imagePath;
        }

        // 5. I-save ang mga pagbabago
        $post->save();

        // I-sync ang categories (tatanggalin ang luma, idadagdag ang bago)
        if (isset($validated['categories'])) {
            $post->categories()->sync($validated['categories']);
        } else {
            // Kung walang pinili, tanggalin lahat ng categories
            $post->categories()->sync([]);
        }

        return redirect()->route('dashboard')->with('success', 'Post updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post)
    {
        // 1. Titingnan kung ang user ang may-ari ng post
        $this->authorize('delete', $post);

        // 2. Burahin ang image sa storage kung meron
        if ($post->featured_image) {
            Storage::disk('public')->delete($post->featured_image);
        }

        // 3. Burahin ang post sa database
        $post->delete();

        return redirect()->route('dashboard')->with('success', 'Post deleted successfully.');
    }
}
