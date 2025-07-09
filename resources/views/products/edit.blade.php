@extends('layouts.app')

@section('content')
<div class="card">
    <div class="card-header">
        <h5 class="mb-0">Chỉnh sửa sản phẩm</h5>
    </div>
    <div class="card-body">
        <form action="{{ route('products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            
            <div class="row">
                <div class="col-md-8">
                    <div class="mb-3">
                        <label for="name" class="form-label">Tên sản phẩm</label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" 
                            id="name" name="name" value="{{ old('name', $product->name) }}" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="short_description" class="form-label">Mô tả ngắn</label>
                        <textarea class="form-control @error('short_description') is-invalid @enderror" 
                            id="short_description" name="short_description" rows="2">{{ old('short_description', $product->short_description) }}</textarea>
                        @error('short_description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">Mô tả chi tiết</label>
                        <textarea class="form-control @error('description') is-invalid @enderror" 
                            id="description" name="description" rows="4">{{ old('description', $product->description) }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="price" class="form-label">Giá gốc</label>
                                <div class="input-group">
                                    <input type="number" step="0.01" class="form-control @error('price') is-invalid @enderror" 
                                        id="price" name="price" value="{{ old('price', $product->price) }}" required>
                                    <span class="input-group-text">đ</span>
                                    @error('price')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="discount_price" class="form-label">Giá khuyến mãi</label>
                                <div class="input-group">
                                    <input type="number" step="0.01" class="form-control @error('discount_price') is-invalid @enderror" 
                                        id="discount_price" name="discount_price" value="{{ old('discount_price', $product->discount_price) }}">
                                    <span class="input-group-text">đ</span>
                                    @error('discount_price')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="mb-3">
                        <label for="image" class="form-label">Hình ảnh sản phẩm</label>
                        @if($product->image)
                            <div class="mb-2">
                                <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="img-thumbnail" style="max-height: 150px">
                            </div>
                        @endif
                        <input type="file" class="form-control @error('image') is-invalid @enderror" 
                            id="image" name="image" accept="image/*">
                        <small class="text-muted">Để trống nếu không muốn thay đổi hình ảnh</small>
                        @error('image')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="category_id" class="form-label">Danh mục</label>
                        <select class="form-select @error('category_id') is-invalid @enderror" 
                            id="category_id" name="category_id">
                            <option value="">Chọn danh mục</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('category_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="sku" class="form-label">Mã SKU</label>
                        <input type="text" class="form-control @error('sku') is-invalid @enderror" 
                            id="sku" name="sku" value="{{ old('sku', $product->sku) }}">
                        @error('sku')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="stock_quantity" class="form-label">Số lượng trong kho</label>
                        <input type="number" class="form-control @error('stock_quantity') is-invalid @enderror" 
                            id="stock_quantity" name="stock_quantity" value="{{ old('stock_quantity', $product->stock_quantity) }}">
                        @error('stock_quantity')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="is_available" name="is_available" value="1" 
                                {{ old('is_available', $product->is_available) ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_available">Có sẵn để bán</label>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mb-3">
                <label for="specifications" class="form-label">Thông số kỹ thuật (JSON)</label>
                <textarea class="form-control @error('specifications') is-invalid @enderror" 
                    id="specifications" name="specifications" rows="3">{{ old('specifications', is_array($product->specifications) ? json_encode($product->specifications, JSON_PRETTY_PRINT) : $product->specifications) }}</textarea>
                <small class="text-muted">Nhập thông số dưới dạng JSON. Ví dụ: {"Màu sắc": "Đỏ", "Kích thước": "XL"}</small>
                @error('specifications')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="d-flex justify-content-between">
                <a href="{{ route('products.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Quay lại
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Cập nhật
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
