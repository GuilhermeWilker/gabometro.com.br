<?php

namespace App\Filament\Pages\Auth;

use DiogoGPinto\AuthUIEnhancer\Pages\Auth\Concerns\HasCustomLayout;
use Filament\Auth\Pages\Register;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\HtmlString;
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

                Checkbox::make('terms')
                    ->label(new HtmlString(
                        'Li e aceito os <a href="' . route('terms') . '" target="_blank" class="underline">Termos de uso</a> e a <a href="' . route('privacy') . '" target="_blank" class="underline">Política de privacidade</a>.'
                    ))
                    ->accepted()
                    ->required()
                    ->dehydrated(false)
            ]);
    }

    protected function mutateFormDataBeforeRegister(array $data): array
    {
        // todo novo usuário começa como admin da própria escola
        $data['role'] = 'Administrador';

        return $data;
    }
}
