<x-app-layout>
    <x-slot name="header">
        <h2 class="tavern-display text-4xl font-black tracking-[0.08em] text-[#f5e7c8]">Catálogo de Magias</h2>
    </x-slot>

    <div class="spell-page mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
        <div class="spell-toolbar rpg-card">
            <form id="spell-filter-form" method="GET" class="spell-filters">
                <div class="spell-field">
                    <label for="search">Buscar</label>
                    <input id="search" name="search" value="{{ $search ?? '' }}" class="tavern-input" placeholder="Nome ou descrição...">
                </div>

                <div class="spell-field">
                    <label for="level">Nível</label>
                    <select id="level" name="level" class="tavern-select">
                        <option value="">Todos</option>
                        @for ($i = 0; $i <= 9; $i++)
                            <option value="{{ $i }}" @selected($level == $i)>{{ $i }}</option>
                        @endfor
                    </select>
                </div>

                <div class="spell-field">
                    <label for="class">Classe</label>
                    <select id="class" name="class" class="tavern-select">
                        <option value="">Todas</option>
                        <option value="Wizard" @selected($class === 'Wizard')>Mago</option>
                        <option value="Cleric" @selected($class === 'Cleric')>Clérigo</option>
                        <option value="Druid" @selected($class === 'Druid')>Druida</option>
                        <option value="Bard" @selected($class === 'Bard')>Bardo</option>
                        <option value="Paladin" @selected($class === 'Paladin')>Paladino</option>
                        <option value="Sorcerer" @selected($class === 'Sorcerer')>Feiticeiro</option>
                        <option value="Warlock" @selected($class === 'Warlock')>Bruxo</option>
                        <option value="Ranger" @selected($class === 'Ranger')>Patrulheiro</option>
                    </select>
                </div>

                <div class="spell-field">
                    <label for="race">Raça</label>
                    <select id="race" name="race" class="tavern-select">
                        <option value="">Todas</option>
                        <option value="Human" @selected($race === 'Human')>Humano</option>
                        <option value="Elf" @selected($race === 'Elf')>Elfo</option>
                        <option value="Dwarf" @selected($race === 'Dwarf')>Anão</option>
                        <option value="Tiefling" @selected($race === 'Tiefling')>Tiefling</option>
                        <option value="Dragonborn" @selected($race === 'Dragonborn')>Draconato</option>
                        <option value="Half-Elf" @selected($race === 'Half-Elf')>Meio-Elfo</option>
                        <option value="Gnome" @selected($race === 'Gnome')>Gnomo</option>
                    </select>
                </div>

                <div class="flex justify-end gap-3 md:justify-start">
                    <button type="button" id="clear-spells" class="wood-button wood-button--ghost">Limpar</button>
                    <button type="submit" class="wood-button">Filtrar</button>
                </div>
            </form>
        </div>

        <div class="spell-list">
            @forelse ($spells as $spell)
                <article class="spell-card">
                    <div class="spell-topline">
                        <div>
                            <span class="spell-level">Nível {{ $spell->level }}</span>
                            <h3 class="spell-name">
                                {{ App\Support\SpellTranslator::name($spell->name) }}
                                <span class="spell-name-english">{{ $spell->name }}</span>
                            </h3>
                        </div>
                        <div class="spell-meta">
                            <span class="spell-pill">{{ App\Support\SpellTranslator::school($spell->school) }}</span>
                            <span class="spell-pill">{{ App\Support\SpellTranslator::range($spell->range) }}</span>
                            <span class="spell-pill">{{ App\Support\SpellTranslator::duration($spell->duration) }}</span>
                        </div>
                    </div>

                    <div class="spell-grid">
                        <div>
                            <strong>Tempo</strong><br>
                            {{ App\Support\SpellTranslator::castingTime($spell->casting_time) }}
                        </div>
                        <div>
                            <strong>Componentes</strong><br>
                            {{ App\Support\SpellTranslator::components($spell->components) }}
                        </div>
                        <div>
                            <strong>Classes</strong><br>
                            {{ App\Support\SpellTranslator::classList($spell->classes ?? []) }}
                        </div>
                    </div>

                    @if (! empty($spell->races ?? []))
                        <div class="mt-3 text-sm text-[#e7d9bd]">
                            <strong class="mr-2 font-bold text-[#fff5dd]">Raças:</strong>
                            {{ App\Support\SpellTranslator::raceList($spell->races ?? []) }}
                        </div>
                    @endif

                    <p class="spell-description">{{ App\Support\SpellTranslator::description($spell->description) }}</p>
                </article>
            @empty
                <div class="spell-card text-center text-[#e7d9bd]">Nenhuma magia encontrada com os filtros atuais.</div>
            @endforelse
        </div>

        <div class="spell-pagination">
            {{ $spells->appends(request()->query())->links('vendor.pagination.tavern') }}
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const form = document.getElementById('spell-filter-form');
            const searchInput = document.getElementById('search');
            const clearButton = document.getElementById('clear-spells');
            const selects = form.querySelectorAll('select');
            let searchTimer = null;

            const submitForm = () => {
                form.requestSubmit ? form.requestSubmit() : form.submit();
            };

            selects.forEach((select) => {
                select.addEventListener('change', submitForm);
            });

            searchInput.addEventListener('input', () => {
                const value = searchInput.value.trim();
                clearTimeout(searchTimer);

                if (value.length === 0 || value.length >= 3) {
                    searchTimer = setTimeout(submitForm, 250);
                }
            });

            clearButton.addEventListener('click', () => {
                form.reset();
                submitForm();
            });
        });
    </script>
</x-app-layout>
