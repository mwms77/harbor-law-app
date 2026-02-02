<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Create default admin user
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@estate.local',
            'password' => Hash::make('ChangeMe123!'),
            'role' => 'admin',
            'is_active' => true,
            'email_verified_at' => now(),
        ]);

        echo "✓ Admin user created\n";
        echo "  Email: admin@estate.local\n";
        echo "  Password: ChangeMe123!\n";
        echo "  ⚠️  CHANGE THIS PASSWORD IMMEDIATELY!\n\n";
    }
}
