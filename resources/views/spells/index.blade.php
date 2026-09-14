@php
    use App\Support\SpellArtwork;
    use Illuminate\Support\Str;
@endphp

<x-character-sheet-layout title="Magias">
    <x-slot name="sidebar">
        <div class="gh-sidebar-panel">
            <nav class="gh-sidebar-nav" aria-label="Navegação principal">
                <a href="{{ route('dashboard') }}" class="gh-sidebar-nav-link">
                    <x-gh-icon name="home"/> Dashboard
                </a>
                <a href="{{ route('characters.index') }}" class="gh-sidebar-nav-link">
                    <x-gh-icon name="user"/> Fichas
                </a>
                <a href="{{ route('spells.index') }}" class="gh-sidebar-nav-link gh-sidebar-nav-link-active" aria-current="page">
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
                <p>&ldquo;O verdadeiro poder não está apenas em lançar magias, mas em saber quando usá-las.&rdquo;</p>
            </div>
        </div>
    </x-slot>

    <div class="gh-page-hero">
        {{--
            Espaço reservado para a imagem de fundo (ex.: grimório aberto com
            velas). Quando a imagem chegar, salve-a em
            public/images/spells/hero.jpg — ela substitui o ícone automaticamente.
        --}}
        <div class="gh-hero-image">
            @if (file_exists(public_path('images/spells/hero.jpg')))
                <img src="{{ asset('images/spells/hero.jpg') }}" alt="">
            @else
                <div class="gh-hero-image-note"><x-gh-icon name="book"/></div>
            @endif
        </div>

        <div class="gh-page-hero-content">
            <p class="gh-eyebrow">Catálogo de magias</p>
            <h1 class="gh-hero-title">Magias</h1>
            <p class="gh-hero-subtitle">Explore, pesquise e encontre a magia perfeita para cada situação. Filtre por nível, escola, classe e muito mais.</p>
        </div>
    </div>

    <div class="gh-card" style="margin-top: 1.5rem;">
        <form id="spell-filter-form" method="GET" class="gh-filter-row">
            <label class="gh-filter-search">
                <x-gh-icon name="search"/>
                <input type="text" id="search" name="search" value="{{ $search }}" placeholder="Buscar magia por nome, descrição ou efeito...">
            </label>

            <label class="gh-filter">
                <span class="gh-filter-icon"><x-gh-icon name="zap"/></span>
                <span class="gh-filter-body">
                    <span class="gh-filter-label">Nível</span>
                    <select id="level" name="level" class="gh-filter-select">
                        <option value="">Todos</option>
                        @for ($i = 0; $i <= 9; $i++)
                            <option value="{{ $i }}" @selected((string) $level === (string) $i)>{{ $i === 0 ? 'Truque' : 'Nível '.$i }}</option>
                        @endfor
                    </select>
                </span>
                <x-gh-icon name="chevron-down" class="gh-filter-chevron"/>
            </label>

            <label class="gh-filter">
                <span class="gh-filter-icon"><x-gh-icon name="book"/></span>
                <span class="gh-filter-body">
                    <span class="gh-filter-label">Escola</span>
                    <select id="school" name="school" class="gh-filter-select">
                        <option value="">Todas</option>
                        @foreach ($schools as $value => $label)
                            <option value="{{ $value }}" @selected($school === $value)>{{ $label }}</option>
                        @endforeach
                    </select>
                </span>
                <x-gh-icon name="chevron-down" class="gh-filter-chevron"/>
            </label>

            <label class="gh-filter">
                <span class="gh-filter-icon"><x-gh-icon name="user"/></span>
                <span class="gh-filter-body">
                    <span class="gh-filter-label">Classe</span>
                    <select id="class" name="class" class="gh-filter-select">
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
                </span>
                <x-gh-icon name="chevron-down" class="gh-filter-chevron"/>
            </label>

            <label class="gh-filter">
                <span class="gh-filter-icon"><x-gh-icon name="people"/></span>
                <span class="gh-filter-body">
                    <span class="gh-filter-label">Raça</span>
                    <select id="race" name="race" class="gh-filter-select">
                        <option value="">Todas</option>
                        <option value="Human" @selected($race === 'Human')>Humano</option>
                        <option value="Elf" @selected($race === 'Elf')>Elfo</option>
                        <option value="Dwarf" @selected($race === 'Dwarf')>Anão</option>
                        <option value="Tiefling" @selected($race === 'Tiefling')>Tiefling</option>
                        <option value="Dragonborn" @selected($race === 'Dragonborn')>Draconato</option>
                        <option value="Half-Elf" @selected($race === 'Half-Elf')>Meio-Elfo</option>
                        <option value="Gnome" @selected($race === 'Gnome')>Gnomo</option>
                    </select>
                </span>
                <x-gh-icon name="chevron-down" class="gh-filter-chevron"/>
            </label>

            <button type="button" id="clear-spells" class="gh-btn gh-btn-primary">
                <x-gh-icon name="filter"/> Limpar filtros
            </button>
        </form>
    </div>

    <div class="gh-spell-results-row">
        <p class="gh-spell-results-count">Encontradas <strong>{{ $spells->total() }}</strong> {{ Str::plural('magia', $spells->total()) }}</p>

        <form method="GET" class="gh-spell-sort" id="spell-sort-form">
            @foreach (['search' => $search, 'level' => $level, 'school' => $school, 'class' => $class, 'race' => $race] as $field => $value)
                @if (filled($value))
                    <input type="hidden" name="{{ $field }}" value="{{ $value }}">
                @endif
            @endforeach
            <label for="sort">Ordenar por</label>
            <select id="sort" name="sort" onchange="this.form.submit()">
                <option value="level" @selected($sort === 'level')>Nível (crescente)</option>
                <option value="level_desc" @selected($sort === 'level_desc')>Nível (decrescente)</option>
                <option value="name" @selected($sort === 'name')>Nome (A-Z)</option>
            </select>
        </form>
    </div>

    <div class="gh-spell-list">
        @forelse ($spells as $spell)
            @php $art = SpellArtwork::for($spell->name, $spell->description, $spell->school); @endphp
            <article class="gh-spell-card" x-data="{ expanded: false, starred: false }">
                <div class="gh-spell-art" style="--art-color: {{ $art['color'] }};">
                    {{--
                        Sem uma fonte confiável e livre de direitos para arte de cada
                        magia (a arte oficial de D&D é propriedade da Wizards of the
                        Coast), cada magia recebe um ícone minimalista gerado a partir
                        do nome/escola (App\Support\SpellArtwork). Se uma imagem real
                        for adicionada em public/images/spells/{slug}.jpg, ela é
                        usada automaticamente no lugar do ícone.
                    --}}
                    @if (file_exists(public_path('images/spells/'.Str::slug($spell->name).'.jpg')))
                        <img src="{{ asset('images/spells/'.Str::slug($spell->name).'.jpg') }}" alt="">
                    @else
                        <x-gh-icon :name="$art['icon']"/>
                    @endif
                </div>

                <div class="gh-spell-body">
                    <div class="gh-spell-topline">
                        <div>
                            <span class="gh-spell-level">{{ $spell->level === 0 ? 'Truque' : 'Nv. '.$spell->level }}</span>
                            <h3 class="gh-spell-name">
                                {{ $spell->nameLabel() }}
                                @if ($spell->translated)
                                    <span class="gh-spell-name-original">{{ $spell->name }}</span>
                                @endif
                            </h3>
                            <div class="gh-spell-pills">
                                <span class="gh-spell-pill"><x-gh-icon name="sparkles"/> {{ $spell->schoolLabel() }}</span>
                                <span class="gh-spell-pill"><x-gh-icon name="diamond"/> {{ $spell->rangeLabel() }}</span>
                                <span class="gh-spell-pill"><x-gh-icon name="calendar"/> {{ $spell->durationLabel() }}</span>
                            </div>
                        </div>

                        <div class="gh-spell-actions">
                            <button
                                type="button"
                                class="gh-spell-favorite"
                                :class="{ 'gh-spell-favorite-active': starred }"
                                @click="starred = !starred"
                                :aria-pressed="starred.toString()"
                            >
                                <x-gh-icon name="star"/>
                                <span x-text="starred ? 'Favoritada' : 'Favoritar'"></span>
                            </button>
                        </div>
                    </div>

                    <p class="gh-spell-description" :class="{ 'gh-spell-description-clamped': ! expanded }">
                        {{ $spell->descriptionLabel() }}
                    </p>

                    <template x-if="expanded">
                        <div>
                            @if (! empty($spell->races ?? []))
                                <p class="gh-spell-races"><strong>Raças:</strong> {{ $spell->raceListLabel() }}</p>
                            @endif
                        </div>
                    </template>

                    <div class="gh-spell-footer">
                        <div class="gh-spell-facts">
                            <div>
                                <p class="gh-spell-fact-label">Tempo</p>
                                <p class="gh-spell-fact-value">{{ $spell->castingTimeLabel() }}</p>
                            </div>
                            <div>
                                <p class="gh-spell-fact-label">Componentes</p>
                                <p class="gh-spell-fact-value">{{ $spell->componentsLabel() }}</p>
                            </div>
                            <div>
                                <p class="gh-spell-fact-label">Classes</p>
                                <p class="gh-spell-fact-value">{{ $spell->classListLabel() }}</p>
                            </div>
                        </div>

                        <button
                            type="button"
                            class="gh-btn gh-btn-primary gh-spell-toggle"
                            :class="{ 'gh-spell-toggle-open': expanded }"
                            @click="expanded = ! expanded"
                        >
                            <x-gh-icon name="eye"/>
                            <span x-text="expanded ? 'Ocultar detalhes' : 'Ver detalhes'"></span>
                        </button>
                    </div>
                </div>
            </article>
        @empty
            <div class="gh-spell-empty">Nenhuma magia encontrada com os filtros atuais.</div>
        @endforelse
    </div>

    <div class="mt-6">
        {{ $spells->links('vendor.pagination.guildhall') }}
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const form = document.getElementById('spell-filter-form');
            const searchInput = document.getElementById('search');
            const clearButton = document.getElementById('clear-spells');
            const selects = form.querySelectorAll('select');
            let searchTimer = null;

            const submitForm = () => form.submit();

            searchInput.addEventListener('input', function () {
                clearTimeout(searchTimer);
                searchTimer = setTimeout(submitForm, 400);
            });

            selects.forEach((select) => select.addEventListener('change', submitForm));

            // Em alguns navegadores, clicar exatamente no ícone da seta (que
            // não é o <select> em si, só um irmão visual dele dentro do
            // <label>) não repassa o clique de forma confiável. Forçamos a
            // abertura do dropdown nesse caso com a API showPicker().
            form.querySelectorAll('.gh-filter').forEach((filter) => {
                const select = filter.querySelector('select');

                if (! select) {
                    return;
                }

                filter.addEventListener('click', function (event) {
                    if (event.target.closest('select')) {
                        return;
                    }

                    if (typeof select.showPicker === 'function') {
                        select.showPicker();
                    } else {
                        select.focus();
                    }
                });
            });

            clearButton.addEventListener('click', function () {
                window.location.href = '{{ route('spells.index') }}';
            });
        });
    </script>
</x-character-sheet-layout>
