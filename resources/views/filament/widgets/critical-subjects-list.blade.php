<x-filament-widgets::widget>
    <x-filament::section>
        <x-slot name="heading">
            Matérias que precisam de atenção
        </x-slot>

        <div class="space-y-3">
            @forelse ($this->getSubjects() as $subject)
                @php
                    $pct = min(100, $subject['avg'] * 10); // se avg é nota bruta ~0-10
                    // se sua média já for 0-100, use: $pct = min(100, $subject['avg']);
                @endphp
                <div>
                    <div class="flex items-center justify-between text-sm mb-1">
                        <span class="font-medium text-gray-900 dark:text-gray-100">
                            {{ $subject['label'] }}
                            <span class="text-gray-400 font-normal">{{ $subject['name'] }}</span>
                        </span>
                        <span class="tabular-nums text-gray-600 dark:text-gray-300">
                            {{ number_format($subject['avg'], 1, ',', '.') }}
                        </span>
                    </div>
                    <div class="h-2 rounded-full bg-gray-100 dark:bg-gray-800 overflow-hidden">
                        <div class="h-full rounded-full bg-primary-500" style="width: {{ $pct }}%"></div>
                    </div>
                </div>
            @empty
                <p class="text-sm text-gray-500">
                    Ainda não há dados de desempenho por disciplina.
                </p>
            @endforelse
        </div>
    </x-filament::section>
</x-filament-widgets::widget>
