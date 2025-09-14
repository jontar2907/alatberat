<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class KepalaDinasUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'kepaladinas@example.com'],
            [
                'name' => 'Kepala Dinas',
                'password' => Hash::make('password123'), // Change password as needed
                'role' => 'kepala_dinas',
            ]
        );
    }
}
