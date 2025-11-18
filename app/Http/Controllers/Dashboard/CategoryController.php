<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str; // Para sa paggawa ng slug
use Illuminate\Validation\Rule; // Para sa unique check
use Illuminate\Validation\ValidationException;

class CategoryController extends Controller
{
    /**
     * Ipakita ang listahan ng categories at ang create form.
     */
    public function index()
    {
        // Kunin lahat ng categories, at i-load ang count ng posts
        $categories = Category::withCount('posts')->get();
        return view('dashboard.categories.index', ['categories' => $categories]);
    }

    /**
     * I-save ang bagong category.
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255|unique:categories',
            ]);
        } catch (ValidationException $e) {
            return redirect()->back()->withInput()->with('error', 'A category with this name already exists.');
        }

        Category::create([
            'name' => $validated['name'],
            'slug' => Str::slug($validated['name']),
        ]);

        return redirect()->route('dashboard.categories.index')->with('success', 'Category created successfully.');
    }

    /**
     * I-update ang category.
     */
    public function update(Request $request, Category $category)
    {
        try {
            $validated = $request->validate([
                'name' => ['required', 'string', 'max:255', Rule::unique('categories')->ignore($category->id)],
                'slug' => ['required', 'string', 'max:255', Rule::unique('categories')->ignore($category->id)],
            ]);
        } catch (ValidationException $e) {
            $firstError = collect($e->errors())->flatten()->first();
            return redirect()->back()->withInput()->with('error', $firstError);
        }

        $category->update($validated);

        return redirect()->route('dashboard.categories.index')->with('success', 'Category updated successfully.');
    }

    /**
     * Burahin ang category.
     */
    public function destroy(Category $category)
    {
        // Tandaan: Dahil sa 'onDelete('cascade')' sa pivot table,
        // ang mga record sa 'category_post' ay awtomatikong mabubura.
        $category->delete();
        return redirect()->route('dashboard.categories.index')->with('success', 'Category deleted successfully.');
    }
}