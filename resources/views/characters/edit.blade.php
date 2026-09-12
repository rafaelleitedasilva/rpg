<x-character-sheet-layout x-data="{ tab: 'identity', dirty: false, saving: false, secondaryClass: {{ \Illuminate\Support\Js::from(old('secondary_class', $character->secondary_class ?? '')) }} }" title="Editar {{ $character->name }}">
    <x-slot name="sidebar">
        <a href="{{ route('characters.index') }}" class="gh-back-link"><x-gh-icon name="arrow-left"/> Voltar para as fichas</a>

        <div class="gh-identity-card">
            @if ($character->portrait_url)
                <img src="{{ $character->portrait_url }}" alt="" class="gh-identity-portrait">
            @else
                <span class="gh-identity-placeholder"><x-gh-icon name="user"/></span>
            @endif
            <div>
                <p class="gh-identity-name">{{ $character->name }}</p>
                <p class="gh-identity-meta">{{ $character->race }} · {{ $character->class }} · Nível {{ $character->level }}</p>
            </div>
        </div>

        <nav class="gh-section-nav" aria-label="Seções da ficha">
            @foreach (include resource_path('views/characters/partials/sections.php') as $key => $section)
                <button
                    type="button"
                    id="tab-{{ $key }}"
                    @click="tab = '{{ $key }}'"
                    :class="tab === '{{ $key }}' ? 'gh-section-nav-item gh-section-nav-item-active' : 'gh-section-nav-item'"
                    :aria-current="tab === '{{ $key }}' ? 'true' : 'false'"
                    aria-controls="panel-{{ $key }}"
                >
                    <x-gh-icon name="{{ $section['icon'] }}"/>
                    {{ $section['label'] }}
                </button>
            @endforeach
        </nav>

        <select class="gh-select gh-mobile-section-select" x-model="tab" aria-label="Seção da ficha">
            @foreach (include resource_path('views/characters/partials/sections.php') as $key => $section)
                <option value="{{ $key }}">{{ $section['label'] }}</option>
            @endforeach
        </select>
    </x-slot>

    <form action="{{ route('characters.update', $character) }}" method="POST" @submit="saving = true" @input="dirty = true" @change="dirty = true">
        @csrf
        @method('PUT')

        <div class="gh-page-header">
            <div>
                <h2 class="gh-page-title"><x-gh-icon name="user"/> Editar ficha</h2>
                <p class="gh-page-description">Aqui você pode editar as informações do seu personagem.</p>
            </div>
            <div class="gh-page-actions">
                <span class="gh-unsaved-badge" x-show="dirty && !saving" x-cloak>Alterações não salvas</span>
                <a href="{{ route('characters.show', $character) }}" class="gh-btn gh-btn-secondary"><x-gh-icon name="eye"/> Visualizar ficha</a>
                <button type="submit" class="gh-btn gh-btn-primary" :disabled="saving">
                    <x-gh-icon name="save"/>
                    <span x-show="!saving">Salvar alterações</span>
                    <span x-show="saving" x-cloak>Salvando...</span>
                </button>
            </div>
        </div>

        @include('characters.partials.form', ['character' => $character])

        <div class="gh-section-footer">
            <a href="{{ route('characters.show', $character) }}" class="gh-btn gh-btn-secondary">Cancelar</a>
            <button type="submit" class="gh-btn gh-btn-primary" :disabled="saving">
                <x-gh-icon name="save"/>
                <span x-show="!saving">Salvar alterações</span>
                <span x-show="saving" x-cloak>Salvando...</span>
            </button>
        </div>
    </form>
</x-character-sheet-layout>
