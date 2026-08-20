<?php

namespace App\Imports;

use App\Models\Assessment;
use Maatwebsite\Excel\Concerns\Import;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class AssessmentResultsImport implements Import, WithMultipleSheets
{
    public function __construct(private readonly Assessment $assessment) {}

    public function sheets(): array
    {
        return [
            'Resultado_Geral' => new ResultadoGeralImport($this->assessment),
        ];
    }
}
