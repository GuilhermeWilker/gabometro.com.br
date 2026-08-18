<?php

namespace App\Filament\Pages;

use DiogoGPinto\AuthUIEnhancer\Pages\Auth\Concerns\HasCustomLayout;
use Filament\Auth\Pages\Login;
use Filament\Pages\Page;

class LoginPage extends Login
{
    use HasCustomLayout;

    public function mount(): void
    {
        parent::mount();

        if (app()->isLocal()) {
            $this->form->fill([
                'email' => 'wilkerguilherme0@gmail.com',
                'password' => 'password',

            ]);
        }
    }
}
