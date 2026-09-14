<x-app-layout>
    <x-slot name="header">
        <p class="text-xs font-semibold uppercase tracking-[0.22em] text-[#d2b694]">Grimório de Monstros</p>
        <h2 class="tavern-display mt-1 text-3xl font-black tracking-[0.06em] text-[#f5e7c8]">{{ $monster->displayName() }}</h2>
    </x-slot>

    <div class="grimoire-wrap">
        <div class="grimoire-topbar">
            <a href="{{ route('monsters.index') }}" class="grimoire-back">
                <x-gh-icon name="arrow-left" />
                Voltar ao grimório
            </a>
        </div>

        <div class="grimoire-book">
            <div class="grimoire-spread">
                {{-- Página esquerda: retrato e ficha de atributos --}}
                <section class="grimoire-page grimoire-page--left">
                    <div class="grimoire-tape grimoire-tape--left"></div>
                    <div class="grimoire-stain" style="right: -1.5rem; bottom: -1.5rem;"></div>

                    <div class="grimoire-portrait-frame">
                        @if ($monster->image_url)
                            <img src="{{ $monster->image_url }}" alt="{{ $monster->displayName() }}">
                        @else
                            <div class="grimoire-portrait-placeholder">
                                <svg viewBox="0 0 48 48" fill="none" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M12 30c-3-6-2-14 4-18 5-3.5 11-3.5 16 0 6 4 7 12 4 18" />
                                    <path d="M16 26c1.5 3 3 4 8 4s6.5-1 8-4" />
                                    <circle cx="19" cy="22" r="1.4" fill="currentColor" stroke="none" />
                                    <circle cx="29" cy="22" r="1.4" fill="currentColor" stroke="none" />
                                    <path d="M9 15c-2 1-3 3-2 6M39 15c2 1 3 3 2 6" />
                                </svg>
                                <span>sem retrato conhecido</span>
                            </div>
                        @endif
                    </div>

                    <h1 class="grimoire-title">{{ $monster->displayName() }}</h1>
                    @if ($monster->translated)
                        <span class="grimoire-subtitle">— assim registrado nos arquivos além-mar: “{{ $monster->name }}” —</span>
                    @endif

                    <div class="grimoire-stamp-row">
                        <span class="grimoire-stamp">{{ $monster->sizeLabel() }}</span>
                        <span class="grimoire-stamp">{{ $monster->typeLabel() }}{{ $monster->subtype ? ' ('.$monster->subtype.')' : '' }}</span>
                        <span class="grimoire-stamp">{{ $monster->alignmentLabel() }}</span>
                    </div>

                    <div class="grimoire-divider">
                        <svg viewBox="0 0 120 16" fill="none" stroke="currentColor" stroke-width="1.4" stroke-linecap="round">
                            <path d="M2 8c8-8 12 8 20 0s12-8 20 0 12 8 20 0 12-8 20 0 12 8 20 0" />
                        </svg>
                    </div>

                    <dl class="grimoire-stat-table">
                        <div>
                            <dt>Classe de Armadura</dt>
                            <dd>{{ $monster->armor_class }}@if ($monster->armor_class_note) <span style="font-size: 0.85rem; color: #6c5334;">({{ $monster->armor_class_note }})</span>@endif</dd>
                        </div>
                        <div>
                            <dt>Pontos de Vida</dt>
                            <dd>{{ $monster->hit_points }}@if ($monster->hit_dice) <span style="font-size: 0.85rem; color: #6c5334;">({{ $monster->hit_dice }})</span>@endif</dd>
                        </div>
                        @foreach ($monster->speedEntries() as $speed)
                            <div>
                                <dt>{{ $speed['label'] }}</dt>
                                <dd>{{ $speed['value'] }}</dd>
                            </div>
                        @endforeach
                    </dl>

                    <div class="grimoire-ability-grid">
                        @foreach ([
                            ['str', $monster->strength],
                            ['dex', $monster->dexterity],
                            ['con', $monster->constitution],
                            ['int', $monster->intelligence],
                            ['wis', $monster->wisdom],
                            ['cha', $monster->charisma],
                        ] as [$key, $score])
                            <div class="grimoire-ability">
                                <strong>{{ $monster->abilityLabel($key) }}</strong>
                                <span>{{ $score }}</span>
                                <em>{{ $monster->abilityModifier($score) }}</em>
                            </div>
                        @endforeach
                    </div>

                    <div class="grimoire-divider">
                        <svg viewBox="0 0 120 16" fill="none" stroke="currentColor" stroke-width="1.4" stroke-linecap="round">
                            <path d="M2 8c8-8 12 8 20 0s12-8 20 0 12 8 20 0 12-8 20 0 12 8 20 0" />
                        </svg>
                    </div>

                    @if ($monster->sensesEntries() !== [])
                        <p class="grimoire-entry-name">Sentidos</p>
                        <ul class="grimoire-list">
                            @foreach ($monster->sensesEntries() as $sense)
                                <li>{{ $sense['label'] }} {{ $sense['value'] }}</li>
                            @endforeach
                        </ul>
                    @endif

                    @if ($monster->languages)
                        <p class="grimoire-entry-name">Idiomas</p>
                        <ul class="grimoire-list"><li>{{ $monster->languages }}</li></ul>
                    @endif

                    @if ($monster->proficiencyLabels() !== [])
                        <p class="grimoire-entry-name">Perícias &amp; Resistências</p>
                        <ul class="grimoire-list">
                            @foreach ($monster->proficiencyLabels() as $label)
                                <li>{{ $label }}</li>
                            @endforeach
                        </ul>
                    @endif

                    @if ($monster->damage_vulnerabilities_labels !== [])
                        <p class="grimoire-entry-name">Vulnerável a</p>
                        <ul class="grimoire-list"><li>{{ implode(', ', $monster->damage_vulnerabilities_labels) }}</li></ul>
                    @endif

                    @if ($monster->damage_resistances_labels !== [])
                        <p class="grimoire-entry-name">Resiste a</p>
                        <ul class="grimoire-list"><li>{{ implode(', ', $monster->damage_resistances_labels) }}</li></ul>
                    @endif

                    @if ($monster->damage_immunities_labels !== [])
                        <p class="grimoire-entry-name">Imune a dano</p>
                        <ul class="grimoire-list"><li>{{ implode(', ', $monster->damage_immunities_labels) }}</li></ul>
                    @endif

                    @if ($monster->condition_immunities_labels !== [])
                        <p class="grimoire-entry-name">Imune às condições</p>
                        <ul class="grimoire-list"><li>{{ implode(', ', $monster->condition_immunities_labels) }}</li></ul>
                    @endif
                </section>

                <div class="grimoire-spine"></div>

                {{-- Página direita: habilidades, ações e anotações de campo --}}
                <section class="grimoire-page grimoire-page--right">
                    <div class="grimoire-tape grimoire-tape--right"></div>
                    <div class="grimoire-stain" style="left: -1.5rem; top: 40%;"></div>

                    <div class="grimoire-seal">
                        <strong>{{ $monster->challengeRatingLabel() }}</strong>
                        <span>CR · {{ $monster->xp }} XP</span>
                    </div>

                    <p class="grimoire-note-heading">Anotações de campo</p>

                    @if (($monster->special_abilities ?? []) === [] && ($monster->actions ?? []) === [])
                        <p class="grimoire-entry-desc">Nenhum comportamento incomum foi registrado por quem estudou esta criatura até agora.</p>
                    @endif

                    @if (! empty($monster->special_abilities))
                        @foreach ($monster->special_abilities as $ability)
                            <div class="grimoire-entry">
                                <p class="grimoire-entry-name">{{ $ability['name_pt'] ?: $ability['name'] }}</p>
                                <p class="grimoire-entry-desc">{{ $ability['desc_pt'] ?: $ability['desc'] }}</p>
                            </div>
                        @endforeach
                    @endif

                    @if (! empty($monster->actions))
                        <div class="grimoire-divider">
                            <svg viewBox="0 0 120 16" fill="none" stroke="currentColor" stroke-width="1.4" stroke-linecap="round">
                                <path d="M2 8c8-8 12 8 20 0s12-8 20 0 12 8 20 0 12-8 20 0 12 8 20 0" />
                            </svg>
                        </div>

                        <p class="grimoire-note-heading" style="font-size: 1.3rem;">Ações</p>

                        @foreach ($monster->actions as $action)
                            <div class="grimoire-entry">
                                <p class="grimoire-entry-name">{{ $action['name_pt'] ?: $action['name'] }}</p>
                                <p class="grimoire-entry-desc">{{ $action['desc_pt'] ?: $action['desc'] }}</p>
                            </div>
                        @endforeach
                    @endif

                    @if (! empty($monster->legendary_actions))
                        <div class="grimoire-divider">
                            <svg viewBox="0 0 120 16" fill="none" stroke="currentColor" stroke-width="1.4" stroke-linecap="round">
                                <path d="M2 8c8-8 12 8 20 0s12-8 20 0 12 8 20 0 12-8 20 0 12 8 20 0" />
                            </svg>
                        </div>

                        <p class="grimoire-note-heading" style="font-size: 1.3rem;">Ações lendárias</p>

                        @foreach ($monster->legendary_actions as $action)
                            <div class="grimoire-entry">
                                <p class="grimoire-entry-name">{{ $action['name_pt'] ?: $action['name'] }}</p>
                                <p class="grimoire-entry-desc">{{ $action['desc_pt'] ?: $action['desc'] }}</p>
                            </div>
                        @endforeach
                    @endif

                    @if (! empty($monster->reactions))
                        <div class="grimoire-divider">
                            <svg viewBox="0 0 120 16" fill="none" stroke="currentColor" stroke-width="1.4" stroke-linecap="round">
                                <path d="M2 8c8-8 12 8 20 0s12-8 20 0 12 8 20 0 12-8 20 0 12 8 20 0" />
                            </svg>
                        </div>

                        <p class="grimoire-note-heading" style="font-size: 1.3rem;">Reações</p>

                        @foreach ($monster->reactions as $reaction)
                            <div class="grimoire-entry">
                                <p class="grimoire-entry-name">{{ $reaction['name_pt'] ?: $reaction['name'] }}</p>
                                <p class="grimoire-entry-desc">{{ $reaction['desc_pt'] ?: $reaction['desc'] }}</p>
                            </div>
                        @endforeach
                    @endif

                    @if ($monster->synced_at)
                        <p class="grimoire-footer-note">copiado do arquivo em {{ $monster->synced_at->format('d/m/Y') }}</p>
                    @endif
                </section>
            </div>
        </div>
    </div>
</x-app-layout>
