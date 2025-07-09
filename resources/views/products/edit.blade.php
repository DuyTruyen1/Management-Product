<h1>Sửa sản phẩm</h1>
<form action="{{ route('products.update', $product->id) }}" method="POST">
    @csrf @method('PUT')
    Tên: <input name="name" value="{{ $product->name }}"><br>
    Giá: <input name="price" value="{{ $product->price }}"><br>
    Mô tả: <textarea name="description">{{ $product->description }}</textarea><br>
    <button type="submit">Cập nhật</button>
</form>
