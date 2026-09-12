@php
    use App\Modules\Rpg\Services\Dnd5eCharacterService;

    $coinLabels = ['coins_cp' => 'PC', 'coins_sp' => 'PP', 'coins_ep' => 'PE', 'coins_gp' => 'PO', 'coins_pp' => 'PL'];
    $appearance = collect(['age' => 'Idade', 'height' => 'Altura', 'weight' => 'Peso', 'eyes' => 'Olhos', 'skin' => 'Pele', 'hair' => 'Cabelo'])
        ->filter(fn ($label, $key) => filled($character->$key));
@endphp

<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between gap-4">
            <div>
                <p class="text-[0.66rem] uppercase tracking-[0.28em] text-[#d8b97d]">Ficha de aventureiro</p>
                <h2 class="tavern-display mt-2 text-4xl text-[#f7efe3]">{{ $character->name }}</h2>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('characters.edit', $character) }}" class="golden-button text-sm">Editar ficha</a>
                <a href="{{ route('characters.index') }}" class="secondary-button text-sm">Voltar</a>
            </div>
        </div>
    </x-slot>

    <div class="character-sheet-shell sheet-fade-in mx-auto max-w-7xl space-y-6 px-4 py-8 sm:px-6 lg:px-8">
        <div class="grid gap-6 lg:grid-cols-[0.9fr_1.1fr]">
            <aside class="rpg-card p-6 sheet-hero">
                @if ($character->portrait_url)
                    <img src="{{ $character->portrait_url }}" alt="Retrato de {{ $character->name }}" class="mb-5 aspect-square w-full rounded-2xl border border-[#a67a47]/40 object-cover">
                @endif

                <div class="mb-5 flex items-center justify-between">
                    <span class="rpg-badge">{{ $character->class }}@if ($character->secondary_class) / {{ $character->secondary_class }}@endif</span>
                    <span class="rpg-badge subtle">Nível {{ $character->level }}</span>
                </div>

                <h3 class="mt-2 tavern-display text-4xl text-[#f7efe3]">{{ $character->race }}</h3>
                <p class="prose-tavern mt-3 text-[#dcccb0]">{{ $character->background }} · {{ $character->alignment }} · {{ $character->size }}</p>

                <dl class="mt-4 grid grid-cols-2 gap-2 text-sm text-[#c9b79c]">
                    @if ($character->player_name)
                        <div><dt class="text-[#d8b97d]">Jogador</dt><dd>{{ $character->player_name }}</dd></div>
                    @endif
                    @if ($character->master_name)
                        <div><dt class="text-[#d8b97d]">Mestre</dt><dd>{{ $character->master_name }}</dd></div>
                    @endif
                    @if ($character->campaign_name)
                        <div><dt class="text-[#d8b97d]">Campanha</dt><dd>{{ $character->campaign_name }}</dd></div>
                    @endif
                    @if ($character->deity)
                        <div><dt class="text-[#d8b97d]">Divindade</dt><dd>{{ $character->deity }}</dd></div>
                    @endif
                    <div><dt class="text-[#d8b97d]">Experiência</dt><dd>{{ number_format($character->experience, 0, ',', '.') }} XP</dd></div>
                </dl>

                <div class="mt-6 grid grid-cols-2 gap-3">
                    <div class="stat-chip">
                        <p class="text-[0.62rem] uppercase tracking-[0.22em] text-[#d8b97d]">CA</p>
                        <p class="mt-3 text-3xl font-black text-[#fffaf1]">{{ $character->armor_class }}</p>
                    </div>
                    <div class="stat-chip">
                        <p class="text-[0.62rem] uppercase tracking-[0.22em] text-[#d8b97d]">Iniciativa</p>
                        <p class="mt-3 text-3xl font-black text-[#fffaf1]">{{ $character->initiative >= 0 ? '+' : '' }}{{ $character->initiative }}</p>
                    </div>
                    <div class="stat-chip">
                        <p class="text-[0.62rem] uppercase tracking-[0.22em] text-[#d8b97d]">PV</p>
                        <p class="mt-3 text-3xl font-black text-[#fffaf1]">{{ $character->current_hp }}/{{ $character->max_hp }}@if ($character->temp_hp) <span class="text-lg text-[#8fd18c]">+{{ $character->temp_hp }}</span>@endif</p>
                    </div>
                    <div class="stat-chip">
                        <p class="text-[0.62rem] uppercase tracking-[0.22em] text-[#d8b97d]">Percepção passiva</p>
                        <p class="mt-3 text-3xl font-black text-[#fffaf1]">{{ $character->passive_perception }}</p>
                    </div>
                </div>

                <div class="mt-4 flex items-center justify-between text-sm text-[#c9b79c]">
                    <span>Deslocamento: {{ $character->speed }} pés</span>
                    <span>Dado de vida: 1d{{ $character->hit_dice }}</span>
                </div>

                @if ($character->inspiration)
                    <div class="mt-4 rpg-badge subtle">✦ Inspiração</div>
                @endif

                @if ($character->current_hp <= 0)
                    <div class="mt-6 rounded-2xl border border-[#a67a47]/40 bg-[#1b1512]/70 p-4">
                        <p class="text-[0.62rem] uppercase tracking-[0.22em] text-[#d8b97d]">Testes de morte</p>
                        <div class="mt-3 flex items-center justify-between text-sm">
                            <span>Sucessos: {{ str_repeat('●', $character->death_save_successes).str_repeat('○', 3 - $character->death_save_successes) }}</span>
                            <span>Falhas: {{ str_repeat('●', $character->death_save_failures).str_repeat('○', 3 - $character->death_save_failures) }}</span>
                        </div>
                    </div>
                @endif
            </aside>

            <section class="rpg-card p-6 sheet-panel">
                <div class="mb-5 flex items-center justify-between">
                    <h3 class="tavern-display text-3xl text-[#f7efe3]">Atributos</h3>
                    <span class="rpg-ornament"></span>
                </div>

                <div class="mt-6 grid gap-4 sm:grid-cols-3">
                    @foreach (Dnd5eCharacterService::ABILITIES as $key => $label)
                        <div class="character-attribute">
                            <p class="text-[0.62rem] uppercase tracking-[0.22em] text-[#d8b97d]">{{ $label }}</p>
                            <p class="mt-3 text-3xl font-black text-[#fffaf1]">{{ $character->$key }}</p>
                            @php $mod = $character->abilityModifier($key); @endphp
                            <p class="mt-1 text-sm text-[#f2d7a1]">Mod: {{ $mod >= 0 ? '+' : '' }}{{ $mod }}</p>
                            @php $save = $character->savingThrowBonus($key); @endphp
                            <p class="mt-1 text-xs {{ $character->isProficientInSavingThrow($key) ? 'text-[#8fd18c]' : 'text-[#8a7862]' }}">
                                {{ $character->isProficientInSavingThrow($key) ? '● ' : '○ ' }}
                                Resistência: {{ $save >= 0 ? '+' : '' }}{{ $save }}
                            </p>
                        </div>
                    @endforeach
                </div>

                <div class="mt-8 grid gap-4 md:grid-cols-3">
                    <div class="character-metric">
                        <p class="text-[0.62rem] uppercase tracking-[0.22em] text-[#d8b97d]">Bônus de proficiência</p>
                        <p class="mt-3 text-3xl font-black text-[#fffaf1]">+{{ $character->proficiency_bonus }}</p>
                    </div>
                    @if ($character->hasSpellcasting())
                        <div class="character-metric">
                            <p class="text-[0.62rem] uppercase tracking-[0.22em] text-[#d8b97d]">CD de magia</p>
                            <p class="mt-3 text-3xl font-black text-[#fffaf1]">{{ $character->spell_save_dc }}</p>
                        </div>
                        <div class="character-metric">
                            <p class="text-[0.62rem] uppercase tracking-[0.22em] text-[#d8b97d]">Ataque com magia</p>
                            <p class="mt-3 text-3xl font-black text-[#fffaf1]">{{ $character->spell_attack_bonus >= 0 ? '+' : '' }}{{ $character->spell_attack_bonus }}</p>
                        </div>
                    @endif
                </div>
            </section>
        </div>

        <section class="rpg-card p-6 sheet-panel">
            <div class="mb-5 flex items-center justify-between">
                <h3 class="tavern-display text-3xl text-[#f7efe3]">Perícias</h3>
                <span class="rpg-ornament"></span>
            </div>

            <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-3">
                @foreach (Dnd5eCharacterService::ABILITIES as $abilityKey => $abilityLabel)
                    @php $skills = collect($character->skillsBreakdown())->filter(fn ($s) => $s['ability'] === $abilityKey); @endphp
                    @if ($skills->isNotEmpty())
                        <div class="rounded-2xl border border-[#a67a47]/40 bg-[#1b1512]/70 p-4">
                            <p class="text-[0.62rem] uppercase tracking-[0.22em] text-[#d8b97d]">{{ $abilityLabel }}</p>
                            <ul class="mt-3 space-y-2 text-sm">
                                @foreach ($skills as $skill)
                                    <li class="flex items-center justify-between {{ $skill['proficient'] ? 'text-[#f2d7a1]' : 'text-[#c9b79c]' }}">
                                        <span>{{ $skill['proficient'] ? '●' : '○' }} {{ $skill['label'] }}@if ($skill['expertise']) <span class="text-[0.6rem] uppercase text-[#8fd18c]">(expertise)</span>@endif</span>
                                        <span class="font-semibold">{{ $skill['bonus'] >= 0 ? '+' : '' }}{{ $skill['bonus'] }}</span>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                @endforeach
            </div>
        </section>

        @if ($character->attacks)
            <section class="rpg-card p-6 sheet-panel">
                <div class="mb-5 flex items-center justify-between">
                    <h3 class="tavern-display text-3xl text-[#f7efe3]">Ataques</h3>
                    <span class="rpg-ornament"></span>
                </div>
                <div class="overflow-x-auto">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Nome</th>
                                <th>Bônus de ataque</th>
                                <th>Dano / Tipo</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($character->attacks as $attack)
                                <tr>
                                    <td>{{ $attack['name'] ?? '' }}</td>
                                    <td>{{ $attack['bonus'] ?? '' }}</td>
                                    <td>{{ $attack['damage'] ?? '' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </section>
        @endif

        <section class="rpg-card p-6 sheet-panel">
            <div class="mb-5 flex items-center justify-between">
                <h3 class="tavern-display text-3xl text-[#f7efe3]">Proficiências, idiomas & equipamento</h3>
                <span class="rpg-ornament"></span>
            </div>

            <div class="grid gap-4 lg:grid-cols-2">
                @foreach ([
                    'armor_proficiencies' => 'Armaduras',
                    'weapon_proficiencies' => 'Armas',
                    'tool_proficiencies' => 'Ferramentas',
                    'languages' => 'Idiomas',
                    'proficiencies' => 'Outras proficiências',
                    'equipment' => 'Equipamentos',
                ] as $field => $label)
                    @php $items = $character->$field ?? []; @endphp
                    @if ($items)
                        <div class="rounded-2xl border border-[#a67a47]/40 bg-[#1b1512]/70 p-4">
                            <p class="text-[0.62rem] uppercase tracking-[0.22em] text-[#d8b97d]">{{ $label }}</p>
                            <ul class="prose-tavern mt-3 space-y-1 text-sm text-[#e6d7b8]">
                                @foreach ($items as $item)
                                    <li>• {{ $item }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                @endforeach
            </div>

            @if (collect($coinLabels)->keys()->contains(fn ($key) => $character->$key > 0) || $character->treasure)
                <div class="mt-6 rounded-2xl border border-[#a67a47]/40 bg-[#1b1512]/70 p-4">
                    <p class="text-[0.62rem] uppercase tracking-[0.22em] text-[#d8b97d]">Moedas & tesouro</p>
                    <div class="mt-3 flex flex-wrap gap-3">
                        @foreach ($coinLabels as $field => $label)
                            @if ($character->$field > 0)
                                <span class="rpg-badge subtle">{{ $character->$field }} {{ $label }}</span>
                            @endif
                        @endforeach
                    </div>
                    @if ($character->treasure)
                        <p class="prose-tavern mt-3 text-sm text-[#e6d7b8]">{{ $character->treasure }}</p>
                    @endif
                </div>
            @endif
        </section>

        @if ($character->hasSpellcasting() || $character->cantrips || $character->spells)
            <section class="rpg-card p-6 sheet-panel">
                <div class="mb-5 flex items-center justify-between">
                    <h3 class="tavern-display text-3xl text-[#f7efe3]">Conjuração</h3>
                    <span class="rpg-ornament"></span>
                </div>

                @if ($character->spellcasting_ability)
                    <p class="text-sm text-[#c9b79c]">Atributo de conjuração: <span class="font-semibold text-[#f2d7a1]">{{ Dnd5eCharacterService::SPELLCASTING_ABILITIES[$character->spellcasting_ability] ?? $character->spellcasting_ability }}</span></p>
                @endif

                @if ($character->hasSpellcasting())
                    <div class="mt-4 grid gap-3 sm:grid-cols-2">
                        @foreach ($character->spellSlots() as $slotLevel => $slotAmount)
                            <div class="rounded-xl border border-[#a67a47]/30 bg-[#17110d]/80 p-3">
                                <p class="text-xs uppercase tracking-[0.2em] text-[#d8b97d]">Nível {{ $slotLevel }}</p>
                                <p class="mt-2 text-2xl font-black text-[#fffaf1]">{{ $slotAmount }}</p>
                            </div>
                        @endforeach
                    </div>
                @endif

                @if ($character->cantrips)
                    <div class="mt-6">
                        <p class="text-[0.62rem] uppercase tracking-[0.22em] text-[#d8b97d]">Truques</p>
                        <div class="mt-3 flex flex-wrap gap-2">
                            @foreach ($character->cantrips as $cantrip)
                                <span class="rpg-badge subtle">{{ $cantrip }}</span>
                            @endforeach
                        </div>
                    </div>
                @endif

                @if ($character->spells)
                    <div class="mt-6">
                        <p class="text-[0.62rem] uppercase tracking-[0.22em] text-[#d8b97d]">Magias conhecidas</p>
                        <div class="mt-3 flex flex-wrap gap-2">
                            @foreach ($character->spells as $spell)
                                <span class="rpg-badge subtle">{{ $spell }}</span>
                            @endforeach
                        </div>
                    </div>
                @endif
            </section>
        @endif

        <section class="rpg-card p-6 sheet-panel">
            <div class="mb-5 flex items-center justify-between">
                <h3 class="tavern-display text-3xl text-[#f7efe3]">Personalidade & características</h3>
                <span class="rpg-ornament"></span>
            </div>

            <div class="grid gap-4 lg:grid-cols-2">
                @foreach (['personality_traits' => 'Traços', 'ideals' => 'Ideais', 'bonds' => 'Vínculos', 'flaws' => 'Defeitos', 'features' => 'Características e talentos'] as $field => $label)
                    @php $items = $character->$field ?? []; @endphp
                    @if ($items)
                        <div class="rounded-2xl border border-[#a67a47]/40 bg-[#1b1512]/70 p-4">
                            <p class="text-[0.62rem] uppercase tracking-[0.22em] text-[#d8b97d]">{{ $label }}</p>
                            <ul class="prose-tavern mt-3 space-y-2 text-sm text-[#e6d7b8]">
                                @foreach ($items as $item)
                                    <li>• {{ $item }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                @endforeach
            </div>
        </section>

        @if ($character->allies_organizations || $character->backstory || $appearance->isNotEmpty() || $character->notes)
            <section class="rpg-card p-6 sheet-panel">
                <div class="mb-5 flex items-center justify-between">
                    <h3 class="tavern-display text-3xl text-[#f7efe3]">Aparência & história</h3>
                    <span class="rpg-ornament"></span>
                </div>

                @if ($appearance->isNotEmpty())
                    <div class="grid gap-3 sm:grid-cols-3">
                        @foreach ($appearance as $key => $label)
                            <div class="stat-chip">
                                <p class="text-[0.62rem] uppercase tracking-[0.22em] text-[#d8b97d]">{{ $label }}</p>
                                <p class="mt-2 text-sm text-[#f7efe3]">{{ $character->$key }}</p>
                            </div>
                        @endforeach
                    </div>
                @endif

                @if ($character->allies_organizations)
                    <div class="mt-6 rounded-2xl border border-[#a67a47]/40 bg-[#1b1512]/70 p-4">
                        <p class="text-[0.62rem] uppercase tracking-[0.22em] text-[#d8b97d]">Aliados e organizações</p>
                        <p class="prose-tavern mt-2 text-sm leading-relaxed text-[#e6d7b8]">{{ $character->allies_organizations }}</p>
                    </div>
                @endif

                @if ($character->backstory)
                    <div class="mt-6 rounded-2xl border border-[#a67a47]/40 bg-[#1b1512]/70 p-4">
                        <p class="text-[0.62rem] uppercase tracking-[0.22em] text-[#d8b97d]">História</p>
                        <p class="prose-tavern mt-2 text-sm leading-relaxed text-[#e6d7b8]">{{ $character->backstory }}</p>
                    </div>
                @endif

                @if ($character->notes)
                    <div class="mt-6 rounded-2xl border border-[#a67a47]/40 bg-[#1b1512]/70 p-4">
                        <p class="text-[0.62rem] uppercase tracking-[0.22em] text-[#d8b97d]">Notas</p>
                        <p class="prose-tavern mt-2 text-sm leading-relaxed text-[#e6d7b8]">{{ $character->notes }}</p>
                    </div>
                @endif
            </section>
        @endif
    </div>
</x-app-layout>
