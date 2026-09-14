<x-character-sheet-layout title="Dashboard">
    <x-slot name="sidebar">
        <div class="gh-sidebar-panel">
            <nav class="gh-sidebar-nav" aria-label="Navegação principal">
                <a href="{{ route('dashboard') }}" class="gh-sidebar-nav-link gh-sidebar-nav-link-active" aria-current="page">
                    <x-gh-icon name="home"/> Dashboard
                </a>
                <a href="{{ route('characters.index') }}" class="gh-sidebar-nav-link">
                    <x-gh-icon name="user"/> Fichas
                </a>
                <a href="{{ route('spells.index') }}" class="gh-sidebar-nav-link">
                    <x-gh-icon name="sparkles"/> Magias
                </a>
                <a href="{{ route('campaigns.index') }}" class="gh-sidebar-nav-link">
                    <x-gh-icon name="flag"/> Campanhas
                </a>
                <a href="{{ route('friends.index') }}" class="gh-sidebar-nav-link">
                    <x-gh-icon name="people"/> Amigos
                </a>
            </nav>

            <div class="gh-sidebar-quote">
                <x-gh-icon name="compass"/>
                <p>&ldquo;Grandes aventuras começam com boas companhias.&rdquo;</p>
            </div>
        </div>
    </x-slot>

    {{-- Hero --}}
    <div class="gh-hero">
        <p class="gh-hero-eyebrow">Bem-vindo de volta,</p>
        <h1 class="gh-hero-title">{{ str(auth()->user()->name)->before(' ') }}</h1>
        <p class="gh-hero-subtitle">Seu espaço para criar, gerenciar e viver suas aventuras.</p>

        {{-- Stat tiles --}}
        <div class="gh-stat-grid">
            @foreach ($stats as $stat)
                @if ($stat['route'])
                    <a href="{{ $stat['route'] }}" class="gh-stat-tile">
                @else
                    <div class="gh-stat-tile" style="cursor: default;">
                @endif
                    <span class="gh-stat-tile-icon"><x-gh-icon :name="$stat['icon']"/></span>
                    <span class="gh-stat-tile-body">
                        <span class="gh-stat-label" style="display:block">{{ $stat['label'] }}</span>
                        <span class="gh-stat-value" style="display:block">{{ $stat['value'] ?? '—' }}</span>
                        <span class="gh-hint" style="display:block; margin-top:0.15rem">{{ $stat['description'] }}</span>
                    </span>
                    <x-gh-icon name="chevron-right" class="gh-stat-tile-chevron"/>
                @if ($stat['route'])
                    </a>
                @else
                    </div>
                @endif
            @endforeach
        </div>
    </div>

    <div class="mt-6 grid items-start gap-6 lg:grid-cols-[1.6fr_1fr]">
        <div class="space-y-6">
            {{-- Campaigns --}}
            <div class="gh-card">
                <div class="flex items-center justify-between gap-3">
                    <h3 class="gh-card-title"><x-gh-icon name="flag"/> Minhas campanhas</h3>
                    <a href="{{ route('campaigns.index') }}" class="gh-link-muted">Ver todas</a>
                </div>

                <div class="mt-4">
                    @forelse ($campaigns as $campaign)
                        @php
                            $players = $campaign->acceptedInvites;
                            $isActive = str($campaign->status)->lower()->contains('andamento');
                        @endphp
                        <a href="{{ route('campaigns.show', $campaign) }}" class="gh-list-row">
                            <span class="gh-list-row-thumb"><x-gh-icon name="flag"/></span>
                            <div class="min-w-0 flex-1">
                                <div class="flex items-center gap-1.5">
                                    <span class="gh-status-dot @if ($isActive) gh-status-dot-active @endif"></span>
                                    <span class="gh-hint">{{ $campaign->status }}</span>
                                </div>
                                <p class="truncate gh-identity-name">{{ $campaign->name }}</p>
                                <p class="truncate gh-hint">{{ $campaign->description ?? $campaign->setting ?? 'Ambientação em criação' }}</p>
                            </div>
                            <div class="hidden flex-shrink-0 items-center gap-3 sm:flex">
                                @if ($players->isNotEmpty())
                                    <span class="gh-avatar-stack">
                                        @foreach ($players->take(3) as $invite)
                                            <span>{{ mb_strtoupper(mb_substr($invite->user->name, 0, 1)) }}</span>
                                        @endforeach
                                    </span>
                                @endif
                                <span class="gh-hint">{{ $players->count() }} {{ Str::plural('jogador', $players->count()) }}</span>
                            </div>
                            <x-gh-icon name="chevron-right" class="flex-shrink-0" style="color: var(--gh-muted-2)"/>
                        </a>
                    @empty
                        <div class="gh-empty-state-compact">
                            <p>Você ainda não comanda nenhuma campanha.</p>
                            <a href="{{ route('campaigns.create') }}" class="gh-link-muted">Criar a primeira</a>
                        </div>
                    @endforelse
                </div>
            </div>

            {{-- Recent characters --}}
            <div class="gh-card">
                <div class="flex items-center justify-between gap-3">
                    <h3 class="gh-card-title"><x-gh-icon name="user"/> Suas fichas recentes</h3>
                    <a href="{{ route('characters.index') }}" class="gh-link-muted">Ver todas</a>
                </div>

                <div class="mt-4">
                    @forelse ($recentCharacters as $character)
                        <a href="{{ route('characters.show', $character) }}" class="gh-list-row">
                            @if ($character->displayPortraitUrl())
                                <img src="{{ $character->displayPortraitUrl() }}" alt="" class="gh-list-row-thumb" style="object-fit: cover;">
                            @else
                                <span class="gh-list-row-thumb">{{ mb_strtoupper(mb_substr($character->name, 0, 1)) }}</span>
                            @endif
                            <div class="min-w-0 flex-1">
                                <p class="truncate gh-identity-name">{{ $character->name }}</p>
                                <p class="truncate gh-hint">{{ $character->race }} · {{ $character->class }} · Nv. {{ $character->level }}</p>
                            </div>
                            <span class="gh-badge gh-badge-success flex-shrink-0">Ativa</span>
                        </a>
                    @empty
                        <div class="gh-empty-state-compact">
                            <p>Você ainda não possui personagens.</p>
                            <a href="{{ route('characters.create') }}" class="gh-link-muted">Criar a primeira ficha</a>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        <div>
            {{-- Quick access --}}
            <div class="gh-card">
                <h3 class="gh-card-title"><x-gh-icon name="zap"/> Acesso rápido</h3>
                <div class="mt-4">
                    <a href="{{ route('characters.create') }}" class="gh-quick-link">
                        <x-gh-icon name="user"/> Nova ficha de personagem
                        <x-gh-icon name="chevron-right" class="gh-quick-link-chevron"/>
                    </a>
                    <a href="{{ route('spells.index') }}" class="gh-quick-link">
                        <x-gh-icon name="sparkles"/> Explorar magias
                        <x-gh-icon name="chevron-right" class="gh-quick-link-chevron"/>
                    </a>
                    <a href="{{ route('campaigns.create') }}" class="gh-quick-link">
                        <x-gh-icon name="flag"/> Criar campanha
                        <x-gh-icon name="chevron-right" class="gh-quick-link-chevron"/>
                    </a>
                </div>
            </div>

            {{-- Upcoming sessions — no scheduling feature yet, honest placeholder. --}}
            <div class="gh-card">
                <h3 class="gh-card-title"><x-gh-icon name="calendar"/> Próximas sessões</h3>
                <div class="gh-empty-state-compact">
                    <p>Nenhuma sessão agendada ainda.</p>
                    <p style="margin-top: 0.25rem;">O agendamento de sessões está por vir.</p>
                </div>
            </div>
        </div>
    </div>
</x-character-sheet-layout>
