<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\Setting::updateOrCreate(['key' => 'active_semester'], ['value' => '1']);
        \App\Models\Setting::updateOrCreate(['key' => 'active_school_year'], ['value' => '2024-2025']);
        \App\Models\Setting::updateOrCreate(['key' => 'school_name'], ['value' => 'Partido State University']);
    }
}
