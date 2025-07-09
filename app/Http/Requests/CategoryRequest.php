<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CategoryRequest extends FormRequest
{
  public function authorize()
  {
    return true;
  }

  public function rules()
  {
    $rules = [
      'name' => 'required|max:255',
      'description' => 'nullable',
      'parent_id' => [
        'nullable',
        'exists:categories,id',
      ]
    ];

    // Thêm validation cho parent_id khi update để tránh self-referencing
    if ($this->isMethod('PUT') || $this->isMethod('PATCH')) {
      $categoryId = $this->route('category')->id;
      $rules['parent_id'][] = function ($attribute, $value, $fail) use ($categoryId) {
        if ($value == $categoryId) {
          $fail('Danh mục không thể là danh mục cha của chính nó.');
        }
      };
    }

    return $rules;
  }

  public function messages()
  {
    return [
      'name.required' => 'Tên danh mục là bắt buộc',
      'name.max' => 'Tên danh mục không được vượt quá 255 ký tự',
      'parent_id.exists' => 'Danh mục cha không tồn tại',
    ];
  }
}
