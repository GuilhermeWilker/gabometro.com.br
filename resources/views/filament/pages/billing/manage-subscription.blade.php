<x-filament-panels::page>
    <div class="my-2">
        <p>
            Meu plano atual: <span
                class="w-fit text-md p-1 px-4 border border-indigo-700 rounded-2xl bg-indigo-700 text-white cursor-pointer  decoration-none">
                {{ auth()->user()->schools->first()?->subscription?->plan?->name ?? 'Plano não encontrado' }}
            </span>
        </p>
        <h2
            class="text-3xl md:text-7xl mb-4 font-black text-gray-900 dark:text-white underline decoration-indigo-600 decoration-4 decoration-wavy underline-offset-8">
            Plano e cobrança
        </h2>
    </div>

    <div class="space-y-6">
        {{-- Status atual --}}
        <x-filament::section>

            @if ($subscription)
                <div class="flex flex-wrap items-center gap-3 text-sm">
                    <x-filament::badge :color="match ($subscription->status) {
                        'active' => 'success',
                        'pending' => 'warning',
                        'overdue' => 'danger',
                        'canceled' => 'gray',
                        default => 'gray',
                    }">
                        {{ strtoupper($subscription->status) }}
                    </x-filament::badge>

                    @if ($subscription->current_period_end)
                        <span class="text-gray-500">
                            Próx. ciclo: {{ $subscription->current_period_end->format('d/m/Y') }}
                        </span>
                    @endif
                </div>

                @if ($latestPayment)
                    <div class="mt-4 rounded-xl border border-gray-200 p-4 dark:border-gray-700">
                        <p class="text-sm text-gray-600 dark:text-gray-300">
                            Última cobrança:
                            <strong>R$ {{ number_format($latestPayment['value'] ?? 0, 2, ',', '.') }}</strong>
                            · {{ $latestPayment['status'] ?? '—' }}
                            · venc.
                            {{ isset($latestPayment['dueDate']) ? \Carbon\Carbon::parse($latestPayment['dueDate'])->format('d/m/Y') : '—' }}
                        </p>

                        <div class="mt-3 flex flex-wrap gap-2">
                            @if (!empty($latestPayment['invoiceUrl']))
                                <x-filament::button tag="a" :href="$latestPayment['invoiceUrl']" target="_blank" color="primary"
                                    size="sm">
                                    Abrir fatura
                                </x-filament::button>
                            @endif

                            @if (!empty($latestPayment['bankSlipUrl']))
                                <x-filament::button tag="a" :href="$latestPayment['bankSlipUrl']" target="_blank" color="gray"
                                    size="sm">
                                    Boleto PDF
                                </x-filament::button>
                            @endif

                            <x-filament::button wire:click="refreshStatus" color="gray" size="sm" outlined>
                                Atualizar status
                            </x-filament::button>
                        </div>
                    </div>
                @endif
            @else
                <p class="text-sm text-gray-500">
                    Nenhuma assinatura ativa. Escolha um plano abaixo.
                </p>
            @endif
        </x-filament::section>

        {{-- Planos --}}
        <x-filament::section>
            <x-slot name="heading">Planos disponíveis</x-slot>

            <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-3">
                @foreach ($plans as $plan)
                    @php
                        $isCurrent = $subscription && (int) $subscription->plan_id === (int) $plan->id;
                    @endphp

                    <div @class([
                        'rounded-xl border p-5',
                        'border-primary-500 ring-1 ring-primary-500' => $isCurrent,
                        'border-gray-200 dark:border-gray-700' => !$isCurrent,
                    ])>
                        <h3 class="text-lg font-semibold">{{ $plan->name }}</h3>
                        <p class="mt-1 text-2xl font-bold tracking-tight">
                            R$ {{ number_format($plan->price, 2, ',', '.') }}
                            <span class="text-sm font-normal text-gray-500">/mês</span>
                        </p>
                        <p class="mt-2 text-sm text-gray-500">
                            Até {{ number_format($plan->report_limit, 0, ',', '.') }} relatórios/mês
                        </p>

                        <div class="mt-4">
                            @if ($isCurrent && in_array($subscription->status, ['active', 'pending'], true))
                                <x-filament::badge color="success">Plano atual</x-filament::badge>
                            @else
                                <x-filament::button wire:click="subscribe({{ $plan->id }})"
                                    wire:confirm="Criar assinatura deste plano? Uma cobrança será gerada no Asaas."
                                    color="primary" class="w-full">
                                    Assinar
                                </x-filament::button>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </x-filament::section>
    </div>
</x-filament-panels::page>
