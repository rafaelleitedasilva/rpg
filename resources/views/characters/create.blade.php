<x-character-sheet-layout x-data="{ tab: 'identity', dirty: false, saving: false, secondaryClass: '' }" title="Nova ficha">
    <x-slot name="sidebar">
        <a href="{{ route('characters.index') }}" class="gh-back-link"><x-gh-icon name="arrow-left"/> Voltar para as fichas</a>

        <div class="gh-identity-card" style="border-style: dashed;">
            <span class="gh-identity-placeholder"><x-gh-icon name="user"/></span>
            <div>
                <p class="gh-identity-name">Novo personagem</p>
                <p class="gh-identity-meta">Preencha as seções ao lado</p>
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

    <form action="{{ route('characters.store') }}" method="POST" @submit="saving = true" @input="dirty = true" @change="dirty = true">
        @csrf

        <div class="gh-page-header">
            <div>
                <h2 class="gh-page-title"><x-gh-icon name="user"/> Nova ficha</h2>
                <p class="gh-page-description">Preencha os dados do seu personagem. Você pode voltar e ajustar qualquer seção antes de criar a ficha.</p>
            </div>
            <div class="gh-page-actions">
                <span class="gh-unsaved-badge" x-show="dirty && !saving" x-cloak>Alterações não salvas</span>
                <button type="submit" class="gh-btn gh-btn-primary" :disabled="saving">
                    <x-gh-icon name="save"/>
                    <span x-show="!saving">Criar ficha</span>
                    <span x-show="saving" x-cloak>Salvando...</span>
                </button>
            </div>
        </div>

        @include('characters.partials.form', ['character' => null])

        <div class="gh-section-footer">
            <a href="{{ route('characters.index') }}" class="gh-btn gh-btn-secondary">Cancelar</a>
            <button type="submit" class="gh-btn gh-btn-primary" :disabled="saving">
                <x-gh-icon name="save"/>
                <span x-show="!saving">Criar ficha</span>
                <span x-show="saving" x-cloak>Salvando...</span>
            </button>
        </div>
    </form>
</x-character-sheet-layout>
