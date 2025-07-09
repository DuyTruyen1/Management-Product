<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Http\Requests\ProductRequest;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class ProductApiController extends Controller
{
  public function index()
  {
    $products = Product::with('category')->get();
    return response()->json([
      'success' => true,
      'data' => $products
    ]);
  }

  public function store(ProductRequest $request)
  {
    $validated = $request->validated();

    if ($request->hasFile('image')) {
      $path = $request->file('image')->store('products', 'public');
      $validated['image'] = $path;
    }

    $validated['slug'] = Str::slug($validated['name']);
    $product = Product::create($validated);

    return response()->json([
      'success' => true,
      'message' => 'Sản phẩm đã được tạo thành công',
      'data' => $product
    ], 201);
  }

  public function show(Product $product)
  {
    return response()->json([
      'success' => true,
      'data' => $product->load('category')
    ]);
  }

  public function update(ProductRequest $request, Product $product)
  {
    $validated = $request->validated();

    if ($request->hasFile('image')) {
      if ($product->image) {
        Storage::disk('public')->delete($product->image);
      }
      $path = $request->file('image')->store('products', 'public');
      $validated['image'] = $path;
    }

    $validated['slug'] = Str::slug($validated['name']);
    $product->update($validated);

    return response()->json([
      'success' => true,
      'message' => 'Sản phẩm đã được cập nhật thành công',
      'data' => $product
    ]);
  }

  public function destroy(Product $product)
  {
    if ($product->image) {
      Storage::disk('public')->delete($product->image);
    }

    $product->delete();
    return response()->json([
      'success' => true,
      'message' => 'Sản phẩm đã được xóa thành công'
    ]);
  }
}
