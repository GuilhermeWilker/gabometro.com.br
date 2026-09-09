<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Gabômetro — Relatórios de simulados para escolas</title>
    <meta name="description"
        content="Importe a planilha, gere PDFs individuais e envie por e-mail. Painel por escola, turma e estudante.">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Instrument+Sans:wght@400;500;600;700&family=JetBrains+Mono:wght@400;500&display=swap"
        rel="stylesheet">

    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        :root {
            --brand: #2563EB;
            --brand-dark: #1D4ED8;
            --border: #E5E5E5;
            --ink: #171717;
            --ink-dim: #737373;
        }

        html {
            scroll-behavior: smooth;
        }

        body {
            font-family: 'Instrument Sans', ui-sans-serif, system-ui, sans-serif;
            background: #fff;
            color: var(--ink);
        }

        .mono {
            font-family: 'JetBrains Mono', ui-monospace, monospace;
        }

        /* Laravel-style boxed container with visible vertical rules at xl */
        .boxed {
            max-width: 80rem;
            margin-inline: auto;
            position: relative;
            padding-inline: 1rem;
        }

        @media (min-width: 1280px) {
            .boxed {
                border-left: 1px solid var(--border);
                border-right: 1px solid var(--border);
                padding-inline: 4rem;
            }
        }

        .corner-dot {
            position: absolute;
            top: -4px;
            width: 7px;
            height: 7px;
            background: var(--brand);
            z-index: 10;
        }

        .corner-dot.left {
            left: -4px;
        }

        .corner-dot.right {
            right: -4px;
        }

        .grid-bg {
            background-image:
                linear-gradient(to right, rgba(0, 0, 0, 0.05) 1px, transparent 1px),
                linear-gradient(to bottom, rgba(0, 0, 0, 0.05) 1px, transparent 1px);
            background-size: 40px 40px;
            -webkit-mask-image: radial-gradient(ellipse 70% 100% at 50% 0%, black 25%, transparent 85%);
            mask-image: radial-gradient(ellipse 70% 100% at 50% 0%, black 25%, transparent 85%);
        }

        .btn-brand {
            background: var(--brand);
            color: #fff;
        }

        .btn-brand:hover {
            background: var(--brand-dark);
        }

        .btn-outline {
            border: 1px solid var(--border);
            color: var(--ink);
            background: #fff;
        }

        .btn-outline:hover {
            background: #fafafa;
            border-color: #d4d4d4;
        }

        .eyebrow {
            font-family: 'JetBrains Mono', monospace;
            color: var(--brand);
        }

        .step-num {
            font-family: 'JetBrains Mono', monospace;
            color: var(--brand);
        }
    </style>
</head>

