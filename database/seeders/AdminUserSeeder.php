<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::firstOrCreate(
            ['email' => 'admin@bidayatialjadida.net'],
            [
                'password' => Hash::make('Admin@Bidaya_2024'),
                'name' => 'Admin',
                'freezed' => 0,
                'is_admin' => 1
            ]
        );
    }
}
