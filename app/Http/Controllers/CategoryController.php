<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
  public function index()
  {
    $categories = Category::with('parent')->get();
    return view('categories.index', compact('categories'));
  }

  public function create()
  {
    $categories = Category::all();
    return view('categories.create', compact('categories'));
  }

  public function store(Request $request)
  {
    $validated = $request->validate([
      'name' => 'required|max:255',
      'description' => 'nullable',
      'parent_id' => 'nullable|exists:categories,id'
    ]);

    $validated['slug'] = Str::slug($validated['name']);

    Category::create($validated);

    return redirect()->route('categories.index')
      ->with('success', 'Danh mục đã được tạo thành công.');
  }

  public function edit(Category $category)
  {
    $categories = Category::where('id', '!=', $category->id)->get();
    return view('categories.edit', compact('category', 'categories'));
  }

  public function update(Request $request, Category $category)
  {
    $validated = $request->validate([
      'name' => 'required|max:255',
      'description' => 'nullable',
      'parent_id' => [
        'nullable',
        'exists:categories,id',
        function ($attribute, $value, $fail) use ($category) {
          if ($value == $category->id) {
            $fail('Danh mục không thể là danh mục cha của chính nó.');
          }
        }
      ]
    ]);

    $validated['slug'] = Str::slug($validated['name']);

    $category->update($validated);

    return redirect()->route('categories.index')
      ->with('success', 'Danh mục đã được cập nhật thành công.');
  }

  public function destroy(Category $category)
  {
    $category->delete();
    return redirect()->route('categories.index')
      ->with('success', 'Danh mục đã được xóa thành công.');
  }
}
