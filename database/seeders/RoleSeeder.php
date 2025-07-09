<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;

class RoleSeeder extends Seeder
{
  public function run()
  {
    $roles = [
      [
        'name' => 'Administrator',
        'slug' => 'admin',
        'description' => 'Quản trị viên hệ thống'
      ],
      [
        'name' => 'User',
        'slug' => 'user',
        'description' => 'Người dùng thông thường'
      ]
    ];

    foreach ($roles as $role) {
      Role::create($role);
    }
  }
}
