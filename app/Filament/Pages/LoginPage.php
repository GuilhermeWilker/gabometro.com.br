<?php

namespace App\Filament\Pages;

use Filament\Auth\Pages\Login;
use Filament\Pages\Page;

class LoginPage extends Login
{
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
