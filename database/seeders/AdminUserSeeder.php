<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

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
            ['email' => 'admin@alkadamschool.net'],
            [
                'password' => Hash::make('Admin@Qadam_2024'),
                'name' => 'Admin',
                'freezed' => 0,
                'is_admin' => 1
            ]
        );
    }
}
