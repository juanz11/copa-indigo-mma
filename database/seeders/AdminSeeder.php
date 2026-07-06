<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@copaindigomma.com'],
            [
                'name'     => 'Administrador',
                'email'    => 'admin@copaindigomma.com',
                'password' => Hash::make('CopaIndigo2024!'),
                'role'     => 'admin',
            ]
        );
    }
}
