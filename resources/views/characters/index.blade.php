@php
    $totalCharacters = $characters->count();
@endphp

<x-character-sheet-layout title="Fichas">
    <x-slot name="sidebar">
        <div class="gh-card">
            <p class="gh-card-title">Resumo</p>
            <div class="mt-4 grid grid-cols-2 gap-3">
                <div class="gh-stat">
                    <p class="gh-stat-label">Fichas</p>
                    <p class="gh-stat-value">{{ $totalCharacters }}</p>
                </div>
                <div class="gh-stat">
                    <p class="gh-stat-label">Campanhas</p>
                    <p class="gh-stat-value">{{ $campaignsCount }}</p>
                </div>
            </div>
        </div>

        <a href="{{ route('characters.create') }}" class="gh-btn gh-btn-primary" style="justify-content:center">
            <x-gh-icon name="save"/> Nova ficha
        </a>
    </x-slot>

    <div class="gh-page-header">
        <div>
            <h2 class="gh-page-title"><x-gh-icon name="user"/> Fichas de personagem</h2>
            <p class="gh-page-description">Gerencie seus personagens e continue suas aventuras.</p>
        </div>
        <div class="gh-page-actions">
            <a href="{{ route('characters.create') }}" class="gh-btn gh-btn-primary">
                <x-gh-icon name="save"/> Nova ficha
            </a>
        </div>
    </div>

    @if ($characters->isEmpty())
        <div class="gh-card gh-empty-state">
            <p>Você ainda não possui personagens</p>
            <p>Crie sua primeira ficha de D&amp;D 5e e comece sua aventura.</p>
            <a href="{{ route('characters.create') }}" class="gh-btn gh-btn-primary" style="margin-top:1.5rem; display:inline-flex">
                <x-gh-icon name="save"/> Criar primeira ficha
            </a>
        </div>
    @else
        <div
            x-data="characterDirectory(@js($characters->map(fn ($character) => [
                'id' => $character->id,
                'name' => $character->name,
                'race' => $character->race,
                'class' => $character->class,
                'campaign' => $character->campaign_name,
                'level' => $character->level,
            ])->values()))"
            x-cloak
        >
            {{-- Search + filters --}}
            <div class="gh-card">
                <div class="flex flex-col gap-3 sm:flex-row sm:items-center">
                    <div class="gh-search-wrap">
                        <svg class="gh-search-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                            <circle cx="11" cy="11" r="7"/><path d="m20 20-3.4-3.4" stroke-linecap="round"/>
                        </svg>
                        <input
                            type="search"
                            x-model="search"
                            placeholder="Buscar por nome, classe, raça ou campanha..."
                            class="gh-input gh-input-with-icon"
                        >
                    </div>

                    <div class="relative" @click.outside="filtersOpen = false">
                        <button
                            type="button"
                            @click="filtersOpen = !filtersOpen"
                            class="gh-btn gh-btn-secondary w-full sm:w-auto"
                            :style="hasActiveFilters ? 'border-color: var(--gh-accent)' : ''"
                        >
                            Filtros
                            <svg class="h-3.5 w-3.5 transition" :class="filtersOpen ? 'rotate-180' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>

                        <div
                            x-show="filtersOpen"
                            x-cloak
                            x-transition
                            class="gh-card absolute right-0 z-20 mt-2 w-72 space-y-3"
                        >
                            <div class="gh-field">
                                <label class="gh-label" for="filter-campaign">Campanha</label>
                                <select id="filter-campaign" x-model="campaign" class="gh-select">
                                    <option value="all">Todas</option>
                                    @foreach ($filterOptions['campaigns'] as $campaign)
                                        <option value="{{ $campaign }}">{{ $campaign }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="gh-field">
                                <label class="gh-label" for="filter-class">Classe</label>
                                <select id="filter-class" x-model="klass" class="gh-select">
                                    <option value="all">Todas</option>
                                    @foreach ($filterOptions['classes'] as $class)
                                        <option value="{{ $class }}">{{ $class }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="gh-field">
                                <label class="gh-label" for="filter-race">Raça</label>
                                <select id="filter-race" x-model="race" class="gh-select">
                                    <option value="all">Todas</option>
                                    @foreach ($filterOptions['races'] as $race)
                                        <option value="{{ $race }}">{{ $race }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="gh-field">
                                <label class="gh-label" for="filter-level">Nível</label>
                                <select id="filter-level" x-model="level" class="gh-select">
                                    <option value="all">Todos</option>
                                    @foreach ($filterOptions['levels'] as $level)
                                        <option value="{{ $level }}">Nível {{ $level }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <button type="button" @click="reset()" x-show="hasActiveFilters" class="gh-link-muted">
                                Limpar filtros
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Results summary --}}
            <div class="mt-5 flex items-center justify-between text-sm" style="color: var(--gh-muted)">
                <p>
                    <span x-text="resultsLabel"></span>
                    @if ($campaignsCount > 0)
                        <span> em {{ $campaignsCount }} {{ Str::plural('campanha', $campaignsCount) }}</span>
                    @endif
                </p>
                <button type="button" @click="reset()" x-show="hasActiveFilters" x-cloak class="gh-link-muted">
                    Limpar busca e filtros
                </button>
            </div>

            {{-- Character grid --}}
            <div class="mt-4 grid gap-5" style="grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));">
                @foreach ($characters as $character)
                    <article x-show="isVisible({{ $character->id }})" x-cloak class="gh-card gh-character-card">
                        <div class="flex items-start justify-between gap-3">
                            <div class="flex min-w-0 items-center gap-3">
                                @if ($character->displayPortraitUrl())
                                    <img src="{{ $character->displayPortraitUrl() }}" alt="" class="gh-character-portrait">
                                @else
                                    <span class="gh-character-placeholder">{{ mb_strtoupper(mb_substr($character->name, 0, 1)) }}</span>
                                @endif
                                <div class="min-w-0">
                                    <h3 class="truncate gh-identity-name">{{ $character->name }}</h3>
                                    <p class="truncate gh-hint">{{ $character->race }} · {{ $character->class }}</p>
                                </div>
                            </div>

                            <div class="flex flex-shrink-0 items-center gap-1.5">
                                <span class="gh-badge gh-badge-accent">Nível {{ $character->level }}</span>

                                <div class="relative" x-data="{ menuOpen: false }" @click.outside="menuOpen = false">
                                    <button
                                        type="button"
                                        @click="menuOpen = !menuOpen"
                                        class="gh-kebab-button"
                                        aria-label="Mais ações para {{ $character->name }}"
                                    >
                                        <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20"><path d="M10 6a2 2 0 100-4 2 2 0 000 4zm0 6a2 2 0 100-4 2 2 0 000 4zm0 6a2 2 0 100-4 2 2 0 000 4z" /></svg>
                                    </button>

                                    <div x-show="menuOpen" x-cloak x-transition class="gh-dropdown-menu absolute right-0 z-10 mt-2 w-44 text-sm">
                                        <a href="{{ route('characters.edit', $character) }}" class="gh-dropdown-link">Editar ficha</a>
                                        <form method="POST" action="{{ route('characters.destroy', $character) }}" onsubmit="return confirm('Excluir {{ $character->name }}? Essa ação não pode ser desfeita.');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="gh-dropdown-link gh-dropdown-link-danger">Excluir ficha</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>

                        @if ($character->campaign_name)
                            <p class="mt-3 inline-flex w-fit items-center gap-1.5 gh-hint" style="text-transform: uppercase; letter-spacing: 0.04em;">
                                <span class="h-1.5 w-1.5 rounded-full" style="background: var(--gh-accent)"></span>
                                {{ $character->campaign_name }}
                            </p>
                        @endif

                        <div class="mt-4 grid grid-cols-3 gap-2">
                            <div class="gh-stat"><p class="gh-stat-label">PV</p><p class="mt-1 text-sm font-bold" style="color: var(--gh-text)">{{ $character->current_hp }}/{{ $character->max_hp }}</p></div>
                            <div class="gh-stat"><p class="gh-stat-label">CA</p><p class="mt-1 text-sm font-bold" style="color: var(--gh-text)">{{ $character->armor_class }}</p></div>
                            <div class="gh-stat"><p class="gh-stat-label">Iniciativa</p><p class="mt-1 text-sm font-bold" style="color: var(--gh-text)">{{ $character->initiative >= 0 ? '+' : '' }}{{ $character->initiative }}</p></div>
                        </div>

                        @php($xp = $character->xpProgress())
                        <div class="mt-4">
                            <div class="flex items-center justify-between gh-hint" style="text-transform: uppercase; letter-spacing: 0.04em;">
                                <span>{{ $xp['maxed'] ? 'Nível máximo' : 'XP' }}</span>
                                <span>{{ number_format($character->experience ?? 0, 0, ',', '.') }} XP</span>
                            </div>
                            <div class="mt-1 gh-xp-track">
                                <div class="gh-xp-fill" style="width: {{ $xp['percent'] }}%"></div>
                            </div>
                        </div>

                        <a href="{{ route('characters.show', $character) }}" class="gh-btn gh-btn-primary mt-5 w-full" style="justify-content:center">
                            Abrir ficha
                        </a>
                    </article>
                @endforeach
            </div>

            {{-- No results after filtering --}}
            <div x-show="filteredIds.length === 0" x-cloak class="gh-card gh-empty-state mt-6">
                <p>Nenhuma ficha encontrada</p>
                <p>Tente alterar os filtros ou buscar outro personagem.</p>
                <button type="button" @click="reset()" class="gh-btn gh-btn-secondary" style="margin-top:1rem">Limpar busca e filtros</button>
            </div>
        </div>
    @endif
</x-character-sheet-layout>
