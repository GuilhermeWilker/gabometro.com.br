<?php

namespace App\Filament\Pages;

use Filament\Facades\Filament;
use Filament\Pages\Dashboard as BaseDashboard;
use Filament\Support\Enums\Width;

class Dashboard extends BaseDashboard
{
      protected static ?string $navigationLabel = 'Painel de Controle';

      protected static ?string $title = 'Painel de Controle';

      protected string $view = 'filament.pages.dashboard';

      public function getMaxContentWidth(): Width | string | null
      {
            return Width::Full;
      }

      public function getSchoolMeta(): array
      {
            $school = Filament::getTenant();

            return [
                  'name' => $school?->name ?? 'Escola',
                  'slug' => $school?->slug ?? '',
            ];
      }

      // Desliga os widgets automáticos do panel (a Blade controla tudo)
      public function getWidgets(): array
      {
            return [];
      }
}
