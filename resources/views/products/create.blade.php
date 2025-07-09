<h1>Thêm sản phẩm</h1>
<form action="{{ route('products.store') }}" method="POST">
    @csrf
    Tên: <input name="name"><br>
    Giá: <input name="price"><br>
    Mô tả: <textarea name="description"></textarea><br>
    <button type="submit">Lưu</button>
</form>
