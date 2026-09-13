<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title') — Gabômetro</title>
    <meta name="description" content="@yield('meta_description', 'Gabômetro — relatórios de simulados para escolas')">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Instrument+Sans:wght@400;500;600;700&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">
    <style>
        :root {
            --brand: #2563EB;
            --brand-dark: #1D4ED8;
            --border: #E5E5E5;
            --ink: #171717;
        }
        body {
            font-family: 'Instrument Sans', ui-sans-serif, system-ui, sans-serif;
            background: #fff;
            color: var(--ink);
        }
        .mono { font-family: 'JetBrains Mono', ui-monospace, monospace; }
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
        .btn-brand { background: var(--brand); color: #fff; }
        .btn-brand:hover { background: var(--brand-dark); }
        .prose-legal h2 {
            font-size: 1.25rem;
            font-weight: 600;
            letter-spacing: -0.02em;
            margin-top: 2.5rem;
            margin-bottom: 0.75rem;
        }
        .prose-legal h2:first-of-type { margin-top: 0; }
        .prose-legal p, .prose-legal li {
            font-size: 0.975rem;
            line-height: 1.7;
            color: #525252;
        }
        .prose-legal ul { list-style: disc; padding-left: 1.25rem; margin: 0.75rem 0; }
        .prose-legal li { margin: 0.35rem 0; }
        .prose-legal a { color: var(--brand); text-decoration: underline; }
        .prose-legal strong { color: #171717; font-weight: 600; }
    </style>
</head>
<body class="antialiased">
    <header class="sticky top-0 z-50 border-b border-[var(--border)] bg-white/85 backdrop-blur">
        <div class="mx-auto flex h-16 max-w-6xl items-center justify-between px-4 sm:px-6">
            <a href="{{ route('home') }}" class="flex items-center gap-2">
                <img src="{{ asset('assets/gabometro-logo-light.svg') }}" class="w-52" alt="Gabômetro">
            </a>
            <div class="flex items-center gap-3">
                <a href="{{ url('/admin/login') }}" class="hidden text-sm font-medium text-neutral-600 hover:text-neutral-900 sm:inline">Entrar</a>
                <a href="{{ url('/admin/register') }}" class="btn-brand rounded-lg px-4 py-2 text-sm font-medium shadow-xs">Começar agora</a>
            </div>
        </div>
    </header>

    <main class="boxed border-b border-[var(--border)] py-16 sm:py-20">
        <div class="mx-auto max-w-3xl">
            <p class="mono text-xs text-[var(--brand)]">@yield('eyebrow')</p>
            <h1 class="mt-2 text-3xl tracking-tighter sm:text-4xl">@yield('heading')</h1>
            <p class="mt-3 text-sm text-neutral-500">@yield('updated')</p>
            <div class="prose-legal mt-10">
                @yield('content')
            </div>
        </div>
    </main>

    <footer class="boxed py-10">
        <div class="flex flex-col items-center justify-between gap-4 text-sm text-neutral-500 sm:flex-row">
            <p>© {{ date('Y') }} Gabômetro. Relatórios de simulados para escolas.</p>
            <div class="flex flex-wrap justify-center gap-6">
                <a href="{{ route('privacy') }}" class="hover:text-neutral-800">Privacidade</a>
                <a href="{{ route('terms') }}" class="hover:text-neutral-800">Termos</a>
                <a href="{{ url('/admin/login') }}" class="hover:text-neutral-800">Entrar</a>
            </div>
        </div>
    </footer>
</body>
</html>