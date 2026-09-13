<x-character-sheet-layout title="Meu perfil">
    <x-slot name="sidebar">
        <p class="gh-sidebar-eyebrow">Configurações</p>

        <nav class="gh-section-nav" aria-label="Configurações da conta">
            <a href="#perfil" class="gh-section-nav-item gh-section-nav-item-active">
                <x-gh-icon name="user"/> Perfil
            </a>
            <a href="#seguranca" class="gh-section-nav-item">
                <x-gh-icon name="shield"/> Segurança
            </a>
            <span class="gh-section-nav-item gh-section-nav-item-disabled">
                <x-gh-icon name="bell"/> Notificações <span class="gh-badge">Em breve</span>
            </span>
            <span class="gh-section-nav-item gh-section-nav-item-disabled">
                <x-gh-icon name="sliders"/> Preferências <span class="gh-badge">Em breve</span>
            </span>
        </nav>
    </x-slot>

    <div class="gh-page-hero">
        {{--
            Espaço reservado para a imagem de fundo do cabeçalho (ex.: mesa de
            taverna iluminada por velas). Quando a imagem chegar, salve-a em
            public/images/profile/hero.jpg e troque o conteúdo abaixo por:
            <img src="{{ asset('images/profile/hero.jpg') }}" alt="">
        --}}
        <div class="gh-hero-image">
            @if (file_exists(public_path('images/profile/hero.jpg')))
                <img src="{{ asset('images/profile/hero.jpg') }}" alt="">
            @else
                <div class="gh-hero-image-note"><x-gh-icon name="scroll"/></div>
            @endif
        </div>

        <div class="gh-page-hero-content">
            <p class="gh-eyebrow">Configurações da conta</p>
            <h1 class="gh-hero-title">Meu perfil</h1>
            <p class="gh-hero-subtitle">Mantenha suas informações atualizadas para uma experiência ainda melhor no Guildhall.</p>
        </div>
    </div>

    <div id="perfil">
        @include('profile.partials.update-profile-information-form')
    </div>

    <div id="seguranca" class="mt-6">
        @include('profile.partials.update-password-form')
        @include('profile.partials.delete-user-form')
    </div>
</x-character-sheet-layout>
