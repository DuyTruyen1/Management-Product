<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Http\Requests\CategoryRequest;
use Illuminate\Support\Str;

class CategoryApiController extends Controller
{
  public function index()
  {
    $categories = Category::with('parent')->get();
    return response()->json([
      'success' => true,
      'data' => $categories
    ]);
  }

  public function store(CategoryRequest $request)
  {
    $validated = $request->validated();
    $validated['slug'] = Str::slug($validated['name']);

    $category = Category::create($validated);

    return response()->json([
      'success' => true,
      'message' => 'Danh mục đã được tạo thành công',
      'data' => $category
    ], 201);
  }

  public function show(Category $category)
  {
    return response()->json([
      'success' => true,
      'data' => $category->load('parent')
    ]);
  }

  public function update(CategoryRequest $request, Category $category)
  {
    $validated = $request->validated();
    $validated['slug'] = Str::slug($validated['name']);

    $category->update($validated);

    return response()->json([
      'success' => true,
      'message' => 'Danh mục đã được cập nhật thành công',
      'data' => $category
    ]);
  }

  public function destroy(Category $category)
  {
    $category->delete();
    return response()->json([
      'success' => true,
      'message' => 'Danh mục đã được xóa thành công'
    ]);
  }
}
