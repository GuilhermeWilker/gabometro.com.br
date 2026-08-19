<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(4)->create();

        // $this->call([
        //     ClassRoomSeeder::class,
        //     StudentSeeder::class,
        // ]);

        User::factory()->create([
            'name' => 'Guilherme Wilker',
            'email' => 'wilkerguilherme0@gmail.com',
            'role' => 'Administrador',
        ]);
    }
}
