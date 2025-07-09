<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class UserRequest extends FormRequest
{
  public function authorize()
  {
    return true;
  }

  public function rules()
  {
    $rules = [
      'name' => 'required|string|max:255',
      'email' => 'required|email|max:255|unique:users,email',
      'phone' => 'nullable|string|max:20',
      'address' => 'nullable|string',
      'birth_date' => 'nullable|date',
      'gender' => 'nullable|in:male,female,other',
      'is_active' => 'boolean',
      'roles' => 'required|array',
      'roles.*' => 'exists:roles,id',
      'avatar' => 'nullable|image|max:2048'
    ];

    // Nếu là tạo mới user thì bắt buộc phải có password
    if ($this->isMethod('POST')) {
      $rules['password'] = ['required', Password::defaults()];
    }

    // Nếu là cập nhật user
    if ($this->isMethod('PUT') || $this->isMethod('PATCH')) {
      $userId = $this->route('user')->id;
      $rules['email'] = 'required|email|max:255|unique:users,email,' . $userId;
      $rules['password'] = ['nullable', Password::defaults()];
    }

    return $rules;
  }

  public function messages()
  {
    return [
      'name.required' => 'Tên người dùng là bắt buộc',
      'name.max' => 'Tên người dùng không được vượt quá 255 ký tự',
      'email.required' => 'Email là bắt buộc',
      'email.email' => 'Email không đúng định dạng',
      'email.unique' => 'Email đã được sử dụng',
      'phone.max' => 'Số điện thoại không được vượt quá 20 ký tự',
      'birth_date.date' => 'Ngày sinh không đúng định dạng',
      'gender.in' => 'Giới tính phải là một trong các giá trị: nam, nữ, khác',
      'password.required' => 'Mật khẩu là bắt buộc khi tạo mới người dùng',
      'roles.required' => 'Vai trò là bắt buộc',
      'roles.array' => 'Vai trò phải là một mảng',
      'roles.*.exists' => 'Vai trò không tồn tại',
      'avatar.image' => 'File phải là hình ảnh',
      'avatar.max' => 'Kích thước hình ảnh không được vượt quá 2MB'
    ];
  }
}
