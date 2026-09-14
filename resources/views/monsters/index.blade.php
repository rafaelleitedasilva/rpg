<x-app-layout>
    <x-slot name="header">
        <h2 class="tavern-display text-4xl font-black tracking-[0.08em] text-[#f5e7c8]">Grimório de Monstros</h2>
        <p class="mt-2 max-w-2xl text-sm text-[#d2b694]">Um bestiário vivo, copiado e traduzido pelos escribas da Guildhall a partir dos arquivos de além-mar. Folheie as páginas e conheça cada criatura.</p>
    </x-slot>

    <div class="spell-page mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
        <div class="spell-toolbar rpg-card">
            <form id="monster-filter-form" method="GET" class="spell-filters">
                <div class="spell-field">
                    <label for="search">Buscar</label>
                    <input id="search" name="search" value="{{ $search ?? '' }}" class="tavern-input" placeholder="Nome da criatura...">
                </div>

                <div class="spell-field">
                    <label for="type">Tipo</label>
                    <select id="type" name="type" class="tavern-select">
                        <option value="">Todos</option>
                        @foreach ($types as $typeOption)
                            <option value="{{ $typeOption }}" @selected($type === $typeOption)>{{ \App\Support\MonsterTranslator::type($typeOption) }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="spell-field">
                    <label for="cr">Nível de Desafio</label>
                    <select id="cr" name="cr" class="tavern-select">
                        <option value="">Todos</option>
                        @foreach ($challengeRatings as $crOption)
                            <option value="{{ $crOption }}" @selected((string) $cr === (string) $crOption)>CR {{ rtrim(rtrim(number_format((float) $crOption, 2, '.', ''), '0'), '.') ?: '0' }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="flex justify-end gap-3 md:justify-start">
                    <button type="button" id="clear-monsters" class="wood-button wood-button--ghost">Limpar</button>
                    <button type="submit" class="wood-button">Filtrar</button>
                </div>
            </form>
        </div>

        <div class="monster-shelf">
            @forelse ($monsters as $monster)
                <a href="{{ route('monsters.show', $monster) }}" class="monster-tome">
                    <div class="monster-tome-portrait">
                        @if ($monster->image_url)
                            <img src="{{ $monster->image_url }}" alt="{{ $monster->displayName() }}" loading="lazy">
                        @else
                            <x-gh-icon name="scroll" />
                        @endif
                    </div>

                    <div class="monster-tome-body">
                        <span class="monster-tome-cr">CR {{ $monster->challengeRatingLabel() }} · {{ $monster->xp }} XP</span>
                        <h3 class="monster-tome-name">{{ $monster->displayName() }}</h3>
                        @if ($monster->translated)
                            <span class="monster-tome-name-english">{{ $monster->name }}</span>
                        @endif

                        <div class="monster-tome-tags">
                            <span class="spell-pill">{{ $monster->sizeLabel() }}</span>
                            <span class="spell-pill">{{ $monster->typeLabel() }}</span>
                        </div>

                        <div class="monster-tome-stats">
                            <span><strong>CA</strong> {{ $monster->armor_class }}</span>
                            <span><strong>PV</strong> {{ $monster->hit_points }}</span>
                        </div>
                    </div>
                </a>
            @empty
                <div class="spell-card text-center text-[#e7d9bd]">Nenhum monstro encontrado com os filtros atuais.</div>
            @endforelse
        </div>

        <div class="spell-pagination">
            {{ $monsters->appends(request()->query())->links('vendor.pagination.tavern') }}
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const form = document.getElementById('monster-filter-form');
            const searchInput = document.getElementById('search');
            const clearButton = document.getElementById('clear-monsters');
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

                if (value.length === 0 || value.length >= 2) {
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
