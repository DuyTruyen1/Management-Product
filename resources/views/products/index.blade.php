@extends('layouts.app')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Danh sách sản phẩm</h5>
        <a href="{{ route('products.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Thêm sản phẩm
        </a>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Hình ảnh</th>
                        <th>Tên sản phẩm</th>
                        <th>Danh mục</th>
                        <th>Giá</th>
                        <th>Tồn kho</th>
                        <th>Trạng thái</th>
                        <th>Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($products as $product)
                    <tr>
                        <td>{{ $product->id }}</td>
                        <td>
                            @if($product->image)
                                <img src="{{ asset('storage/' . $product->image) }}" 
                                    alt="{{ $product->name }}" 
                                    class="img-thumbnail" 
                                    style="max-height: 50px">
                            @else
                                <span class="text-muted">Không có ảnh</span>
                            @endif
                        </td>
                        <td>
                            <strong>{{ $product->name }}</strong>
                            @if($product->short_description)
                                <br>
                                <small class="text-muted">{{ $product->short_description }}</small>
                            @endif
                        </td>
                        <td>{{ $product->category ? $product->category->name : 'Chưa phân loại' }}</td>
                        <td>
                            @if($product->discount_price)
                                <span class="text-decoration-line-through text-muted">
                                    {{ number_format($product->price, 0, ',', '.') }}đ
                                </span>
                                <br>
                                <span class="text-danger">
                                    {{ number_format($product->discount_price, 0, ',', '.') }}đ
                                </span>
                            @else
                                {{ number_format($product->price, 0, ',', '.') }}đ
                            @endif
                        </td>
                        <td>
                            @if($product->stock_quantity > 0)
                                <span class="badge bg-success">{{ $product->stock_quantity }}</span>
                            @else
                                <span class="badge bg-danger">Hết hàng</span>
                            @endif
                        </td>
                        <td>
                            @if($product->is_available)
                                <span class="badge bg-success">Đang bán</span>
                            @else
                                <span class="badge bg-secondary">Ngừng bán</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('products.edit', $product->id) }}" class="btn btn-sm btn-info btn-action">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('products.destroy', $product->id) }}" method="POST" class="d-inline">
                                @csrf 
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger btn-action" onclick="return confirm('Bạn có chắc chắn muốn xóa?')">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
