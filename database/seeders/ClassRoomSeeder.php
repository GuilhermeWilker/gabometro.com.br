<?php

namespace Database\Seeders;

use App\Models\ClassRoom;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ClassRoomSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $academicYear = (string) now()->year;

        $grades = [
            // Fundamental II
            ['grade_level' => '6º Ano', 'sections' => ['A', 'B', 'C', 'D', 'E', 'F']],
            ['grade_level' => '7º Ano', 'sections' => ['A', 'B', 'C', 'D', 'E', 'F']],
            ['grade_level' => '8º Ano', 'sections' => ['A', 'B', 'C', 'D', 'E', 'F']],
            ['grade_level' => '9º Ano', 'sections' => ['A', 'B', 'C', 'D', 'E', 'F']],

            // Ensino Médio
            ['grade_level' => '1º Ensino Médio', 'sections' => ['A', 'B', 'C']],
            ['grade_level' => '2º Ensino Médio', 'sections' => ['A', 'B', 'C']],
            ['grade_level' => '3º Ensino Médio', 'sections' => ['A', 'B', 'C']],
        ];

        foreach ($grades as $grade) {
            foreach ($grade['sections'] as $section) {
                ClassRoom::firstOrCreate([
                    'grade_level' => $grade['grade_level'],
                    'section' => $section,
                    'academic_year' => $academicYear,
                ]);
            }
        }

        $this->command->info('Turmas (class_rooms) criadas: ' . ClassRoom::count());
    }
}
