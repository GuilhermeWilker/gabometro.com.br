<?php

namespace App\Filament\Resources\Students\RelationManagers;

use Filament\Actions\Action;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ResultsRelationManager extends RelationManager
{
      protected static string $relationship = 'results';

      protected static ?string $title = 'Relatórios (PDFs)';

      public function table(Table $table): Table
      {
            return $table
                  ->recordTitleAttribute('id')
                  ->columns([
                        TextColumn::make('assessment.name')
                              ->label('Prova / Simulado')
                              ->searchable()
                              ->sortable(),

                        TextColumn::make('assessment.assessment_date')
                              ->label('Data')
                              ->date('d/m/Y')
                              ->sortable(),

                        TextColumn::make('correct_answers')
                              ->label('Acertos'),

                        TextColumn::make('incorrect_answers')
                              ->label('Erros'),

                        TextColumn::make('total_questions')
                              ->label('Total'),

                        TextColumn::make('percentage')
                              ->label('Desempenho')
                              ->state(function ($record) {
                                    if (! $record->total_questions) {
                                          return '—';
                                    }

                                    return round(($record->correct_answers / $record->total_questions) * 100, 1) . '%';
                              }),

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
                  ->headerActions([])
                  ->recordActions([
                        Action::make('viewPdf')
                              ->label('Visualizar')
                              ->icon('heroicon-o-eye')
                              ->color('info')
                              ->visible(fn($record) => filled($record->pdf_path)
                                    && Storage::disk('local')->exists($record->pdf_path))
                              ->url(fn($record) => route('students.results.pdf.view', $record), shouldOpenInNewTab: true),

                        Action::make('downloadPdf')
                              ->label('Baixar')
                              ->icon('heroicon-o-arrow-down-tray')
                              ->color('success')
                              ->visible(fn($record) => filled($record->pdf_path)
                                    && Storage::disk('local')->exists($record->pdf_path))
                              ->action(function ($record) {
                                    $studentName = Str::slug($record->student?->name ?? 'aluno');
                                    $assessmentName = Str::slug($record->assessment?->name ?? 'prova');

                                    return response()->download(
                                          Storage::disk('local')->path($record->pdf_path),
                                          "{$studentName}-{$assessmentName}.pdf"
                                    );
                              }),
                  ])
                  ->defaultSort('created_at', 'desc')
                  ->emptyStateHeading('Nenhum relatório ainda')
                  ->emptyStateDescription('Os PDFs aparecerão aqui depois de gerar os relatórios do simulado.');
      }
}
