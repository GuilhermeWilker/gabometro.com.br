<?php

namespace Database\Seeders;

use App\Models\Plan;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Env;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        Plan::create([
            'name' => 'Starter',
            'slug' => 'starter',
            'report_limit' => 300,
            'price' => 349,
            'is_active' => true
        ]);

        Plan::create([
            'name' => 'Premium',
            'slug' => 'premium',
            'report_limit' => 900,
            'price' => 548,
            'is_active' => true
        ]);
    }
}
