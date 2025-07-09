<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with('category')->get();
        return view('products.index', compact('products'));
    }

    public function create()
    {
        $categories = Category::all();
        return view('products.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|max:255',
            'short_description' => 'nullable|max:500',
            'price' => 'required|numeric|min:0',
            'discount_price' => 'nullable|numeric|min:0',
            'description' => 'nullable',
            'image' => 'nullable|image|max:2048', // max 2MB
            'category_id' => 'nullable|exists:categories,id',
            'stock_quantity' => 'nullable|integer|min:0',
            'sku' => 'nullable|max:50',
            'is_available' => 'boolean',
            'specifications' => 'nullable|json'
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('products', 'public');
        }

        $validated['is_available'] = $request->has('is_available');

        if (isset($validated['specifications'])) {
            $validated['specifications'] = json_decode($validated['specifications'], true);
        }

        Product::create($validated);

        return redirect()->route('products.index')->with('success', 'Sản phẩm đã được tạo thành công.');
    }

    public function show(Product $product)
    {
        return view('products.edit', compact('product'));
    }

    public function edit(Product $product)
    {
        $categories = Category::all();
        return view('products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'name' => 'required|max:255',
            'short_description' => 'nullable|max:500',
            'price' => 'required|numeric|min:0',
            'discount_price' => 'nullable|numeric|min:0',
            'description' => 'nullable',
            'image' => 'nullable|image|max:2048', // max 2MB
            'category_id' => 'nullable|exists:categories,id',
            'stock_quantity' => 'nullable|integer|min:0',
            'sku' => 'nullable|max:50',
            'is_available' => 'boolean',
            'specifications' => 'nullable|json'
        ]);

        if ($request->hasFile('image')) {
            // Delete old image
            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }
            $validated['image'] = $request->file('image')->store('products', 'public');
        }

        $validated['is_available'] = $request->has('is_available');

        if (isset($validated['specifications'])) {
            $validated['specifications'] = json_decode($validated['specifications'], true);
        }

        $product->update($validated);

        return redirect()->route('products.index')->with('success', 'Sản phẩm đã được cập nhật thành công.');
    }

    public function destroy(Product $product)
    {
        if ($product->image) {
            Storage::disk('public')->delete($product->image);
        }

        $product->delete();
        return redirect()->route('products.index')->with('success', 'Sản phẩm đã được xóa thành công.');
    }
}
