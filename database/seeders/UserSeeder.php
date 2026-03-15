<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
        'login' => 'admin',
        'email' => 'admin@admin.ru',
        'password' => Hash::make('admin123'),
        'first_name' => 'Админ',
        'last_name' => 'Админович',
        'role' => 'admin',
        'phone' => '+7 (666) 666-66-66',
        'address' => 'Пересвет',
        ]);
    }
}
