<?php

namespace App\Imports;

use App\Models\Assessment;
use App\Models\AssessmentResult;
use App\Models\Students;
use App\Models\Subject;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ToArray;

class ResultadoGeralImport implements ToArray
{
    public function __construct(private readonly Assessment $assessment) {}

    public function array(array $array): void
    {
        $header = array_shift($array);

        [$registrationIdx, $nameIdx, $emailIdx, $subjectColumns, $correctIdx, $incorrectIdx] = $this->mapColumns($header);

        $schoolId = $this->assessment->school_id;

        DB::transaction(function () use (
            $array,
            $registrationIdx,
            $nameIdx,
            $emailIdx,
            $subjectColumns,
            $correctIdx,
            $incorrectIdx,
            $schoolId
        ) {
            $subjectsByColumn = collect($subjectColumns)
                ->mapWithKeys(fn(int $colIndex, string $abbreviation) => [
                    $colIndex => Subject::firstOrCreate(
                        [
                            'abbreviation' => trim($abbreviation),
                            'school_id' => $schoolId,
                        ]
                    ),
                ]);

            foreach ($array as $row) {
                if (blank($row[$registrationIdx] ?? null)) {
                    continue;
                }

                $emailRaw = ($emailIdx !== false) ? ($row[$emailIdx] ?? null) : null;
                $email = $this->normalizeEmail($emailRaw);

                logger()->info('ResultadoGeralImport student row', [
                    'matricula' => $row[$registrationIdx] ?? null,
                    'email_raw' => $emailRaw,
                    'email_normalized' => $email,
                    'emailIdx' => $emailIdx,
                ]);

                $student = Students::updateOrCreate(
                    [
                        'registration_number' => (string) $row[$registrationIdx],
                        'school_id' => $schoolId,
                    ],
                    [
                        'name' => trim((string) ($row[$nameIdx] ?? '')),
                        'class_room_id' => $this->assessment->class_room_id,
                        'email' => $email,
                    ]
                );

                // se o aluno já existia e a planilha trouxe e-mail novo, garante update
                if ($email && $student->email !== $email) {
                    $student->update(['email' => $email]);
                }

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
     * @return array{0: int|false, 1: int|false, 2: int|false, 3: array<string, int>, 4: int|false, 5: int|false}
     */
    private function mapColumns(array $header): array
    {
        // normaliza cabeçalhos: trim + lowercase
        $normalized = collect($header)
            ->map(fn($label) => mb_strtolower(trim((string) $label)))
            ->all();

        $find = function (array $candidates) use ($normalized) {
            foreach ($candidates as $candidate) {
                $idx = array_search(mb_strtolower($candidate), $normalized, true);
                if ($idx !== false) {
                    return $idx;
                }
            }

            return false;
        };

        $registrationIdx = $find(['Matricula', 'Matrícula', 'matricula']);
        $nameIdx = $find(['Nome aluno(a)', 'Nome_aluno', 'Nome aluno', 'Nome']);
        $emailIdx = $find(['E-mail', 'Email', 'e-mail', 'email']);
        $correctIdx = $find(['Acertos', 'acertos']);
        $incorrectIdx = $find(['Erros', 'erros']);

        $fixedIndexes = array_filter(
            [$registrationIdx, $nameIdx, $emailIdx, $correctIdx, $incorrectIdx],
            fn($idx) => $idx !== false
        );

        $subjectColumns = [];
        foreach ($header as $idx => $label) {
            if (in_array($idx, $fixedIndexes, true)) {
                continue;
            }
            if (blank(trim((string) $label))) {
                continue;
            }
            $subjectColumns[trim((string) $label)] = $idx;
        }

        // DEBUG temporário — olhe no log
        logger()->info('ResultadoGeralImport headers', [
            'raw_header' => $header,
            'registrationIdx' => $registrationIdx,
            'nameIdx' => $nameIdx,
            'emailIdx' => $emailIdx,
            'correctIdx' => $correctIdx,
            'incorrectIdx' => $incorrectIdx,
        ]);

        return [$registrationIdx, $nameIdx, $emailIdx, $subjectColumns, $correctIdx, $incorrectIdx];
    }

    private function normalizeEmail(mixed $value): ?string
    {
        if ($value === null) {
            return null;
        }

        $email = strtolower(trim((string) $value));

        if ($email === '' || $email === '-') {
            return null;
        }

        return filter_var($email, FILTER_VALIDATE_EMAIL) ?: null;
    }
}
