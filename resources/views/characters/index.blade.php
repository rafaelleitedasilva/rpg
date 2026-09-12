<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-wrap items-start justify-between gap-4">
            <div>
                <h2 class="tavern-display text-3xl tracking-tight text-[#f7efe3]">Suas fichas</h2>
                <p class="mt-1 text-sm text-[#c9b79c]">Gerencie seus personagens e continue suas aventuras.</p>
            </div>
            <a href="{{ route('characters.create') }}" class="golden-button !normal-case !tracking-normal gap-2">
                <span class="text-base leading-none">+</span> Nova ficha
            </a>
        </div>
    </x-slot>

    <div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
        @if ($characters->isEmpty())
            <div class="rpg-card p-10 text-center">
                <p class="text-xl font-bold text-white">Você ainda não possui personagens</p>
                <p class="mt-2 text-[#c9b79c]">Crie sua primeira ficha de D&amp;D 5e e comece sua aventura.</p>
                <a href="{{ route('characters.create') }}" class="golden-button !normal-case !tracking-normal mt-6 gap-2">
                    <span class="text-base leading-none">+</span> Criar primeira ficha
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
                <div class="rpg-card p-4 sm:p-5">
                    <div class="flex flex-col gap-3 sm:flex-row sm:items-center">
                        <div class="relative flex-1">
                            <svg class="pointer-events-none absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-[#c9b79c]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35m1.85-5.4a7.25 7.25 0 11-14.5 0 7.25 7.25 0 0114.5 0z" />
                            </svg>
                            <input
                                type="search"
                                x-model="search"
                                placeholder="Buscar por nome, classe, raça ou campanha..."
                                class="input-shell !pl-10"
                            >
                        </div>

                        <div class="relative" @click.outside="filtersOpen = false">
                            <button
                                type="button"
                                @click="filtersOpen = !filtersOpen"
                                class="secondary-button w-full gap-2 sm:w-auto"
                                :class="hasActiveFilters ? '!border-[#d5a75d]/70' : ''"
                            >
                                Filtros
                                <svg class="h-3.5 w-3.5 transition" :class="filtersOpen ? 'rotate-180' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>

                            <div
                                x-show="filtersOpen"
                                x-cloak
                                x-transition
                                class="absolute right-0 z-20 mt-2 w-72 space-y-3 rpg-card p-4"
                            >
                                <div>
                                    <label class="label-block text-xs">Campanha</label>
                                    <select x-model="campaign" class="input-shell">
                                        <option value="all">Todas</option>
                                        @foreach ($filterOptions['campaigns'] as $campaign)
                                            <option value="{{ $campaign }}">{{ $campaign }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label class="label-block text-xs">Classe</label>
                                    <select x-model="klass" class="input-shell">
                                        <option value="all">Todas</option>
                                        @foreach ($filterOptions['classes'] as $class)
                                            <option value="{{ $class }}">{{ $class }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label class="label-block text-xs">Raça</label>
                                    <select x-model="race" class="input-shell">
                                        <option value="all">Todas</option>
                                        @foreach ($filterOptions['races'] as $race)
                                            <option value="{{ $race }}">{{ $race }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label class="label-block text-xs">Nível</label>
                                    <select x-model="level" class="input-shell">
                                        <option value="all">Todos</option>
                                        @foreach ($filterOptions['levels'] as $level)
                                            <option value="{{ $level }}">Nível {{ $level }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <button type="button" @click="reset()" x-show="hasActiveFilters" class="text-xs font-semibold text-[#f0d39a] hover:underline">
                                    Limpar filtros
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Results summary --}}
                <div class="mt-5 flex items-center justify-between text-sm text-[#c9b79c]">
                    <p>
                        <span x-text="resultsLabel"></span>
                        @if ($campaignsCount > 0)
                            <span> em {{ $campaignsCount }} {{ Str::plural('campanha', $campaignsCount) }}</span>
                        @endif
                    </p>
                    <button type="button" @click="reset()" x-show="hasActiveFilters" x-cloak class="text-[#f0d39a] hover:underline">
                        Limpar busca e filtros
                    </button>
                </div>

                {{-- Character grid --}}
                <div class="mt-4 grid gap-6" style="grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));">
                    @foreach ($characters as $character)
                        <article x-show="isVisible({{ $character->id }})" x-cloak class="rpg-card flex flex-col p-5">
                            <div class="flex items-start justify-between gap-3">
                                <div class="flex min-w-0 items-center gap-3">
                                    @if ($character->portrait_url)
                                        <img
                                            src="{{ $character->portrait_url }}"
                                            alt="Retrato de {{ $character->name }}"
                                            class="h-14 w-14 flex-shrink-0 rounded-2xl border border-[#a67a47]/40 object-cover"
                                        >
                                    @else
                                        <span class="flex h-14 w-14 flex-shrink-0 items-center justify-center rounded-2xl border border-[#a67a47]/40 bg-gradient-to-br from-[#f1d8a3] via-[#d5a75d] to-[#8a5d32] text-xl font-black text-[#1f160f]">
                                            {{ mb_strtoupper(mb_substr($character->name, 0, 1)) }}
                                        </span>
                                    @endif
                                    <div class="min-w-0">
                                        <h3 class="truncate text-xl font-black leading-tight text-white">{{ $character->name }}</h3>
                                        <p class="truncate text-sm text-[#c9b79c]">{{ $character->race }} · {{ $character->class }}</p>
                                    </div>
                                </div>

                                <div class="flex flex-shrink-0 items-center gap-1.5">
                                    <span class="rpg-badge">Nível {{ $character->level }}</span>

                                    <div class="relative" x-data="{ menuOpen: false }" @click.outside="menuOpen = false">
                                        <button
                                            type="button"
                                            @click="menuOpen = !menuOpen"
                                            class="flex h-8 w-8 items-center justify-center rounded-full text-[#c9b79c] transition hover:bg-[#2a201a] hover:text-white"
                                            aria-label="Mais ações para {{ $character->name }}"
                                        >
                                            <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M10 6a2 2 0 100-4 2 2 0 000 4zm0 6a2 2 0 100-4 2 2 0 000 4zm0 6a2 2 0 100-4 2 2 0 000 4z" />
                                            </svg>
                                        </button>

                                        <div
                                            x-show="menuOpen"
                                            x-cloak
                                            x-transition
                                            class="absolute right-0 z-10 mt-2 w-44 rpg-card p-1.5 text-sm"
                                        >
                                            <a href="{{ route('characters.edit', $character) }}" class="block rounded-lg px-3 py-2 text-[#f0e5cf] hover:bg-[#2a201a]">
                                                Editar ficha
                                            </a>
                                            <form
                                                method="POST"
                                                action="{{ route('characters.destroy', $character) }}"
                                                onsubmit="return confirm('Excluir {{ $character->name }}? Essa ação não pode ser desfeita.');"
                                            >
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="block w-full rounded-lg px-3 py-2 text-left text-[#e08a78] hover:bg-[#2a201a]">
                                                    Excluir ficha
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            @if ($character->campaign_name)
                                <p class="mt-3 inline-flex w-fit items-center gap-1.5 text-xs font-semibold uppercase tracking-wide text-[#f0d39a]">
                                    <span class="h-1.5 w-1.5 rounded-full bg-[#d5a75d]"></span>
                                    {{ $character->campaign_name }}
                                </p>
                            @endif

                            <div class="mt-4 grid grid-cols-3 gap-2 text-sm">
                                <div class="stat-chip">
                                    <p class="text-xs text-[#c9b79c]">PV</p>
                                    <p class="mt-1 font-bold text-white">{{ $character->current_hp }}/{{ $character->max_hp }}</p>
                                </div>
                                <div class="stat-chip">
                                    <p class="text-xs text-[#c9b79c]">CA</p>
                                    <p class="mt-1 font-bold text-white">{{ $character->armor_class }}</p>
                                </div>
                                <div class="stat-chip">
                                    <p class="text-xs text-[#c9b79c]">Iniciativa</p>
                                    <p class="mt-1 font-bold text-white">{{ $character->initiative >= 0 ? '+' : '' }}{{ $character->initiative }}</p>
                                </div>
                            </div>

                            @php($xp = $character->xpProgress())
                            <div class="mt-4">
                                <div class="flex items-center justify-between text-[11px] uppercase tracking-wide text-[#c9b79c]">
                                    <span>{{ $xp['maxed'] ? 'Nível máximo' : 'XP' }}</span>
                                    <span>{{ number_format($character->experience ?? 0, 0, ',', '.') }} XP</span>
                                </div>
                                <div class="mt-1 h-1.5 w-full overflow-hidden rounded-full bg-black/40">
                                    <div class="h-full rounded-full bg-gradient-to-r from-[#d5a75d] to-[#f2ddaf]" style="width: {{ $xp['percent'] }}%"></div>
                                </div>
                            </div>

                            <a href="{{ route('characters.show', $character) }}" class="golden-button mt-5 w-full justify-center text-sm">
                                Abrir ficha
                            </a>
                        </article>
                    @endforeach
                </div>

                {{-- No results after filtering --}}
                <div x-show="filteredIds.length === 0" x-cloak class="rpg-card mt-6 p-10 text-center">
                    <p class="text-lg font-semibold text-white">Nenhuma ficha encontrada</p>
                    <p class="mt-2 text-sm text-[#c9b79c]">Tente alterar os filtros ou buscar outro personagem.</p>
                    <button type="button" @click="reset()" class="secondary-button mt-4">Limpar busca e filtros</button>
                </div>
            </div>
        @endif
    </div>
</x-app-layout>
