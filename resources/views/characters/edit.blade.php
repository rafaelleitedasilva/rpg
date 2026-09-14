<x-character-sheet-layout x-data="{ tab: 'identity', dirty: false, saving: false, secondaryClass: {{ \Illuminate\Support\Js::from(old('secondary_class', $character->secondary_class ?? '')) }} }" title="Editar {{ $character->name }}">
    <x-slot name="sidebar">
        <a href="{{ route('characters.index') }}" class="gh-back-link"><x-gh-icon name="arrow-left"/> Voltar para as fichas</a>

        <div class="gh-identity-card">
            @if ($character->displayPortraitUrl())
                <img src="{{ $character->displayPortraitUrl() }}" alt="" class="gh-identity-portrait">
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

    {{-- Photo uploads live outside the main form: file uploads need their own
         multipart request, and HTML does not allow nesting <form> elements.
         Still gated by the shared Alpine `tab` state, so it appears in place
         inside the "História" section. --}}
    <section x-show="tab === 'story'" x-cloak class="gh-card" style="margin-top: 1.25rem">
        <h3 class="gh-card-title"><x-gh-icon name="user"/> Fotos do personagem</h3>
        <p class="gh-card-description">Envie uma ou mais imagens. A imagem marcada como capa aparece na listagem e no topo da ficha.</p>

        @error('images')
            <p class="gh-field-error mt-2">{{ $message }}</p>
        @enderror

        @if ($character->images->isNotEmpty())
            <div class="mt-4 grid gap-4" style="grid-template-columns: repeat(auto-fill, minmax(140px, 1fr));">
                @foreach ($character->images as $image)
                    <div class="gh-photo-card">
                        <img src="{{ $image->url() }}" alt="Foto de {{ $character->name }}" class="gh-photo-thumb">
                        @if ($image->is_cover)
                            <span class="gh-badge gh-badge-accent gh-photo-cover-badge">Capa</span>
                        @endif
                        <div class="gh-photo-actions">
                            @unless ($image->is_cover)
                                <form method="POST" action="{{ route('characters.images.cover', [$character, $image]) }}">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="gh-btn gh-btn-secondary">Tornar capa</button>
                                </form>
                            @endunless
                            <form method="POST" action="{{ route('characters.images.destroy', [$character, $image]) }}" onsubmit="return confirm('Remover esta imagem?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="gh-btn gh-btn-ghost gh-photo-danger">Remover</button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif

        @if ($character->images->count() < 12)
            <form method="POST" action="{{ route('characters.images.store', $character) }}" enctype="multipart/form-data" class="mt-5">
                @csrf
                <div class="gh-field">
                    <label class="gh-label" for="images">Adicionar imagens</label>
                    <input id="images" type="file" name="images[]" multiple accept="image/png,image/jpeg,image/webp,image/gif" class="gh-input">
                    <p class="gh-hint">JPG, PNG, WEBP ou GIF, até 5MB cada. Máximo de 12 imagens por personagem.</p>
                </div>
                <button type="submit" class="gh-btn gh-btn-primary mt-3">Enviar imagens</button>
            </form>
        @endif
    </section>
</x-character-sheet-layout>
