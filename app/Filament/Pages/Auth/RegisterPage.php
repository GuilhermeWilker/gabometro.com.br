<?php

namespace App\Filament\Pages\Auth;

use DiogoGPinto\AuthUIEnhancer\Pages\Auth\Concerns\HasCustomLayout;
use Filament\Auth\Pages\Register;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class RegisterPage extends Register
{
    use HasCustomLayout;
    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('Nome')
                    ->required()
                    ->maxLength(255)
                    ->autofocus(),

                TextInput::make('email')
                    ->label('E-mail')
                    ->email()
                    ->required()
                    ->maxLength(255)
                    ->unique($this->getUserModel()),

                TextInput::make('password')
                    ->label('Senha')
                    ->password()
                    ->revealable()
                    ->required()
                    ->rule(Password::default())
                    ->dehydrateStateUsing(fn($state) => Hash::make($state))
                    ->same('passwordConfirmation'),

                TextInput::make('passwordConfirmation')
                    ->label('Confirmar senha')
                    ->password()
                    ->revealable()
                    ->required()
                    ->dehydrated(false),
            ]);
    }

    protected function mutateFormDataBeforeRegister(array $data): array
    {
        // todo novo usuário começa como admin da própria escola
        $data['role'] = 'Administrador';

        return $data;
    }
}
