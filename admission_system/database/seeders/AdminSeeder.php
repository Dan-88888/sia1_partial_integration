<?php

namespace Database\Seeders;

use App\Models\Admin;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        if (Admin::where('email', 'admin@psu.edu.ph')->exists()) {
            $this->command->info('Admin account already exists, skipping.');
            return;
        }

        $password = Str::random(16);

        Admin::create([
            'name' => 'Administrator',
            'email' => 'admin@psu.edu.ph',
            'password' => Hash::make($password),
        ]);

        $this->command->info('Admin account created.');
        $this->command->info('  Email:    admin@psu.edu.ph');
        $this->command->info('  Password: ' . $password);
        $this->command->warn('  Change this password immediately after first login!');
    }
}
