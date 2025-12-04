<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UsersTableSeeder extends Seeder
{
  /**
   * Run the database seeds.
   */
  public function run(): void
  {
    User::create([
      'name' => 'Admin',
      'email' => 'admin@admin.com',
      'password' => Hash::make('admin123'),
      'role' => 'admin',
    ]);
    User::create([
      'name' => 'user',
      'email' => 'user@user.com',
      'password' => Hash::make('user123'),
      'role' => 'user',
    ]);
  }
}
