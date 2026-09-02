<?php

namespace App\Filament\Pages;

use App\Models\School;
use BackedEnum;
use Filament\Facades\Filament;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Components\Actions;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Actions\Action;
use Illuminate\Support\Facades\Mail;
use UnitEnum;

class ManageMailSettings extends Page
{
    protected static string | BackedEnum | null $navigationIcon = Heroicon::Envelope;

    protected static string | UnitEnum | null $navigationGroup = 'Configurações da Organização';

    protected static ?string $navigationLabel = 'E-mail / SMTP';

    protected static ?string $title = 'Configurações de E-mail';

    protected static ?int $navigationSort = 2;

    protected string $view = 'filament.pages.manage-mail-settings';

    public ?array $data = [];

    public function mount(): void
    {
        /** @var School $school */
        $school = Filament::getTenant();

        $this->form->fill([
            'mail_mailer' => $school->mail_mailer ?? 'smtp',
            'mail_host' => $school->mail_host,
            'mail_port' => $school->mail_port ?? 587,
            'mail_username' => $school->mail_username,
            'mail_password' => $school->mail_password,
            'mail_encryption' => $school->mail_encryption ?? 'tls',
            'mail_from_address' => $school->mail_from_address,
            'mail_from_name' => $school->mail_from_name ?? $school->name,
            'mail_is_configured' => $school->mail_is_configured,
            'provider_preset' => null,
        ]);
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Provedor')
                    ->description('Escolha um preset ou configure manualmente')
                    ->icon(Heroicon::Server)
                    ->schema([
                        Select::make('provider_preset')
                            ->label('Preset rápido')
                            ->placeholder('Personalizado / já configurado')
                            ->options([
                                'gmail' => 'Gmail / Google Workspace',
                                'outlook' => 'Outlook / Microsoft 365',
                                'yahoo' => 'Yahoo Mail',
                                'custom' => 'SMTP personalizado',
                            ])
                            ->live()
                            ->afterStateUpdated(function (?string $state, Set $set) {
                                match ($state) {
                                    'gmail' => [
                                        $set('mail_host', 'smtp.gmail.com'),
                                        $set('mail_port', 587),
                                        $set('mail_encryption', 'tls'),
                                    ],
                                    'outlook' => [
                                        $set('mail_host', 'smtp.office365.com'),
                                        $set('mail_port', 587),
                                        $set('mail_encryption', 'tls'),
                                    ],
                                    'yahoo' => [
                                        $set('mail_host', 'smtp.mail.yahoo.com'),
                                        $set('mail_port', 587),
                                        $set('mail_encryption', 'tls'),
                                    ],
                                    default => null,
                                };
                            })
                            ->helperText('Gmail exige “Senha de app”. Outlook pode exigir autenticação moderna.'),
                    ]),

                Section::make('Servidor SMTP')
                    ->icon(Heroicon::Cog6Tooth)
                    ->columns(2)
                    ->schema([
                        TextInput::make('mail_host')
                            ->label('Host SMTP')
                            ->placeholder('smtp.gmail.com')
                            ->required(),

                        TextInput::make('mail_port')
                            ->label('Porta')
                            ->numeric()
                            ->required()
                            ->default(587),

                        Select::make('mail_encryption')
                            ->label('Criptografia')
                            ->options([
                                'tls' => 'TLS (recomendado)',
                                'ssl' => 'SSL',
                                '' => 'Nenhuma',
                            ])
                            ->native(false),

                        TextInput::make('mail_username')
                            ->label('Usuário')
                            ->placeholder('seu-email@dominio.com')
                            ->required(),

                        TextInput::make('mail_password')
                            ->label('Senha / Senha de app')
                            ->password()
                            ->revealable()
                            ->required(fn(Get $get) => blank($get('mail_password')))
                            ->helperText('No Gmail, use uma Senha de app (não a senha normal).'),
                    ]),

                Section::make('Remetente')
                    ->icon(Heroicon::PaperAirplane)
                    ->columns(2)
                    ->schema([
                        TextInput::make('mail_from_name')
                            ->label('Nome do remetente')
                            ->required(),

                        TextInput::make('mail_from_address')
                            ->label('E-mail do remetente')
                            ->email()
                            ->required(),

                        Toggle::make('mail_is_configured')
                            ->label('Configuração ativa')
                            ->helperText('Desative para pausar envios desta escola.')
                            ->columnSpanFull(),
                    ]),
            ])
            ->statePath('data');
    }

    protected function getFormActions(): array
    {
        return [
            Action::make('save')
                ->label('Salvar configurações')
                ->icon(Heroicon::Check)
                ->action('save'),

            Action::make('test')
                ->label('Enviar e-mail de teste')
                ->icon(Heroicon::PaperAirplane)
                ->color('gray')
                ->requiresConfirmation()
                ->modalHeading('Enviar e-mail de teste?')
                ->modalDescription('Será enviado um e-mail para o endereço de remetente configurado.')
                ->action('sendTestEmail'),
        ];
    }

    public function save(): void
    {
        $data = $this->form->getState();

        /** @var School $school */
        $school = Filament::getTenant();

        $school->update([
            'mail_mailer' => 'smtp',
            'mail_host' => $data['mail_host'],
            'mail_port' => $data['mail_port'],
            'mail_username' => $data['mail_username'],
            'mail_password' => $data['mail_password'] ?: $school->mail_password,
            'mail_encryption' => $data['mail_encryption'] ?: null,
            'mail_from_address' => $data['mail_from_address'],
            'mail_from_name' => $data['mail_from_name'],
            'mail_is_configured' => (bool) ($data['mail_is_configured'] ?? false),
        ]);

        Notification::make()
            ->title('Configurações de e-mail salvas')
            ->success()
            ->send();
    }

    public function sendTestEmail(): void
    {
        $this->save();

        /** @var School $school */
        $school = Filament::getTenant()->fresh();

        if (! $school->mail_is_configured || blank($school->mail_host)) {
            Notification::make()
                ->title('Configure e ative o SMTP antes de testar')
                ->danger()
                ->send();

            return;
        }

        try {
            config([
                'mail.default' => 'smtp',
                'mail.mailers.smtp.transport' => 'smtp',
                'mail.mailers.smtp.host' => $school->mail_host,
                'mail.mailers.smtp.port' => $school->mail_port,
                'mail.mailers.smtp.username' => $school->mail_username,
                'mail.mailers.smtp.password' => $school->mail_password,
                'mail.mailers.smtp.encryption' => $school->mail_encryption,
                'mail.from.address' => $school->mail_from_address,
                'mail.from.name' => $school->mail_from_name,
            ]);

            Mail::raw(
                "Este é um e-mail de teste do Gabômetro para a escola {$school->name}.",
                function ($message) use ($school) {
                    $message
                        ->to($school->mail_from_address)
                        ->subject('Teste de SMTP - Gabômetro');
                }
            );

            Notification::make()
                ->title('E-mail de teste enviado com sucesso')
                ->success()
                ->send();
        } catch (\Throwable $e) {
            Notification::make()
                ->title('Falha no envio de teste')
                ->body($e->getMessage())
                ->danger()
                ->send();
        }
    }
}
