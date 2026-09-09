<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="utf-8">
    <style>
        body {
            font-family: DejaVu Sans, Arial, sans-serif;
            margin: 40px;
            padding-left: 60px;
            padding-top: 40px;
            color: #333;
        }

        .header {
            text-align: center;
            border-bottom: 3px solid #2c3e50;
            padding-bottom: 10px;
            margin-bottom: 30px;
        }

        .title {
            font-size: 22px;
            font-weight: bold;
            color: #2c3e50;
        }

        .subtitle {
            font-size: 14px;
            color: #555;
        }

        .info {
            margin-bottom: 20px;
            font-size: 16px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th {
            background: #2c3e50;
            color: #fff;
            padding: 10px;
            text-align: left;
        }

        td {
            border: 1px solid #ddd;
            padding: 8px;
        }

        tr:nth-child(even) {
            background: #f2f2f2;
        }

        .box {
            background: #ecf0f1;
            padding: 15px;
            border-radius: 5px;
            margin-top: 25px;
            font-size: 16px;
        }

        .footer {
            position: absolute;
            bottom: 20px;
            right: 40px;
            font-size: 12px;
            color: #777;
        }
    </style>
</head>

<body>
    <div class="header">
        <div class="title">Relatório de Desempenho do Aluno</div>
        <div class="subtitle">Sistema de Avaliação Escolar</div>
    </div>

    <div class="info">
        <strong>Matrícula:</strong> {{ $student->registration_number }}<br>
        <strong>Nome:</strong> {{ $student->name }}
    </div>

    <table>
        <thead>
            <tr>
                <th>Disciplina</th>
                <th style="text-align: right; width: 80px;">Acertos</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($subjects as $subject)
                <tr>
                    <td>{{ $subject->abbreviation }}</td>
                    <td style="text-align: center;">{{ $subject->pivot->correct_answers }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="box">
        <strong>Total de Acertos:</strong> {{ $result->correct_answers }}<br>
        <strong>Total de Erros:</strong> {{ $result->incorrect_answers }}
    </div>

    <div class="footer">
        Gerado em: {{ now()->format('d/m/Y') }}
    </div>
</body>

</html>
