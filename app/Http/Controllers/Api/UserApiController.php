<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Http\Requests\UserRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use App\Models\Role;

class UserApiController extends Controller
{
  public function register(Request $request)
  {
    $validated = $request->validate([
      'name' => 'required|string|max:255',
      'email' => 'required|email|unique:users',
      'password' => 'required|min:8',
      'phone' => 'nullable|string|max:20',
    ]);

    $validated['password'] = Hash::make($validated['password']);

    $user = User::create($validated);

    // Gán role mặc định là 'user'
    $userRole = Role::where('slug', 'user')->first();
    $user->roles()->attach($userRole);

    $token = $user->createToken('auth_token')->plainTextToken;

    return response()->json([
      'success' => true,
      'message' => 'Đăng ký thành công',
      'data' => $user,
      'access_token' => $token,
      'token_type' => 'Bearer'
    ], 201);
  }

  public function login(Request $request)
  {
    $validated = $request->validate([
      'email' => 'required|email',
      'password' => 'required'
    ]);

    if (!Auth::attempt($validated)) {
      return response()->json([
        'success' => false,
        'message' => 'Email hoặc mật khẩu không chính xác'
      ], 401);
    }

    $user = User::where('email', $validated['email'])->firstOrFail();
    $token = $user->createToken('auth_token')->plainTextToken;

    return response()->json([
      'success' => true,
      'message' => 'Đăng nhập thành công',
      'data' => $user->load('roles'),
      'access_token' => $token,
      'token_type' => 'Bearer'
    ]);
  }

  public function logout(Request $request)
  {
    $request->user()->currentAccessToken()->delete();

    return response()->json([
      'success' => true,
      'message' => 'Đăng xuất thành công'
    ]);
  }

  public function index()
  {
    $users = User::with('roles')->get();
    return response()->json([
      'success' => true,
      'data' => $users
    ]);
  }

  public function store(UserRequest $request)
  {
    $validated = $request->validated();

    // Xử lý password
    $validated['password'] = Hash::make($validated['password']);

    // Xử lý avatar nếu có
    if ($request->hasFile('avatar')) {
      $path = $request->file('avatar')->store('avatars', 'public');
      $validated['avatar'] = $path;
    }

    // Tạo user
    $user = User::create($validated);

    // Gán roles
    $user->roles()->attach($validated['roles']);

    return response()->json([
      'success' => true,
      'message' => 'Người dùng đã được tạo thành công',
      'data' => $user->load('roles')
    ], 201);
  }

  public function show(User $user)
  {
    return response()->json([
      'success' => true,
      'data' => $user->load('roles')
    ]);
  }

  public function update(UserRequest $request, User $user)
  {
    $validated = $request->validated();

    // Xử lý password nếu có
    if (isset($validated['password'])) {
      $validated['password'] = Hash::make($validated['password']);
    } else {
      unset($validated['password']);
    }

    // Xử lý avatar nếu có
    if ($request->hasFile('avatar')) {
      // Xóa avatar cũ nếu có
      if ($user->avatar) {
        Storage::disk('public')->delete($user->avatar);
      }
      $path = $request->file('avatar')->store('avatars', 'public');
      $validated['avatar'] = $path;
    }

    // Cập nhật user
    $user->update($validated);

    // Cập nhật roles
    if (isset($validated['roles'])) {
      $user->roles()->sync($validated['roles']);
    }

    return response()->json([
      'success' => true,
      'message' => 'Người dùng đã được cập nhật thành công',
      'data' => $user->load('roles')
    ]);
  }

  public function destroy(User $user)
  {
    // Xóa avatar nếu có
    if ($user->avatar) {
      Storage::disk('public')->delete($user->avatar);
    }

    $user->roles()->detach();
    $user->delete();

    return response()->json([
      'success' => true,
      'message' => 'Người dùng đã được xóa thành công'
    ]);
  }

  public function profile(Request $request)
  {
    return response()->json([
      'success' => true,
      'data' => $request->user()->load('roles')
    ]);
  }

  public function updateProfile(UserRequest $request)
  {
    return $this->update($request, $request->user());
  }
}
