<?php

namespace App\Http\Middleware;

use App\Filament\Pages\Billing\ManageSubscription;
use Closure;
use Filament\Facades\Filament;
use Filament\Notifications\Notification;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureSchoolSubscriptionIsActive
{
    public function handle(Request $request, Closure $next): Response
    {
        $tenant = Filament::getTenant();

        if (! $tenant) {
            return $next($request);
        }

        if ($this->isAllowedWithoutActiveSubscription($request)) {
            return $next($request);
        }

        if ($tenant->hasActiveSubscription()) {
            return $next($request);
        }

        Notification::make()
            ->title('Assinatura necessária')
            ->body('Regularize o plano da escola para continuar usando o Gabômetro.')
            ->warning()
            ->send();

        return redirect()->to(
            ManageSubscription::getUrl(tenant: $tenant)
        );
    }

    protected function isAllowedWithoutActiveSubscription(Request $request): bool
    {
        $routeName = $request->route()?->getName() ?? '';

        // Ajuste se o name da page for outro (php artisan route:list | find billing)
        $allowed = [
            'filament.admin.pages.billing.manage-subscription',
            'filament.admin.auth.logout',
            'filament.admin.tenant.profile', // se existir e quiser liberar
        ];

        foreach ($allowed as $name) {
            if ($routeName === $name || str_starts_with($routeName, $name)) {
                return true;
            }
        }

        // fallback por path
        $path = trim($request->path(), '/');

        return str_contains($path, 'billing')
            || str_contains($path, 'manage-subscription')
            || str_contains($path, 'logout');
    }
}
