<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Hash;
use Str;
class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@one.com',
            'password' => Hash::make('Admin@123'),
            'role' => 'admin',
            'api_token' => Str::random(60),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        User::create([
            'name' => 'Regular User',
            'email' => 'user@one.com',
            'password' => Hash::make('User@123'),
            'role' => 'user',
            'api_token' => Str::random(60),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
