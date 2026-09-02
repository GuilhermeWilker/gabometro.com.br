<x-mail::message>
    # Resultado da avaliação

    Olá, **{{ $studentName }}**!

    Segue o relatório de desempenho da prova **{{ $assessmentName }}** da escola **{{ $schoolName }}**.

    **Desempenho geral:** {{ $percentage }}%

    O PDF completo está em anexo.

    Atenciosamente,
    {{ $schoolName }}
</x-mail::message>
