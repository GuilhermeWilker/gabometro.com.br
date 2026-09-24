<?php

namespace App\Filament\Pages\Billing;

use App\Models\Plan;
use App\Models\Subscription;
use App\Services\Asaas\AsaasClient;
use App\Services\Asaas\AsaasSubscriptionService;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Facades\Filament;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Support\Icons\Heroicon;
use Illuminate\Contracts\Support\Htmlable;
use UnitEnum;

class ManageSubscription extends Page
{
    protected static string|BackedEnum|null $navigationIcon = Heroicon::CreditCard;

    protected static string|UnitEnum|null $navigationGroup = 'Configurações da Organização';

    protected static ?string $navigationLabel = 'Plano e cobrança';

    protected static ?string $title = ' ';

    protected static ?int $navigationSort = 90;

    protected string $view = 'filament.pages.billing.manage-subscription';

    public ?Subscription $subscription = null;

    public array $plans = [];

    public ?array $latestPayment = null;

    public static function shouldRegisterNavigation(): bool
    {
        $tenant = Filament::getTenant();

        if (! $tenant) {
            return false;
        }

        // Billing sempre visível
        if (static::class === \App\Filament\Pages\Billing\ManageSubscription::class) {
            return true;
        }

        return $tenant->hasActiveSubscription();
    }

    public static function canAccess(): bool
    {
        $user = auth()->user();

        // só admin da escola (ajuste ao seu role)
        return $user && method_exists($user, 'isAdmin')
            ? $user->isAdmin()
            : true;
    }

    public function mount(): void
    {
        $school = Filament::getTenant();

        $this->subscription = Subscription::query()
            ->with('plan')
            ->where('school_id', $school->getKey())
            ->first();

        $this->plans = Plan::query()
            ->where('is_active', true)
            ->orderBy('price')
            ->get()
            ->all();

        $this->latestPayment = $this->fetchLatestPayment();
    }

    protected function fetchLatestPayment(): ?array
    {
        if (! $this->subscription?->asaas_subscription_id) {
            return null;
        }

        try {
            $response = app(AsaasClient::class)
                ->request()
                ->get('/subscriptions/' . $this->subscription->asaas_subscription_id . '/payments', [
                    'limit' => 1,
                    'offset' => 0,
                ])
                ->json();

            return $response['data'][0] ?? null;
        } catch (\Throwable $e) {
            report($e);

            return null;
        }
    }

    public function subscribe(int $planId): void
    {
        $school = Filament::getTenant();
        $plan = Plan::query()->where('is_active', true)->findOrFail($planId);

        if ($this->subscription?->asaas_subscription_id && in_array($this->subscription->status, ['active', 'pending', 'overdue'], true)) {
            Notification::make()
                ->title('Já existe uma assinatura')
                ->body('Cancele a atual antes de mudar de plano (MVP).')
                ->warning()
                ->send();

            return;
        }

        try {
            $subscription = app(AsaasSubscriptionService::class)
                ->subscribe($school, $plan, 'UNDEFINED'); // cliente escolhe PIX/boleto/cartão

            $this->subscription = $subscription->load('plan');
            $this->latestPayment = $this->fetchLatestPayment();

            Notification::make()
                ->title('Assinatura criada')
                ->body('A cobrança foi gerada. Conclua o pagamento pelo link da fatura.')
                ->success()
                ->send();
        } catch (\Throwable $e) {
            report($e);

            Notification::make()
                ->title('Erro ao criar assinatura')
                ->body($e->getMessage())
                ->danger()
                ->send();
        }
    }

    public function refreshStatus(): void
    {
        $this->mount();

        Notification::make()
            ->title('Status atualizado')
            ->success()
            ->send();
    }
}
