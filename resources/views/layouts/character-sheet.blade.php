<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ $title ?? 'Ficha de personagem' }} — Guildhall</title>

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="gh-sheet" {{ $attributes }}>
        <header class="gh-header">
            <div class="gh-header-inner">
                <a href="{{ route('dashboard') }}" class="gh-brand">
                    <span class="gh-brand-mark">G</span>
                    <span class="gh-brand-name">Guildhall</span>
                </a>

                <nav class="gh-nav" aria-label="Navegação principal">
                    <a href="{{ route('dashboard') }}" class="gh-nav-link" @if (request()->routeIs('dashboard')) aria-current="page" @endif>Dashboard</a>
                    <a href="{{ route('characters.index') }}" class="gh-nav-link gh-nav-link-active" aria-current="page">Fichas</a>
                    <a href="{{ route('spells.index') }}" class="gh-nav-link" @if (request()->routeIs('spells.*')) aria-current="page" @endif>Magias</a>
                    <a href="{{ route('campaigns.index') }}" class="gh-nav-link" @if (request()->routeIs('campaigns.*')) aria-current="page" @endif>Campanhas</a>
                    <a href="{{ route('friends.index') }}" class="gh-nav-link" @if (request()->routeIs('friends.*')) aria-current="page" @endif>Amigos</a>
                </nav>

                <div class="gh-header-actions">
                    <button type="button" class="gh-icon-button" aria-label="Buscar">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><circle cx="11" cy="11" r="7"/><path d="m20 20-3.4-3.4" stroke-linecap="round"/></svg>
                    </button>
                    <button type="button" class="gh-icon-button" aria-label="Notificações">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path d="M6 8a6 6 0 1 1 12 0c0 4 1.5 5.5 1.5 5.5H4.5S6 12 6 8Z" stroke-linejoin="round"/><path d="M10 19a2 2 0 0 0 4 0" stroke-linecap="round"/></svg>
                    </button>

                    @php
                        $initials = collect(explode(' ', auth()->user()->name))
                            ->filter()
                            ->take(2)
                            ->map(fn ($part) => mb_strtoupper(mb_substr($part, 0, 1)))
                            ->implode('');
                    @endphp
                    <x-dropdown align="right" width="48" content-classes="py-1 gh-dropdown-menu">
                        <x-slot name="trigger">
                            <button type="button" class="gh-user-trigger">
                                <span class="gh-avatar">{{ $initials }}</span>
                                <span class="gh-user-name">{{ auth()->user()->name }}</span>
                                <svg class="gh-chevron" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path d="m5 7 5 6 5-6" stroke-linecap="round" stroke-linejoin="round"/></svg>
                            </button>
                        </x-slot>
                        <x-slot name="content">
                            <a href="{{ route('profile.edit') }}" class="gh-dropdown-link">Perfil</a>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="gh-dropdown-link gh-dropdown-link-danger">Sair</button>
                            </form>
                        </x-slot>
                    </x-dropdown>
                </div>
            </div>
        </header>

        <div class="gh-body">
            @isset($sidebar)
                <aside class="gh-sidebar">
                    {{ $sidebar }}
                </aside>
            @endisset

            <main class="gh-main">
                @if (session('status'))
                    <div class="gh-alert gh-alert-success" role="status">
                        <svg viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path d="m4 10 4 4 8-9" stroke-linecap="round" stroke-linejoin="round"/></svg>
                        <span>{{ session('status') }}</span>
                    </div>
                @endif

                {{ $slot }}
            </main>
        </div>
    </body>
</html>
