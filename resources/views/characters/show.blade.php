@php
    use App\Modules\Rpg\Services\Dnd5eCharacterService;

    $coinLabels = ['coins_cp' => 'PC', 'coins_sp' => 'PP', 'coins_ep' => 'PE', 'coins_gp' => 'PO', 'coins_pp' => 'PL'];
    $appearance = collect(['age' => 'Idade', 'height' => 'Altura', 'weight' => 'Peso', 'eyes' => 'Olhos', 'skin' => 'Pele', 'hair' => 'Cabelo'])
        ->filter(fn ($label, $key) => filled($character->$key));
    $sections = include resource_path('views/characters/partials/sections.php');
@endphp

<x-character-sheet-layout title="{{ $character->name }}">
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

        <a href="{{ route('characters.edit', $character) }}" class="gh-btn gh-btn-primary" style="justify-content:center">Editar ficha</a>
        <a href="{{ route('characters.pdf', $character) }}" class="gh-btn gh-btn-secondary" style="justify-content:center"><x-gh-icon name="download"/> Exportar PDF</a>

        <nav class="gh-section-nav" aria-label="Seções da ficha">
            @foreach ($sections as $key => $section)
                <a href="#section-{{ $key }}" class="gh-section-nav-item">
                    <x-gh-icon name="{{ $section['icon'] }}"/>
                    {{ $section['label'] }}
                </a>
            @endforeach
        </nav>
    </x-slot>

    <div class="gh-page-header">
        <div>
            <h2 class="gh-page-title"><x-gh-icon name="user"/> {{ $character->name }}</h2>
            <p class="gh-page-description">{{ $character->race }} · {{ $character->class }}@if ($character->secondary_class) / {{ $character->secondary_class }}@endif · Nível {{ $character->level }}</p>
        </div>
        <div class="gh-page-actions">
            <a href="{{ route('characters.pdf', $character) }}" class="gh-btn gh-btn-secondary"><x-gh-icon name="download"/> Exportar PDF</a>
            <a href="{{ route('characters.edit', $character) }}" class="gh-btn gh-btn-primary">Editar ficha</a>
        </div>
    </div>

    <div id="section-identity" class="gh-card">
        <h3 class="gh-card-title"><x-gh-icon name="user"/> Identificação</h3>
        <div class="mt-4 grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
            @foreach ([
                'player_name' => 'Jogador', 'master_name' => 'Mestre', 'campaign_name' => 'Campanha', 'deity' => 'Divindade',
                'background' => 'Antecedente', 'alignment' => 'Tendência', 'size' => 'Tamanho',
            ] as $field => $label)
                @if ($character->$field)
                    <div class="gh-stat">
                        <p class="gh-stat-label">{{ $label }}</p>
                        <p class="mt-2 text-sm" style="color: var(--gh-text)">{{ $character->$field }}</p>
                    </div>
                @endif
            @endforeach
            <div class="gh-stat">
                <p class="gh-stat-label">Experiência</p>
                <p class="mt-2 text-sm" style="color: var(--gh-text)">{{ number_format($character->experience, 0, ',', '.') }} XP</p>
            </div>
        </div>
    </div>

    <div id="section-abilities" class="gh-card">
        <h3 class="gh-card-title"><x-gh-icon name="shield"/> Atributos & resistências</h3>
        <div class="mt-4 grid gap-4 sm:grid-cols-3 xl:grid-cols-6">
            @foreach (Dnd5eCharacterService::ABILITIES as $abilityKey => $abilityLabel)
                @php $mod = $character->abilityModifier($abilityKey); $save = $character->savingThrowBonus($abilityKey); @endphp
                <div class="gh-stat">
                    <p class="gh-stat-label">{{ $abilityLabel }}</p>
                    <p class="gh-stat-value">{{ $character->$abilityKey }}</p>
                    <p class="gh-hint">Mod {{ $mod >= 0 ? '+' : '' }}{{ $mod }}</p>
                    <p class="gh-hint" style="color: {{ $character->isProficientInSavingThrow($abilityKey) ? 'var(--gh-success)' : 'var(--gh-muted-2)' }}">
                        {{ $character->isProficientInSavingThrow($abilityKey) ? '●' : '○' }} Resistência {{ $save >= 0 ? '+' : '' }}{{ $save }}
                    </p>
                </div>
            @endforeach
        </div>

        <div class="mt-4 grid gap-4 sm:grid-cols-3">
            <div class="gh-stat">
                <p class="gh-stat-label">Bônus de proficiência</p>
                <p class="gh-stat-value">+{{ $character->proficiency_bonus }}</p>
            </div>
            <div class="gh-stat">
                <p class="gh-stat-label">Percepção passiva</p>
                <p class="gh-stat-value">{{ $character->passive_perception }}</p>
            </div>
            <div class="gh-stat">
                <p class="gh-stat-label">Inspiração</p>
                <p class="gh-stat-value">{{ $character->inspiration ? 'Sim' : 'Não' }}</p>
            </div>
        </div>
    </div>

    <div id="section-skills" class="gh-card">
        <h3 class="gh-card-title"><x-gh-icon name="sparkles"/> Perícias</h3>
        <div class="mt-4 grid gap-4 sm:grid-cols-2 xl:grid-cols-3">
            @foreach (Dnd5eCharacterService::ABILITIES as $abilityKey => $abilityLabel)
                @php $skills = collect($character->skillsBreakdown())->filter(fn ($s) => $s['ability'] === $abilityKey); @endphp
                @if ($skills->isNotEmpty())
                    <div class="gh-stat">
                        <p class="gh-stat-label">{{ $abilityLabel }}</p>
                        <ul class="mt-3 space-y-2 text-sm">
                            @foreach ($skills as $skill)
                                <li class="flex items-center justify-between" style="color: {{ $skill['proficient'] ? 'var(--gh-text)' : 'var(--gh-muted)' }}">
                                    <span>{{ $skill['proficient'] ? '●' : '○' }} {{ $skill['label'] }}@if ($skill['expertise']) <span class="gh-badge gh-badge-accent" style="margin-left:.35rem;padding:0.1rem .4rem;font-size:.62rem">expertise</span>@endif</span>
                                    <span class="font-semibold">{{ $skill['bonus'] >= 0 ? '+' : '' }}{{ $skill['bonus'] }}</span>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endif
            @endforeach
        </div>
    </div>

    <div id="section-combat" class="gh-card">
        <h3 class="gh-card-title"><x-gh-icon name="sword"/> Combate</h3>
        <div class="mt-4 grid gap-4 sm:grid-cols-3 xl:grid-cols-6">
            <div class="gh-stat"><p class="gh-stat-label">CA</p><p class="gh-stat-value">{{ $character->armor_class }}</p></div>
            <div class="gh-stat"><p class="gh-stat-label">Iniciativa</p><p class="gh-stat-value">{{ $character->initiative >= 0 ? '+' : '' }}{{ $character->initiative }}</p></div>
            <div class="gh-stat"><p class="gh-stat-label">PV</p><p class="gh-stat-value">{{ $character->current_hp }}/{{ $character->max_hp }}</p></div>
            <div class="gh-stat"><p class="gh-stat-label">PV temp.</p><p class="gh-stat-value">{{ $character->temp_hp }}</p></div>
            <div class="gh-stat"><p class="gh-stat-label">Deslocamento</p><p class="gh-stat-value">{{ $character->speed }}pés</p></div>
            <div class="gh-stat"><p class="gh-stat-label">Dado de vida</p><p class="gh-stat-value">1d{{ $character->hit_dice }}</p></div>
        </div>

        @if ($character->current_hp <= 0)
            <div class="mt-4 gh-stat">
                <p class="gh-stat-label">Testes de morte</p>
                <div class="mt-2 flex items-center justify-between text-sm" style="color: var(--gh-text)">
                    <span>Sucessos: {{ str_repeat('●', $character->death_save_successes).str_repeat('○', 3 - $character->death_save_successes) }}</span>
                    <span>Falhas: {{ str_repeat('●', $character->death_save_failures).str_repeat('○', 3 - $character->death_save_failures) }}</span>
                </div>
            </div>
        @endif

        @if ($character->attacks)
            <div class="mt-4 overflow-x-auto">
                <table class="gh-table">
                    <thead><tr><th>Nome</th><th>Bônus de ataque</th><th>Dano / Tipo</th></tr></thead>
                    <tbody>
                        @foreach ($character->attacks as $attack)
                            <tr><td>{{ $attack['name'] ?? '' }}</td><td>{{ $attack['bonus'] ?? '' }}</td><td>{{ $attack['damage'] ?? '' }}</td></tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>

    <div id="section-gear" class="gh-card">
        <h3 class="gh-card-title"><x-gh-icon name="bag"/> Proficiências, idiomas & equipamento</h3>
        <div class="mt-4 grid gap-4 lg:grid-cols-2">
            @foreach ([
                'armor_proficiencies' => 'Armaduras', 'weapon_proficiencies' => 'Armas', 'tool_proficiencies' => 'Ferramentas',
                'languages' => 'Idiomas', 'proficiencies' => 'Outras proficiências', 'equipment' => 'Equipamentos',
            ] as $field => $label)
                @php $items = $character->$field ?? []; @endphp
                @if ($items)
                    <div class="gh-stat">
                        <p class="gh-stat-label">{{ $label }}</p>
                        <ul class="mt-2 space-y-1 text-sm" style="color: var(--gh-text)">
                            @foreach ($items as $item)<li>• {{ $item }}</li>@endforeach
                        </ul>
                    </div>
                @endif
            @endforeach
        </div>

        @if (collect($coinLabels)->keys()->contains(fn ($key) => $character->$key > 0) || $character->treasure)
            <div class="mt-4 gh-stat">
                <p class="gh-stat-label">Moedas & tesouro</p>
                <div class="mt-2 flex flex-wrap gap-2">
                    @foreach ($coinLabels as $field => $label)
                        @if ($character->$field > 0)
                            <span class="gh-badge">{{ $character->$field }} {{ $label }}</span>
                        @endif
                    @endforeach
                </div>
                @if ($character->treasure)
                    <p class="mt-2 text-sm" style="color: var(--gh-text)">{{ $character->treasure }}</p>
                @endif
            </div>
        @endif
    </div>

    @if ($character->hasSpellcasting() || $character->cantrips || $character->spells)
        <div id="section-spellcasting" class="gh-card">
            <h3 class="gh-card-title"><x-gh-icon name="book"/> Conjuração</h3>

            <div class="mt-4 grid gap-4 sm:grid-cols-3">
                @if ($character->spellcasting_ability)
                    <div class="gh-stat">
                        <p class="gh-stat-label">Atributo</p>
                        <p class="mt-2 text-sm" style="color: var(--gh-text)">{{ Dnd5eCharacterService::SPELLCASTING_ABILITIES[$character->spellcasting_ability] ?? $character->spellcasting_ability }}</p>
                    </div>
                @endif
                @if ($character->hasSpellcasting())
                    <div class="gh-stat"><p class="gh-stat-label">CD de magia</p><p class="gh-stat-value">{{ $character->spell_save_dc }}</p></div>
                    <div class="gh-stat"><p class="gh-stat-label">Ataque com magia</p><p class="gh-stat-value">{{ $character->spell_attack_bonus >= 0 ? '+' : '' }}{{ $character->spell_attack_bonus }}</p></div>
                @endif
            </div>

            @if ($character->hasSpellcasting())
                <div class="mt-4 grid gap-3 sm:grid-cols-3 xl:grid-cols-5">
                    @foreach ($character->spellSlots() as $slotLevel => $slotAmount)
                        <div class="gh-stat"><p class="gh-stat-label">Nível {{ $slotLevel }}</p><p class="gh-stat-value">{{ $slotAmount }}</p></div>
                    @endforeach
                </div>
            @endif

            @if ($character->cantrips)
                <div class="mt-4">
                    <p class="gh-stat-label">Truques</p>
                    <div class="mt-2 flex flex-wrap gap-2">
                        @foreach ($character->cantrips as $cantrip)<span class="gh-badge">{{ $cantrip }}</span>@endforeach
                    </div>
                </div>
            @endif

            @if ($character->spells)
                <div class="mt-4">
                    <p class="gh-stat-label">Magias conhecidas</p>
                    <div class="mt-2 flex flex-wrap gap-2">
                        @foreach ($character->spells as $spell)<span class="gh-badge gh-badge-accent">{{ $spell }}</span>@endforeach
                    </div>
                </div>
            @endif
        </div>
    @endif

    <div id="section-story" class="gh-card">
        <h3 class="gh-card-title"><x-gh-icon name="scroll"/> Personalidade & história</h3>

        <div class="mt-4 grid gap-4 lg:grid-cols-2">
            @foreach (['personality_traits' => 'Traços', 'ideals' => 'Ideais', 'bonds' => 'Vínculos', 'flaws' => 'Defeitos', 'features' => 'Características e talentos'] as $field => $label)
                @php $items = $character->$field ?? []; @endphp
                @if ($items)
                    <div class="gh-stat">
                        <p class="gh-stat-label">{{ $label }}</p>
                        <ul class="mt-2 space-y-1 text-sm" style="color: var(--gh-text)">
                            @foreach ($items as $item)<li>• {{ $item }}</li>@endforeach
                        </ul>
                    </div>
                @endif
            @endforeach
        </div>

        @if ($appearance->isNotEmpty())
            <div class="mt-4 grid gap-3 sm:grid-cols-3">
                @foreach ($appearance as $key => $label)
                    <div class="gh-stat"><p class="gh-stat-label">{{ $label }}</p><p class="mt-1 text-sm" style="color: var(--gh-text)">{{ $character->$key }}</p></div>
                @endforeach
            </div>
        @endif

        @if ($character->images->isNotEmpty())
            <div class="mt-4">
                <p class="gh-stat-label">Fotos</p>
                <div class="mt-2 grid gap-3" style="grid-template-columns: repeat(auto-fill, minmax(110px, 1fr));">
                    @foreach ($character->images as $image)
                        <div class="gh-photo-card">
                            <img src="{{ $image->url() }}" alt="Foto de {{ $character->name }}" class="gh-photo-thumb">
                            @if ($image->is_cover)
                                <span class="gh-badge gh-badge-accent gh-photo-cover-badge">Capa</span>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        @if ($character->allies_organizations)
            <div class="mt-4 gh-stat"><p class="gh-stat-label">Aliados e organizações</p><p class="mt-2 text-sm leading-relaxed" style="color: var(--gh-text)">{{ $character->allies_organizations }}</p></div>
        @endif

        @if ($character->backstory)
            <div class="mt-4 gh-stat"><p class="gh-stat-label">História</p><p class="mt-2 text-sm leading-relaxed" style="color: var(--gh-text)">{{ $character->backstory }}</p></div>
        @endif

        @if ($character->notes)
            <div class="mt-4 gh-stat"><p class="gh-stat-label">Notas</p><p class="mt-2 text-sm leading-relaxed" style="color: var(--gh-text)">{{ $character->notes }}</p></div>
        @endif
    </div>
</x-character-sheet-layout>
