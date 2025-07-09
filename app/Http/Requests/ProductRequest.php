<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductRequest extends FormRequest
{
  public function authorize()
  {
    return true;
  }

  public function rules()
  {
    return [
      'name' => 'required|max:255',
      'description' => 'nullable',
      'price' => 'required|numeric|min:0',
      'discount_price' => 'nullable|numeric|min:0|lt:price',
      'stock' => 'required|integer|min:0',
      'category_id' => 'required|exists:categories,id',
      'specifications' => 'nullable|json',
      'image' => 'nullable|image|max:2048'
    ];
  }

  public function messages()
  {
    return [
      'name.required' => 'Tên sản phẩm là bắt buộc',
      'name.max' => 'Tên sản phẩm không được vượt quá 255 ký tự',
      'price.required' => 'Giá sản phẩm là bắt buộc',
      'price.numeric' => 'Giá sản phẩm phải là số',
      'price.min' => 'Giá sản phẩm không được âm',
      'discount_price.numeric' => 'Giá khuyến mãi phải là số',
      'discount_price.min' => 'Giá khuyến mãi không được âm',
      'discount_price.lt' => 'Giá khuyến mãi phải nhỏ hơn giá gốc',
      'stock.required' => 'Số lượng tồn kho là bắt buộc',
      'stock.integer' => 'Số lượng tồn kho phải là số nguyên',
      'stock.min' => 'Số lượng tồn kho không được âm',
      'category_id.required' => 'Danh mục sản phẩm là bắt buộc',
      'category_id.exists' => 'Danh mục sản phẩm không tồn tại',
      'specifications.json' => 'Thông số kỹ thuật phải là định dạng JSON hợp lệ',
      'image.image' => 'File phải là hình ảnh',
      'image.max' => 'Kích thước hình ảnh không được vượt quá 2MB'
    ];
  }
}