<body class="antialiased">
    {{-- Nav --}}
    <header class="sticky top-0 z-50 border-b border-[var(--border)] bg-white/85 backdrop-blur">
        <div class="mx-auto flex h-16 max-w-6xl items-center justify-between px-4 sm:px-6">
            <a href="{{ route('landing') }}"
                class="flex items-center gap-2 font-semibold tracking-tight text-[var(--ink)]">
                {{-- <span
                    class="inline-flex h-7 w-7 items-center justify-center rounded bg-[var(--brand)] text-sm font-bold text-white">G</span>
                Gabômetro --}}
                <img src="/assets/gabometro-logo-light.svg" class="w-52" alt="">
            </a>

            <nav class="hidden items-center gap-8 text-[15px] font-medium tracking-tight text-neutral-600 md:flex">
                <a href="#produto" class="hover:text-neutral-900">Produto</a>
                <a href="#como-funciona" class="hover:text-neutral-900">Como funciona</a>
                <a href="#precos" class="hover:text-neutral-900">Preços</a>
            </nav>

            @if (auth()->check())
                <div class="flex items-center gap-3">
                    <a href="{{ url('/admin/' . auth()->user()->schools->first()->slug) }}"
                        class="btn-brand rounded-lg px-4 py-2 text-sm font-medium shadow-xs">
                        Dashboard
                    </a>
                </div>
            @else
                <div class="flex items-center gap-3">
                    <a href="{{ url('/admin/login') }}"
                        class="hidden text-sm font-medium text-neutral-600 hover:text-neutral-900 sm:inline">
                        Entrar
                    </a>
                    <a href="{{ url('/admin/register') }}"
                        class="btn-brand rounded-lg px-4 py-2 text-sm font-medium shadow-xs">
                        Começar agora
                    </a>
                </div>
            @endif
        </div>
    </header>

    {{-- Hero --}}
    <section class="relative">
        <div class="boxed border-b border-[var(--border)]">
            <div class="corner-dot left"></div>
            <div class="corner-dot right"></div>
            <div class="relative overflow-hidden">
                <div class="grid-bg absolute inset-0 h-[420px]" aria-hidden="true"></div>
                <div class="relative flex flex-col items-center pt-20 pb-24 text-center">
                    <span class="eyebrow mb-4 inline-block text-sm">[ coordenação pedagógica ]</span>
                    <h1 class="max-w-3xl text-4xl tracking-tighter text-pretty sm:text-5xl md:text-6xl">
                        O relatório do simulado pronto antes do próximo intervalo.
                    </h1>
                    <p class="mt-4 max-w-xl text-lg tracking-tight text-neutral-500 sm:text-xl">
                        Importe a planilha de resultados, gere PDFs individuais e envie por e-mail.
                        Multi-escola, painel por turma e controle para coordenadores e professores.
                    </p>
                    <div class="mt-8 flex flex-col items-center gap-4 sm:flex-row">
                        <a href="{{ url('/admin/register') }}"
                            class="btn-brand inline-flex h-12 items-center justify-center rounded-lg px-6 text-base font-medium shadow-xs">
                            Criar conta da escola
                        </a>
                        <a href="#produto"
                            class="btn-outline inline-flex h-12 items-center justify-center rounded-lg px-6 text-base font-medium shadow-xs">
                            Ver o produto
                        </a>
                    </div>

                    {{-- Mock / screenshot --}}
                    <div class="relative mx-auto mt-16 w-full max-w-4xl">
                        <div class="overflow-hidden rounded-xl border border-[var(--border)] bg-white shadow-lg">
                            <div class="flex items-center gap-2 border-b border-[var(--border)] px-4 py-3">
                                <span class="h-2.5 w-2.5 rounded-full bg-neutral-200"></span>
                                <span class="h-2.5 w-2.5 rounded-full bg-neutral-200"></span>
                                <span class="h-2.5 w-2.5 rounded-full bg-neutral-200"></span>
                                <span class="mono ml-3 text-xs text-neutral-400">app.gabometro —
                                    painel-de-controle</span>
                            </div>
                            <img src="{{ asset('assets/lp/dashboard.png') }}" alt="Painel Gabômetro" class="w-full"
                                onerror="this.parentElement.innerHTML='<div class=\'flex h-64 items-center justify-center text-sm text-neutral-400\'>Adicione assets/lp/dashboard.png</div>'">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Partners strip --}}
    <section class="boxed border-b border-[var(--border)] py-10">
        <p class="mono mb-6 text-center text-xs text-neutral-400">espaço para parceiros educacionais</p>
        <div class="flex flex-wrap items-center justify-center gap-6">
            @foreach (range(1, 5) as $i)
                <div
                    class="flex h-12 w-32 items-center justify-center rounded-md border border-dashed border-[var(--border)] text-xs text-neutral-400">
                    Anuncie aqui
                </div>
            @endforeach
        </div>
    </section>

    {{-- Problem --}}
    <section class="relative boxed border-b border-[var(--border)] py-20">
        <div class="corner-dot left"></div>
        <div class="corner-dot right"></div>
        <h2 class="text-center text-3xl tracking-tighter sm:text-4xl">O operacional que trava a coordenação</h2>
        <p class="mx-auto mt-3 max-w-2xl text-center text-neutral-500">
            Depois do simulado, o trabalho pesado ainda está por fazer.
        </p>
        <div class="mt-14 grid divide-neutral-200 rounded-xl border border-[var(--border)] md:grid-cols-3 md:divide-x">
            @foreach ([['title' => 'Planilhas infinitas', 'body' => 'Cruzar gabarito, matrícula e e-mail no Excel consome horas e gera erro.'], ['title' => 'PDF um a um', 'body' => 'Montar relatório individual na mão não escala para turmas inteiras.'], ['title' => 'Envio manual', 'body' => 'Copiar e-mail, anexar arquivo, repetir dezenas de vezes por prova.']] as $item)
                <div class="p-6">
                    <h3 class="text-lg font-medium tracking-tight">{{ $item['title'] }}</h3>
                    <p class="mt-2 text-[15px] leading-relaxed text-neutral-500">{{ $item['body'] }}</p>
                </div>
            @endforeach
        </div>
    </section>

    {{-- Product --}}
    <section id="produto" class="relative boxed border-b border-[var(--border)] py-20">
        <div class="corner-dot left"></div>
        <div class="corner-dot right"></div>
        <h2 class="text-center text-3xl tracking-tighter sm:text-4xl">Tudo que a coordenação precisa em um painel</h2>
        <p class="mx-auto mt-3 max-w-2xl text-center text-neutral-500">
            Multi-tenant por escola, visão por turma e ficha completa do estudante.
        </p>

        <div class="mt-14 space-y-16">
            @foreach ([
        [
            'title' => 'Dashboard da escola',
            'body' => 'Média geral, disciplinas críticas e últimas avaliações — saúde pedagógica em uma tela.',
            'img' => 'assets/lp/dashboard.png',
        ],
        [
            'title' => 'Ficha do estudante',
            'body' => 'Aproveitamento, evolução, desempenho por matéria e histórico de PDFs enviados.',
            'img' => 'assets/lp/student.png',
        ],
        [
            'title' => 'Simulados e envios',
            'body' => 'Importe o Excel modelo, acompanhe o status e dispare os e-mails com um clique.',
            'img' => 'assets/lp/assessments.png',
        ],
        [
            'title' => 'Relatórios individuais',
            'body' => 'PDFs prontos para cada aluno, com gabarito, acertos, erros e desempenho por disciplina.',
            'img' => 'assets/lp/PDF.png',
        ],
    ] as $i => $block)
                <div
                    class="grid items-center gap-10 lg:grid-cols-2 {{ $i % 2 === 1 ? 'lg:[&>*:first-child]:order-2' : '' }}">
                    <div>
                        <span class="mono text-xs text-[var(--brand)]">0{{ $i + 1 }}</span>
                        <h3 class="mt-2 text-2xl font-medium tracking-tight">{{ $block['title'] }}</h3>
                        <p class="mt-3 text-neutral-500">{{ $block['body'] }}</p>
                    </div>
                    <div class="overflow-hidden rounded-xl border border-[var(--border)] shadow-sm">
                        <img src="{{ asset($block['img']) }}" alt="{{ $block['title'] }}" class="w-full"
                            onerror="this.replaceWith(Object.assign(document.createElement('div'),{className:'flex h-48 items-center justify-center text-sm text-neutral-400',textContent:'Screenshot em breve'}))">
                    </div>
                </div>
            @endforeach
        </div>
    </section>

    {{-- How it works --}}
    <section id="como-funciona" class="relative boxed border-b border-[var(--border)] py-20">
        <div class="corner-dot left"></div>
        <div class="corner-dot right"></div>
        <h2 class="text-center text-3xl tracking-tighter sm:text-4xl">Como funciona</h2>
        <div class="mt-14 grid divide-neutral-200 rounded-xl border border-[var(--border)] md:grid-cols-3 md:divide-x">
            @foreach ([['step' => '01', 'title' => 'Importe a planilha', 'body' => 'Baixe o modelo, preencha os resultados do simulado e faça o upload.'], ['step' => '02', 'title' => 'Processe no painel', 'body' => 'O Gabômetro consolida alunos, disciplinas e gera os PDFs em segundo plano.'], ['step' => '03', 'title' => 'Envie os relatórios', 'body' => 'Dispare os e-mails com SMTP da escola e acompanhe o status de cada envio.']] as $item)
                <div class="p-6">
                    <span class="step-num text-sm font-medium">{{ $item['step'] }}</span>
                    <h3 class="mt-2 text-lg font-medium tracking-tight">{{ $item['title'] }}</h3>
                    <p class="mt-2 text-[15px] text-neutral-500">{{ $item['body'] }}</p>
                </div>
            @endforeach
        </div>
    </section>

    {{-- Pricing --}}
    <section id="precos" class="relative boxed border-b border-[var(--border)] py-20" x-data="planCalculator()">
        <div class="corner-dot left"></div>
        <div class="corner-dot right"></div>

        <h2 class="text-center text-3xl tracking-tighter sm:text-4xl">
            Preços simples, por volume de relatórios
        </h2>
        <p class="mx-auto mt-3 max-w-xl text-center text-neutral-500">
            1 relatório = 1 PDF gerado ou enviado para um aluno em um simulado.
        </p>

        {{-- Calculadora --}}
        <div class="mx-auto mt-12 max-w-2xl rounded-xl border border-[var(--border)] p-6 sm:p-8">
            <p class="mono text-xs text-[var(--brand)]">[ estimativa ]</p>
            <h3 class="mt-1 text-lg font-medium tracking-tight">Quantos relatórios por mês?</h3>
            <p class="mt-1 text-sm text-neutral-500">
                Ajuste alunos e simulados — indicamos o plano ideal.
            </p>

            <div class="mt-8 space-y-8">
                {{-- Alunos --}}
                <div>
                    <div class="flex items-center justify-between text-sm">
                        <label class="font-medium text-neutral-700">Alunos</label>
                        <span class="mono text-[var(--brand)]" x-text="students"></span>
                    </div>
                    <input type="range" min="20" max="2000" step="10" x-model.number="students"
                        class="mt-3 h-2 w-full cursor-pointer appearance-none rounded-full bg-neutral-200 accent-[var(--brand)]">
                    <div class="mt-1 flex justify-between text-xs text-neutral-400">
                        <span>20</span>
                        <span>2000</span>
                    </div>
                </div>

                {{-- Simulados / mês --}}
                <div>
                    <div class="flex items-center justify-between text-sm">
                        <label class="font-medium text-neutral-700">Simulados por mês</label>
                        <span class="mono text-[var(--brand)]" x-text="assessments"></span>
                    </div>
                    <input type="range" min="1" max="12" step="1" x-model.number="assessments"
                        class="mt-3 h-2 w-full cursor-pointer appearance-none rounded-full bg-neutral-200 accent-[var(--brand)]">
                    <div class="mt-1 flex justify-between text-xs text-neutral-400">
                        <span>1</span>
                        <span>12</span>
                    </div>
                </div>
            </div>

            {{-- Resultado --}}
            <div class="mt-8 rounded-lg bg-neutral-50 px-5 py-4 sm:flex sm:items-center sm:justify-between">
                <div>
                    <p class="text-sm text-neutral-500">Volume estimado</p>
                    <p class="mt-0.5 text-2xl font-medium tracking-tight">
                        <span class="mono" x-text="formatNumber(reports)"></span>
                        <span class="text-base font-normal text-neutral-500">relatórios/mês</span>
                    </p>
                </div>
                <div class="mt-3 sm:mt-0 sm:text-right">
                    <p class="text-sm text-neutral-500">Plano indicado</p>
                    <p class="mt-0.5 text-xl font-medium tracking-tight text-[var(--brand)]" x-text="planLabel"></p>
                </div>
            </div>
            <p class="mt-3 text-xs text-neutral-400">
                Cálculo: alunos × simulados/mês. Valores aproximados para ajudar na escolha.
            </p>
        </div>

        {{-- Cards --}}
        <div class="mt-14 grid gap-6 lg:grid-cols-3">
            {{-- Starter --}}
            <div class="flex flex-col rounded-xl border p-8 transition-all duration-200"
                :class="plan === 'starter'
                    ?
                    'border-2 border-[var(--brand)] shadow-lg shadow-blue-500/10' :
                    'border-[var(--border)] opacity-80'">
                <template x-if="plan === 'starter'">
                    <span
                        class="mb-3 w-fit rounded-full bg-[var(--brand)] px-2.5 py-0.5 text-xs font-medium text-white">
                        Indicado para você
                    </span>
                </template>
                <h3 class="text-lg font-medium tracking-tight">Starter</h3>
                <p class="mt-1 text-sm text-neutral-500">Até 300 relatórios/mês</p>
                <p class="mt-6 text-4xl font-medium tracking-tight">300 <span
                        class="text-base font-normal text-neutral-500">relatórios/mês</span></p>
                <ul class="mt-6 flex-1 space-y-3 text-sm text-neutral-600">
                    <li>· 1 escola</li>
                    <li>· PDF + e-mail</li>
                    <li>· SMTP próprio</li>
                    <li>· Planilha modelo</li>
                </ul>
                <a href="{{ url('/admin/register') }}"
                    class="mt-8 block rounded-lg py-2.5 text-center text-sm font-medium shadow-xs"
                    :class="plan === 'starter' ? 'btn-brand' : 'btn-outline'">
                    Começar
                </a>
            </div>

            {{-- Premium --}}
            <div class="flex flex-col rounded-xl border p-8 transition-all duration-200"
                :class="plan === 'premium'
                    ?
                    'border-2 border-[var(--brand)] shadow-lg shadow-blue-500/10' :
                    'border-[var(--border)] opacity-80'">
                <template x-if="plan === 'premium'">
                    <span
                        class="mb-3 w-fit rounded-full bg-[var(--brand)] px-2.5 py-0.5 text-xs font-medium text-white">
                        Indicado para você
                    </span>
                </template>
                <h3 class="text-lg font-medium tracking-tight">Premium</h3>
                <p class="mt-1 text-sm text-neutral-500">Até 900 relatórios/mês</p>
                <p class="mt-6 text-4xl font-medium tracking-tight">900 <span
                        class="text-base font-normal text-neutral-500">relatórios/mês</span></p>
                <ul class="mt-6 flex-1 space-y-3 text-sm text-neutral-600">
                    <li>· Tudo do Starter</li>
                    <li>· Mais volume de envios</li>
                    <li>· Usuários da equipe</li>
                    <li>· Suporte prioritário</li>
                </ul>
                <a href="{{ url('/admin/register') }}"
                    class="mt-8 block rounded-lg py-2.5 text-center text-sm font-medium shadow-xs"
                    :class="plan === 'premium' ? 'btn-brand' : 'btn-outline'">
                    Escolher Premium
                </a>
            </div>

            {{-- Enterprise --}}
            <div class="flex flex-col rounded-xl border p-8 transition-all duration-200"
                :class="plan === 'enterprise'
                    ?
                    'border-2 border-[var(--brand)] shadow-lg shadow-blue-500/10' :
                    'border-[var(--border)] opacity-80'">
                <template x-if="plan === 'enterprise'">
                    <span
                        class="mb-3 w-fit rounded-full bg-[var(--brand)] px-2.5 py-0.5 text-xs font-medium text-white">
                        Indicado para você
                    </span>
                </template>
                <h3 class="text-lg font-medium tracking-tight">Enterprise</h3>
                <p class="mt-1 text-sm text-neutral-500">1.400+ relatórios/mês</p>
                <p class="mt-6 text-4xl font-medium tracking-tight">1.400+ <span
                        class="text-base font-normal text-neutral-500">relatórios/mês</span></p>
                <ul class="mt-6 flex-1 space-y-3 text-sm text-neutral-600">
                    <li>· Volume sob medida</li>
                    <li>· Multi-escola avançado</li>
                    <li>· Onboarding dedicado</li>
                    <li>· Comercial sob consulta</li>
                </ul>
                <a href="mailto:contato@gabometro.com.br"
                    class="mt-8 block rounded-lg py-2.5 text-center text-sm font-medium shadow-xs"
                    :class="plan === 'enterprise' ? 'btn-brand' : 'btn-outline'">
                    Falar com vendas
                </a>
            </div>
        </div>
    </section>

    @once
        @push('scripts')
            {{-- se não usar @stack, cole o script antes de </body> --}}
        @endpush
    @endonce

    <script>
        function planCalculator() {
            return {
                students: 120,
                assessments: 2,

                get reports() {
                    return this.students * this.assessments;
                },

                get plan() {
                    if (this.reports <= 300) return 'starter';
                    if (this.reports <= 900) return 'premium';
                    return 'enterprise';
                },

                get planLabel() {
                    return {
                        starter: 'Starter',
                        premium: 'Premium',
                        enterprise: 'Enterprise',
                    } [this.plan];
                },

                formatNumber(n) {
                    return new Intl.NumberFormat('pt-BR').format(n);
                },
            };
        }
    </script>

    {{-- FAQ --}}
    <section class="relative boxed border-b border-[var(--border)] py-20">
        <div class="corner-dot left"></div>
        <div class="corner-dot right"></div>
        <div class="mx-auto max-w-3xl">
            <h2 class="text-center text-3xl tracking-tighter">Perguntas frequentes</h2>
            <dl class="mt-12 space-y-4">
                @foreach ([['q' => 'Preciso mudar a planilha que a escola já usa?', 'a' => 'Recomendamos o modelo Gabômetro para garantir colunas de matrícula, e-mail e disciplinas. Você baixa o arquivo direto na lista de simulados.'], ['q' => 'Os e-mails saem de qual remetente?', 'a' => 'Do SMTP configurado pela própria escola (Gmail, Outlook, provedor institucional, etc.).'], ['q' => 'Professores podem editar dados?', 'a' => 'Não. Professores visualizam; criar, editar e excluir ficam com admin e coordenação.'], ['q' => 'Quando entra o pagamento?', 'a' => 'O MVP já roda o fluxo completo. Cobrança via Stripe entra na próxima etapa; por ora você pode criar a conta e usar o painel.']] as $faq)
                    <div class="rounded-lg border border-[var(--border)] p-5">
                        <dt class="font-medium tracking-tight">{{ $faq['q'] }}</dt>
                        <dd class="mt-2 text-sm text-neutral-500">{{ $faq['a'] }}</dd>
                    </div>
                @endforeach
            </dl>
        </div>
    </section>

    {{-- Final CTA --}}
    <section class="relative boxed border-b border-[var(--border)] py-20">
        <div class="corner-dot left"></div>
        <div class="corner-dot right"></div>
        <div class="mx-auto max-w-3xl text-center">
            <h2 class="text-3xl tracking-tighter sm:text-4xl">Pronto para enxugar o pós-simulado?</h2>
            <p class="mt-3 text-neutral-500">
                Cadastre a escola, importe a primeira planilha e envie os relatórios no mesmo dia.
            </p>
            <a href="{{ url('/admin/register') }}"
                class="btn-brand mt-8 inline-flex h-12 items-center justify-center rounded-lg px-6 text-base font-medium shadow-xs">
                Criar conta
            </a>
        </div>
    </section>

    <footer class="boxed py-10">
        <div class="flex flex-col items-center justify-between gap-4 text-sm text-neutral-500 sm:flex-row">
            <p>© {{ date('Y') }} Gabômetro. Relatórios de simulados para escolas.</p>
            <div class="flex gap-6">
                <a href="{{ url('/admin/login') }}" class="hover:text-neutral-800">Entrar</a>
                <a href="#precos" class="hover:text-neutral-800">Preços</a>
            </div>
        </div>
    </footer>
</body>

</html>
