<?php

namespace App\Filament\Resources\Assessments\Tables;

use App\Jobs\GenerateAssessmentResultPdf;
use App\Jobs\SendAssessmentEmails;
use App\Jobs\SendAssessmentResultEmail;
use App\Models\Assessment;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Notifications\Notification;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Filament\Support\Colors\Color;

class AssessmentsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Nome')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('class_room_id')
                    ->label('Série e turma')
                    ->state(fn($record) => "{$record->classRoom->grade_level} {$record->classRoom->section}")
                    ->sortable(),
                TextColumn::make('assessment_date')
                    ->label('Data de aplicação')
                    ->isoDate()
                    ->sortable(),
                TextColumn::make('status')
                    ->badge()
                    ->formatStateUsing(fn(string $state) => match ($state) {
                        'pending' => 'Pendente',
                        'processing' => 'Processando',
                        'processed' => 'Processado',
                        'sending' => 'Enviando e-mails',
                        'sent' => 'E-mails enviados',
                        'error' => 'Erro',
                        default => $state,
                    })
                    ->color(fn(string $state) => match ($state) {
                        'pending' => 'gray',
                        'processing', 'sending' => 'warning',
                        'processed' => 'info',
                        'sent' => 'success',
                        'error' => 'danger',
                        default => 'gray',
                    }),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                Action::make('downloadSpreadsheet')
                    ->label('Baixar planilha')
                    ->icon(Heroicon::ArrowDownTray)
                    ->color(Color::Yellow)
                    ->visible(fn($record) => filled($record->spreadsheet_path) && Storage::disk('local')->exists($record->spreadsheet_path))
                    ->action(fn($record) => response()->download(
                        Storage::disk('local')->path($record->spreadsheet_path),
                        Str::slug($record->name) . '.xlsx'
                    )),

                Action::make('generatePdfs')
                    ->label('Gerar PDFs')
                    ->icon('heroicon-o-document')
                    ->action(function (Assessment $record) {
                        $record->results()->each(function ($result) {
                            GenerateAssessmentResultPdf::dispatch($result->id);
                        });
                    }),

                Action::make('sendEmails')
                    ->label('Enviar e-mails')
                    ->icon('heroicon-o-envelope')
                    ->color('success')
                    ->requiresConfirmation()
                    ->modalHeading('Enviar resultados por e-mail?')
                    ->modalDescription('Os e-mails serão enviados em segundo plano. Você receberá notificações de progresso.')
                    ->action(function (Assessment $record) {
                        $record->update(['status' => 'sending']);

                        SendAssessmentEmails::dispatch($record->id, auth()->id());

                        Notification::make()
                            ->title('Envio iniciado')
                            ->body('Acompanhe o progresso no sino de notificações.')
                            ->success()
                            ->send();
                    }),


                // EditAction::make(),
                // DeleteAction::make()->button()
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
