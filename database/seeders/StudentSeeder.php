<?php

namespace Database\Seeders;

use App\Models\ClassRoom;
use App\Models\Students;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class StudentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = fake('pt_BR');
        $quantity = 1278;

        $classRooms = ClassRoom::all();

        $progressBar = $this->command->getOutput()->createProgressBar($quantity);

        $progressBar->start();

        for ($i = 1; $i <= $quantity; $i++) {
            $registrationNumber = sprintf('%04d', $i);

            Students::firstOrCreate(
                ['registration_number' => $registrationNumber],
                [
                    'name' => $faker->unique()->name(),
                    'class_room_id' => $classRooms->random()->id,
                ]
            );

            $progressBar->advance();
        }

        $progressBar->finish();

        $this->command->newLine(2);

        $this->command->info(
            'Alunos (students) criados: ' . Students::count()
        );
    }
}
