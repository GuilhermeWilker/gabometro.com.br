<?php

namespace App\Filament\Widgets;

use App\Models\AssessmentResult;
use App\Models\Students;
use Filament\Actions\Action;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class StudentPdfsTable extends TableWidget
{
    public ?Students $record = null;

    protected static ?string $heading = 'Relatórios (PDFs)';

    protected int | string | array $columnSpan = 'full';

    protected static ?int $sort = 100; // bem no final

    public function table(Table $table): Table
    {
        return $table
            ->query(
                AssessmentResult::query()
                    ->where('students_id', $this->record?->id)
                    ->with('assessment')
                    ->latest()
            )
            ->columns([
                TextColumn::make('assessment.name')
                    ->label('Prova / Simulado')
                    ->searchable(),

                TextColumn::make('assessment.assessment_date')
                    ->label('Data')
                    ->date('d/m/Y'),

                TextColumn::make('correct_answers')->label('Acertos'),
                TextColumn::make('incorrect_answers')->label('Erros'),
                TextColumn::make('total_questions')->label('Total'),

                TextColumn::make('percentage')
                    ->label('Desempenho')
                    ->state(fn($record) => $record->total_questions
                        ? round(($record->correct_answers / $record->total_questions) * 100, 1) . '%'
                        : '—'),

                IconColumn::make('pdf_path')
                    ->label('PDF')
                    ->boolean()
                    ->getStateUsing(fn($record) => filled($record->pdf_path)
                        && Storage::disk('local')->exists($record->pdf_path)),

                TextColumn::make('email_sent_at')
                    ->label('E-mail enviado')
                    ->dateTime('d/m/Y H:i')
                    ->placeholder('Não enviado'),
            ])
            ->recordActions([
                Action::make('viewPdf')
                    ->label('Visualizar')
                    ->icon('heroicon-o-eye')
                    ->color('info')
                    ->visible(fn($record) => filled($record->pdf_path)
                        && Storage::disk('local')->exists($record->pdf_path))
                    ->url(fn($record) => route('students.results.pdf.view', $record), true),

                Action::make('downloadPdf')
                    ->label('Baixar')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->color('info')
                    ->visible(fn($record) => filled($record->pdf_path)
                        && Storage::disk('local')->exists($record->pdf_path))
                    ->action(function ($record) {
                        return response()->download(
                            Storage::disk('local')->path($record->pdf_path),
                            Str::slug($record->student?->name ?? 'aluno') . '-' .
                                Str::slug($record->assessment?->name ?? 'prova') . '.pdf'
                        );
                    }),
            ])
            ->paginated([5, 10]);
    }
}
