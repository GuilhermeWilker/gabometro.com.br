<?php

namespace App\Imports;

use App\Models\Assessment;
use App\Models\AssessmentResult;
use App\Models\Student;
use App\Models\Students;
use App\Models\Subject;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ToArray;

class ResultadoGeralImport implements ToArray
{
    public function __construct(private readonly Assessment $assessment) {}

    public function array(array $array): void
    {
        $header = array_shift($array); // primeira linha = cabeçalho

        [$registrationIdx, $nameIdx, $subjectColumns, $correctIdx, $incorrectIdx] = $this->mapColumns($header);

        DB::transaction(function () use ($array, $registrationIdx, $nameIdx, $subjectColumns, $correctIdx, $incorrectIdx) {
            // resolve/cria os Subjects UMA vez, fora do loop de linhas (evita N+1)
            $subjectsByColumn = collect($subjectColumns)
                ->mapWithKeys(fn(int $colIndex, string $abbreviation) => [
                    $colIndex => Subject::firstOrCreate(['abbreviation' => trim($abbreviation)]),
                ]);

            foreach ($array as $row) {
                if (blank($row[$registrationIdx] ?? null)) {
                    continue; // pula linhas em branco no fim da planilha
                }

                $student = Students::firstOrCreate(
                    ['registration_number' => (string) $row[$registrationIdx]],
                    [
                        'name' => $row[$nameIdx],
                        'class_room_id' => $this->assessment->class_room_id,
                    ]
                );

                $correct = (int) ($row[$correctIdx] ?? 0);
                $incorrect = (int) ($row[$incorrectIdx] ?? 0);

                $result = AssessmentResult::updateOrCreate(
                    [
                        'assessment_id' => $this->assessment->id,
                        'students_id' => $student->id,
                    ],
                    [
                        'correct_answers' => $correct,
                        'incorrect_answers' => $incorrect,
                        'total_questions' => $correct + $incorrect,
                    ]
                );

                $pivotData = [];
                foreach ($subjectColumns as $abbreviation => $colIndex) {
                    $pivotData[$subjectsByColumn[$colIndex]->id] = [
                        'correct_answers' => (int) ($row[$colIndex] ?? 0),
                    ];
                }

                $result->subjects()->sync($pivotData);
            }
        });
    }

    /**
     * @return array{0: int, 1: int, 2: array<string, int>, 3: int, 4: int}
     */
    private function mapColumns(array $header): array
    {
        $registrationIdx = array_search('Matricula', $header, true);
        $nameIdx = array_search('Nome aluno(a)', $header, true);
        $correctIdx = array_search('Acertos', $header, true);
        $incorrectIdx = array_search('Erros', $header, true);

        $fixedIndexes = [$registrationIdx, $nameIdx, $correctIdx, $incorrectIdx];

        $subjectColumns = [];
        foreach ($header as $idx => $label) {
            if (in_array($idx, $fixedIndexes, true)) {
                continue;
            }
            $subjectColumns[$label] = $idx;
        }

        return [$registrationIdx, $nameIdx, $subjectColumns, $correctIdx, $incorrectIdx];
    }
}
