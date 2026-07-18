<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::latest()->paginate(15);

        return view('admin.categories.index', compact('categories'));
    }

    public function create()
    {
        return view('admin.categories.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:categories,name',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp,gif|max:5120',
            'status' => 'nullable|boolean',
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            if (! $file->isValid()) {
                return back()
                    ->withInput()
                    ->withErrors(['image' => 'Image upload failed. Please try a smaller JPG or PNG (max 5MB).']);
            }
            $imagePath = $file->store('categories', 'public');
        }

        Category::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'description' => $request->description,
            'image' => $imagePath,
            'status' => $request->boolean('status', true),
        ]);

        return redirect()->route('admin.categories.index')
            ->with('success', 'Category created successfully.');
    }

    public function edit($id)
    {
        $category = Category::findOrFail($id);

        return view('admin.categories.edit', compact('category'));
    }

    public function update(Request $request, $id)
    {
        $category = Category::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255|unique:categories,name,'.$category->id,
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp,gif|max:5120',
            'status' => 'nullable|boolean',
        ]);

        $category->name = $request->name;
        $category->slug = Str::slug($request->name);
        $category->description = $request->description;
        $category->status = $request->boolean('status');

        $imageChanged = false;

        if ($request->hasFile('image')) {
            $file = $request->file('image');

            if (! $file->isValid()) {
                return back()
                    ->withInput()
                    ->withErrors(['image' => 'Image upload failed. Please try a smaller JPG or PNG (max 5MB).']);
            }

            // Delete previous category upload only (never delete product images)
            if ($category->image && str_starts_with($category->image, 'categories/')) {
                Storage::disk('public')->delete($category->image);
            }

            $category->image = $file->store('categories', 'public');
            $imageChanged = true;
        }

        $category->save();

        return redirect()
            ->route('admin.categories.edit', $category->id)
            ->with('success', $imageChanged
                ? 'Category image replaced successfully. Check the homepage Shop by Category section.'
                : 'Category updated successfully.');
    }

    public function destroy($id)
    {
        $category = Category::findOrFail($id);

        if ($category->image && str_starts_with($category->image, 'categories/')) {
            Storage::disk('public')->delete($category->image);
        }

        $category->delete();

        return redirect()->route('admin.categories.index')
            ->with('success', 'Category deleted successfully.');
    }
}
