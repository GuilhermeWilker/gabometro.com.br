<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="utf-8">
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            color: #111;
        }

        .header {
            border-bottom: 2px solid #4f46e5;
            padding-bottom: 12px;
            margin-bottom: 20px;
        }

        .title {
            font-size: 18px;
            font-weight: bold;
            color: #4f46e5;
        }

        .meta {
            margin-top: 6px;
            color: #555;
        }

        .card {
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            padding: 14px;
            margin-bottom: 16px;
        }

        .score {
            font-size: 28px;
            font-weight: bold;
            color: #4f46e5;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        th,
        td {
            border: 1px solid #e5e7eb;
            padding: 8px;
            text-align: left;
        }

        th {
            background: #f3f4f6;
        }

        .footer {
            margin-top: 30px;
            font-size: 10px;
            color: #6b7280;
            text-align: center;
        }
    </style>
</head>

<body>
    <div class="header">
        <div class="title">{{ $school->name }}</div>
        <div class="meta">Relatório de desempenho — Gabômetro</div>
    </div>

    <div class="card">
        <strong>Aluno:</strong> {{ $student->name }}<br>
        <strong>Matrícula:</strong> {{ $student->registration_number }}<br>
        <strong>Turma:</strong>
        {{ $student->classRoom?->grade_level }} {{ $student->classRoom?->section }}<br>
        <strong>Prova:</strong> {{ $assessment->name }}<br>
        <strong>Data:</strong> {{ $assessment->assessment_date?->format('d/m/Y') }}
    </div>

    <div class="card">
        <div>Desempenho geral</div>
        <div class="score">{{ $percentage }}%</div>
        <div>
            Acertos: {{ $result->correct_answers }}
            · Erros: {{ $result->incorrect_answers }}
            · Total: {{ $result->total_questions }}
        </div>
    </div>

    <div class="card">
        <strong>Desempenho por disciplina</strong>
        <table>
            <thead>
                <tr>
                    <th>Disciplina</th>
                    <th>Abreviação</th>
                    <th>Acertos</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($subjects as $subject)
                    <tr>
                        <td>{{ $subject->name ?? '—' }}</td>
                        <td>{{ $subject->abbreviation }}</td>
                        <td>{{ $subject->pivot->correct_answers }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="footer">
        Documento gerado automaticamente pelo Gabômetro em {{ now()->format('d/m/Y H:i') }}
    </div>
</body>

</html>
