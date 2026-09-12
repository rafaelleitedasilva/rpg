<!DOCTYPE html>
<html lang="pt-BR">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Guildhall — Sua mesa de RPG</title>
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="gh-landing" x-data="{ mobileOpen: false }">
        <header class="pub-nav">
            <div class="pub-nav-inner">
                <a href="{{ url('/') }}" class="pub-brand">
                    <span class="pub-brand-mark">G</span>
                    <span class="pub-brand-name">Guildhall</span>
                </a>

                <nav class="pub-nav-links" aria-label="Navegação principal">
                    <a href="#recursos" class="pub-nav-link">Recursos <x-gh-icon name="chevron-down" /></a>
                    <a href="#" class="pub-nav-link">Como funciona</a>
                    <a href="#" class="pub-nav-link">Preços</a>
                </nav>

                <div class="pub-nav-actions">
                    @auth
                        <a href="{{ url('/dashboard') }}" class="pub-btn pub-btn-gold">Ir para o painel</a>
                    @else
                        @if (Route::has('login'))
                            <a href="{{ route('login') }}" class="pub-nav-link">Entrar <x-gh-icon name="chevron-down" /></a>
                        @endif
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="pub-btn pub-btn-gold">Criar conta</a>
                        @endif
                    @endauth
                </div>

                <button type="button" class="pub-nav-toggle" @click="mobileOpen = !mobileOpen" aria-label="Abrir menu">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                        <path :class="{ hidden: mobileOpen }" stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{ hidden: !mobileOpen }" class="hidden" stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <div class="pub-mobile-menu" x-show="mobileOpen" x-cloak>
                <a href="#recursos" class="pub-nav-link" @click="mobileOpen = false">Recursos</a>
                <a href="#" class="pub-nav-link" @click="mobileOpen = false">Como funciona</a>
                <a href="#" class="pub-nav-link" @click="mobileOpen = false">Preços</a>

                <div class="pub-nav-actions">
                    @auth
                        <a href="{{ url('/dashboard') }}" class="pub-btn pub-btn-gold">Ir para o painel</a>
                    @else
                        @if (Route::has('login'))
                            <a href="{{ route('login') }}" class="pub-btn pub-btn-outline">Entrar</a>
                        @endif
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="pub-btn pub-btn-gold">Criar conta</a>
                        @endif
                    @endauth
                </div>
            </div>
        </header>

        <main>
            <section class="pub-hero">
                <div>
                    <p class="pub-eyebrow">Sua mesa de RPG</p>
                    <h1 class="pub-hero-title">Sua mesa.<br>Suas aventuras.<br>Em um só lugar.</h1>
                    <p class="pub-hero-text">
                        Organize personagens, magias e campanhas em uma plataforma completa, feita para jogadores e mestres.
                    </p>

                    <div class="pub-hero-actions">
                        @auth
                            <a href="{{ url('/dashboard') }}" class="pub-btn pub-btn-gold">Ir para o painel <x-gh-icon name="arrow-right" /></a>
                        @else
                            <a href="{{ route('register') }}" class="pub-btn pub-btn-gold">Criar minha conta <x-gh-icon name="arrow-right" /></a>
                        @endauth
                        <a href="#recursos" class="pub-btn pub-btn-outline">Conhecer o Guildhall</a>
                    </div>
                </div>

                {{--
                    Espaço reservado para a imagem do herói (ex.: castelo visto por um arco de pedra).
                    Quando a imagem chegar, salve-a em public/images/landing/hero.jpg e troque o
                    conteúdo abaixo por: <img src="{{ asset('images/landing/hero.jpg') }}" alt="">
                --}}
                <div class="pub-image-placeholder pub-hero-image">
                    @if (file_exists(public_path('images/landing/hero.jpg')))
                        <img src="{{ asset('images/landing/hero.jpg') }}" alt="">
                    @else
                        <div class="pub-image-placeholder-note">
                            <x-gh-icon name="scroll" />
                            <span>Imagem em breve</span>
                        </div>
                    @endif
                </div>
            </section>

            <section id="recursos" class="pub-features">
                <div class="pub-features-header">
                    <h2 class="pub-features-title">Tudo que sua mesa precisa</h2>
                    <p class="pub-features-text">Ferramentas completas para você criar, gerenciar e viver suas histórias.</p>
                </div>

                <div class="pub-features-grid">
                    <div class="pub-feature-card">
                        <span class="pub-feature-icon"><x-gh-icon name="shield" /></span>
                        <h3 class="pub-feature-title">Fichas de personagem</h3>
                        <p class="pub-feature-text">Crie e personalize seus personagens com facilidade.</p>
                    </div>
                    <div class="pub-feature-card">
                        <span class="pub-feature-icon"><x-gh-icon name="sparkles" /></span>
                        <h3 class="pub-feature-title">Magias e equipamentos</h3>
                        <p class="pub-feature-text">Pesquise e gerencie magias, itens e habilidades.</p>
                    </div>
                    <div class="pub-feature-card">
                        <span class="pub-feature-icon"><x-gh-icon name="scroll" /></span>
                        <h3 class="pub-feature-title">Campanhas</h3>
                        <p class="pub-feature-text">Organize suas aventuras, sessões e encontros.</p>
                    </div>
                    <div class="pub-feature-card">
                        <span class="pub-feature-icon"><x-gh-icon name="sword" /></span>
                        <h3 class="pub-feature-title">Para mestres</h3>
                        <p class="pub-feature-text">Ferramentas para criar cenários, NPCs e muito mais.</p>
                    </div>
                </div>
            </section>
        </main>

        <footer class="pub-footer">
            <div class="pub-footer-inner">
                <a href="{{ url('/') }}" class="pub-brand">
                    <span class="pub-brand-mark">G</span>
                    <span class="pub-brand-name">Guildhall</span>
                </a>
                <p>&copy; {{ date('Y') }} Guildhall. Todos os direitos reservados.</p>
            </div>
        </footer>
    </body>
</html>
