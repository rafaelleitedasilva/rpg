@php
    use App\Modules\Rpg\Services\Dnd5eCharacterService;

    /** @var \App\Models\Character|null $character */
    $c = $character ?? null;

    // Resolves a scalar field: old input first, then the character's stored value, then a default.
    $val = function (string $field, $default = null) use ($c) {
        if (old($field) !== null) {
            return old($field);
        }

        return $c->$field ?? $default;
    };

    // Resolves a json/array field into newline-separated text for a textarea.
    $listVal = function (string $field) use ($c) {
        if (old($field) !== null) {
            return old($field);
        }

        return implode("\n", $c->$field ?? []);
    };

    // Resolves the array of checked values for a checkbox group (saving throws, skills...).
    $checkedValues = function (string $field) use ($c) {
        if (old($field) !== null) {
            return (array) old($field);
        }

        return $c->$field ?? [];
    };

    $attacksVal = function () use ($c) {
        if (old('attacks') !== null) {
            return old('attacks');
        }

        return implode("\n", array_map(
            static fn ($attack) => trim(($attack['name'] ?? '').' | '.($attack['bonus'] ?? '').' | '.($attack['damage'] ?? '')),
            $c->attacks ?? []
        ));
    };

    $savingThrows = $checkedValues('saving_throw_proficiencies');
    $skillProficiencies = $checkedValues('skill_proficiencies');
    $skillExpertise = $checkedValues('skill_expertise');

    // Initial proficiency state per skill, so the expertise checkbox starts enabled/disabled correctly.
    $skillProficiencyMap = array_combine(
        array_keys(Dnd5eCharacterService::SKILLS),
        array_map(static fn ($key) => in_array($key, $skillProficiencies, true), array_keys(Dnd5eCharacterService::SKILLS))
    );
@endphp

<div
    x-data="{
        tab: 'identity',
        proficient: {{ \Illuminate\Support\Js::from($skillProficiencyMap) }},
        saving: false,
    }"
    class="sheet-fade-in"
>
    <nav class="sheet-tabs mb-6 flex flex-wrap gap-2" role="tablist">
        @foreach ([
            'identity' => 'Identificação',
            'abilities' => 'Atributos & Resistência',
            'skills' => 'Perícias',
            'combat' => 'Combate',
            'gear' => 'Proficiências & Equipamento',
            'spellcasting' => 'Conjuração',
            'story' => 'Personalidade & História',
        ] as $key => $label)
            <button
                type="button"
                @click="tab = '{{ $key }}'"
                :class="tab === '{{ $key }}' ? 'sheet-tab sheet-tab-active' : 'sheet-tab'"
                role="tab"
            >{{ $label }}</button>
        @endforeach
    </nav>

    {{-- 1. Identificação --}}
    <section x-show="tab === 'identity'" x-transition class="grid gap-6 md:grid-cols-2 xl:grid-cols-3">
        <div>
            <label class="label-block" for="name">Nome do personagem</label>
            <input id="name" name="name" value="{{ $val('name') }}" class="input-shell" required>
        </div>
        <div>
            <label class="label-block" for="player_name">Jogador</label>
            <input id="player_name" name="player_name" value="{{ $val('player_name') }}" class="input-shell">
        </div>
        <div>
            <label class="label-block" for="master_name">Mestre</label>
            <input id="master_name" name="master_name" value="{{ $val('master_name') }}" class="input-shell">
        </div>
        <div>
            <label class="label-block" for="campaign_name">Campanha</label>
            <input id="campaign_name" name="campaign_name" value="{{ $val('campaign_name') }}" class="input-shell">
        </div>
        <div>
            <label class="label-block" for="race">Raça / espécie</label>
            <input id="race" name="race" value="{{ $val('race', 'Humano') }}" class="input-shell" required>
        </div>
        <div>
            <label class="label-block" for="class">Classe</label>
            <input id="class" name="class" value="{{ $val('class', 'Mago') }}" class="input-shell" required>
        </div>
        <div>
            <label class="label-block" for="secondary_class">Segunda classe</label>
            <input id="secondary_class" name="secondary_class" value="{{ $val('secondary_class') }}" class="input-shell" placeholder="Ex.: Bardo">
        </div>
        <div>
            <label class="label-block" for="secondary_class_level">Nível da segunda classe</label>
            <input id="secondary_class_level" type="number" name="secondary_class_level" value="{{ $val('secondary_class_level') }}" min="1" max="20" class="input-shell" placeholder="1-20">
        </div>
        <div>
            <label class="label-block" for="level">Nível</label>
            <input id="level" type="number" name="level" value="{{ $val('level', 1) }}" min="1" max="20" class="input-shell" required>
        </div>
        <div>
            <label class="label-block" for="background">Antecedente</label>
            <input id="background" name="background" value="{{ $val('background', 'Aventureiro') }}" class="input-shell" required>
        </div>
        <div>
            <label class="label-block" for="alignment">Tendência</label>
            <select id="alignment" name="alignment" class="input-shell" required>
                @foreach (Dnd5eCharacterService::ALIGNMENTS as $alignment)
                    <option value="{{ $alignment }}" @selected($val('alignment', 'Neutro') === $alignment)>{{ $alignment }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="label-block" for="deity">Divindade</label>
            <input id="deity" name="deity" value="{{ $val('deity') }}" class="input-shell" placeholder="Opcional">
        </div>
        <div>
            <label class="label-block" for="size">Tamanho</label>
            <select id="size" name="size" class="input-shell">
                @foreach (Dnd5eCharacterService::SIZES as $size)
                    <option value="{{ $size }}" @selected($val('size', 'Médio') === $size)>{{ $size }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="label-block" for="experience">Experiência (XP)</label>
            <input id="experience" type="number" name="experience" value="{{ $val('experience', 0) }}" min="0" max="355000" class="input-shell">
        </div>
    </section>

    {{-- 2. Atributos & Resistência --}}
    <section x-show="tab === 'abilities'" x-transition class="grid gap-4">
        <p class="text-sm text-[#c9b79c]">Marque “proficiente” nos testes de resistência concedidos pela sua classe.</p>
        <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-3">
            @foreach (Dnd5eCharacterService::ABILITIES as $key => $label)
                <div class="stat-chip">
                    <label class="label-block" for="{{ $key }}">{{ $label }}</label>
                    <input id="{{ $key }}" type="number" name="{{ $key }}" value="{{ $val($key, 10) }}" min="1" max="30" class="input-shell" required>
                    <label class="proficiency-check mt-3">
                        <input type="checkbox" name="saving_throw_proficiencies[]" value="{{ $key }}" @checked(in_array($key, $savingThrows, true))>
                        <span>Proficiente no teste de resistência</span>
                    </label>
                </div>
            @endforeach
        </div>
    </section>

    {{-- 3. Perícias --}}
    <section x-show="tab === 'skills'" x-transition class="grid gap-6">
        <p class="text-sm text-[#c9b79c]">Marque a proficiência e, quando aplicável, a especialização (dobra o bônus de proficiência).</p>
        @foreach (Dnd5eCharacterService::ABILITIES as $abilityKey => $abilityLabel)
            @php
                $skillsForAbility = array_filter(Dnd5eCharacterService::SKILLS, fn ($skill) => $skill['ability'] === $abilityKey);
            @endphp
            @if ($skillsForAbility)
                <div class="rounded-2xl border border-[#a67a47]/40 bg-[#1b1512]/70 p-4">
                    <p class="text-[0.62rem] uppercase tracking-[0.22em] text-[#d8b97d]">{{ $abilityLabel }}</p>
                    <div class="mt-3 grid gap-3 sm:grid-cols-2">
                        @foreach ($skillsForAbility as $skillKey => $skill)
                            <div x-data="{ proficient: proficient['{{ $skillKey }}'] }" class="flex items-center justify-between gap-3 rounded-xl border border-[#a67a47]/25 bg-[#17110d]/70 px-3 py-2">
                                <span class="text-sm text-[#e6d7b8]">{{ $skill['label'] }}</span>
                                <div class="flex items-center gap-4">
                                    <label class="proficiency-check">
                                        <input type="checkbox" name="skill_proficiencies[]" value="{{ $skillKey }}" x-model="proficient" @checked(in_array($skillKey, $skillProficiencies, true))>
                                        <span>Proficiente</span>
                                    </label>
                                    <label class="proficiency-check" :class="!proficient && 'opacity-40'">
                                        <input type="checkbox" name="skill_expertise[]" value="{{ $skillKey }}" :disabled="!proficient" @checked(in_array($skillKey, $skillExpertise, true))>
                                        <span>Expertise</span>
                                    </label>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        @endforeach
    </section>

    {{-- 4. Combate --}}
    <section x-show="tab === 'combat'" x-transition class="grid gap-6">
        <div class="grid gap-6 md:grid-cols-2 xl:grid-cols-4">
            <div>
                <label class="label-block" for="armor_class">Classe de armadura</label>
                <input id="armor_class" type="number" name="armor_class" value="{{ $val('armor_class', 10) }}" min="1" max="50" class="input-shell">
            </div>
            <div>
                <label class="label-block" for="speed">Deslocamento (pés)</label>
                <input id="speed" type="number" name="speed" value="{{ $val('speed', 30) }}" min="1" max="120" class="input-shell">
            </div>
            <div>
                <label class="label-block" for="max_hp">Pontos de vida (máx.)</label>
                <input id="max_hp" type="number" name="max_hp" value="{{ $val('max_hp', 8) }}" min="1" max="500" class="input-shell">
            </div>
            <div>
                <label class="label-block" for="current_hp">PV atuais</label>
                <input id="current_hp" type="number" name="current_hp" value="{{ $val('current_hp', 8) }}" min="0" max="500" class="input-shell">
            </div>
            <div>
                <label class="label-block" for="temp_hp">PV temporários</label>
                <input id="temp_hp" type="number" name="temp_hp" value="{{ $val('temp_hp', 0) }}" min="0" max="200" class="input-shell">
            </div>
            <div>
                <label class="label-block" for="hit_dice">Dados de vida</label>
                <input id="hit_dice" type="number" name="hit_dice" value="{{ $val('hit_dice', 8) }}" min="1" max="20" class="input-shell">
            </div>
            <div class="flex items-end">
                <label class="proficiency-check">
                    <input type="checkbox" name="inspiration" value="1" @checked($val('inspiration', false))>
                    <span>Inspiração</span>
                </label>
            </div>
        </div>

        <div class="grid gap-6 sm:grid-cols-2">
            <div class="rounded-2xl border border-[#a67a47]/40 bg-[#1b1512]/70 p-4">
                <p class="text-[0.62rem] uppercase tracking-[0.22em] text-[#d8b97d]">Testes de morte — sucessos</p>
                <div class="mt-3 flex gap-4">
                    @for ($i = 1; $i <= 3; $i++)
                        <label class="proficiency-check">
                            <input type="radio" name="death_save_successes" value="{{ $i }}" @checked((int) $val('death_save_successes', 0) === $i)>
                            <span>{{ $i }}</span>
                        </label>
                    @endfor
                    <label class="proficiency-check">
                        <input type="radio" name="death_save_successes" value="0" @checked((int) $val('death_save_successes', 0) === 0)>
                        <span>Nenhum</span>
                    </label>
                </div>
            </div>
            <div class="rounded-2xl border border-[#a67a47]/40 bg-[#1b1512]/70 p-4">
                <p class="text-[0.62rem] uppercase tracking-[0.22em] text-[#d8b97d]">Testes de morte — falhas</p>
                <div class="mt-3 flex gap-4">
                    @for ($i = 1; $i <= 3; $i++)
                        <label class="proficiency-check">
                            <input type="radio" name="death_save_failures" value="{{ $i }}" @checked((int) $val('death_save_failures', 0) === $i)>
                            <span>{{ $i }}</span>
                        </label>
                    @endfor
                    <label class="proficiency-check">
                        <input type="radio" name="death_save_failures" value="0" @checked((int) $val('death_save_failures', 0) === 0)>
                        <span>Nenhuma</span>
                    </label>
                </div>
            </div>
        </div>

        <div>
            <label class="label-block" for="attacks">Ataques e magias de ataque</label>
            <p class="mb-2 text-xs text-[#c9b79c]">Uma linha por ataque, no formato: Nome | Bônus de ataque | Dano/Tipo. Ex.: Espada longa | +5 | 1d8+3 cortante</p>
            <textarea id="attacks" name="attacks" rows="4" class="input-shell" placeholder="Espada longa | +5 | 1d8+3 cortante">{{ $attacksVal() }}</textarea>
        </div>
    </section>

    {{-- 5. Proficiências, idiomas & equipamento --}}
    <section x-show="tab === 'gear'" x-transition class="grid gap-6">
        <div class="grid gap-6 lg:grid-cols-2">
            <div>
                <label class="label-block" for="armor_proficiencies">Proficiências em armaduras</label>
                <textarea id="armor_proficiencies" name="armor_proficiencies" rows="2" class="input-shell" placeholder="Leves, Médias, Escudos">{{ $listVal('armor_proficiencies') }}</textarea>
            </div>
            <div>
                <label class="label-block" for="weapon_proficiencies">Proficiências em armas</label>
                <textarea id="weapon_proficiencies" name="weapon_proficiencies" rows="2" class="input-shell" placeholder="Armas simples, Espadas longas">{{ $listVal('weapon_proficiencies') }}</textarea>
            </div>
            <div>
                <label class="label-block" for="tool_proficiencies">Ferramentas</label>
                <textarea id="tool_proficiencies" name="tool_proficiencies" rows="2" class="input-shell" placeholder="Ferramentas de ladrão, Alaúde">{{ $listVal('tool_proficiencies') }}</textarea>
            </div>
            <div>
                <label class="label-block" for="languages">Idiomas</label>
                <textarea id="languages" name="languages" rows="2" class="input-shell" placeholder="Comum, Élfico">{{ $listVal('languages') }}</textarea>
            </div>
        </div>

        <div>
            <label class="label-block" for="equipment">Equipamentos</label>
            <textarea id="equipment" name="equipment" rows="4" class="input-shell" placeholder="Mochila, Corda (15m), Tocha, Rações de viagem (5 dias)">{{ $listVal('equipment') }}</textarea>
        </div>

        <div class="grid gap-4 sm:grid-cols-5">
            @foreach (['coins_cp' => 'PC', 'coins_sp' => 'PP (prata)', 'coins_ep' => 'PE', 'coins_gp' => 'PO', 'coins_pp' => 'PL'] as $key => $label)
                <div>
                    <label class="label-block" for="{{ $key }}">{{ $label }}</label>
                    <input id="{{ $key }}" type="number" name="{{ $key }}" value="{{ $val($key, 0) }}" min="0" class="input-shell">
                </div>
            @endforeach
        </div>

        <div>
            <label class="label-block" for="treasure">Tesouro e itens mágicos</label>
            <textarea id="treasure" name="treasure" rows="3" class="input-shell">{{ $val('treasure') }}</textarea>
        </div>
    </section>

    {{-- 6. Conjuração --}}
    <section x-show="tab === 'spellcasting'" x-transition class="grid gap-6">
        <div class="grid gap-6 sm:grid-cols-2">
            <div>
                <label class="label-block" for="spellcasting_ability">Atributo de conjuração</label>
                <select id="spellcasting_ability" name="spellcasting_ability" class="input-shell">
                    <option value="">Automático pela classe</option>
                    @foreach (Dnd5eCharacterService::SPELLCASTING_ABILITIES as $key => $label)
                        <option value="{{ $key }}" @selected($val('spellcasting_ability') === $key)>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div>
            <label class="label-block" for="cantrips">Truques (cantrips)</label>
            <textarea id="cantrips" name="cantrips" rows="3" class="input-shell" placeholder="Prestidigitação, Luz">{{ $listVal('cantrips') }}</textarea>
        </div>
        <div>
            <label class="label-block" for="spells">Magias conhecidas/preparadas</label>
            <textarea id="spells" name="spells" rows="4" class="input-shell" placeholder="Míssil Mágico, Escudo Arcano, Identificação">{{ $listVal('spells') }}</textarea>
        </div>
    </section>

    {{-- 7. Personalidade & história --}}
    <section x-show="tab === 'story'" x-transition class="grid gap-6">
        <div class="grid gap-6 lg:grid-cols-2">
            <div>
                <label class="label-block" for="personality_traits">Traços de personalidade</label>
                <textarea id="personality_traits" name="personality_traits" rows="3" class="input-shell">{{ $listVal('personality_traits') }}</textarea>
            </div>
            <div>
                <label class="label-block" for="ideals">Ideais</label>
                <textarea id="ideals" name="ideals" rows="3" class="input-shell">{{ $listVal('ideals') }}</textarea>
            </div>
            <div>
                <label class="label-block" for="bonds">Vínculos</label>
                <textarea id="bonds" name="bonds" rows="3" class="input-shell">{{ $listVal('bonds') }}</textarea>
            </div>
            <div>
                <label class="label-block" for="flaws">Defeitos</label>
                <textarea id="flaws" name="flaws" rows="3" class="input-shell">{{ $listVal('flaws') }}</textarea>
            </div>
        </div>

        <div class="grid gap-6 lg:grid-cols-2">
            <div>
                <label class="label-block" for="features">Características e talentos</label>
                <textarea id="features" name="features" rows="3" class="input-shell">{{ $listVal('features') }}</textarea>
            </div>
            <div>
                <label class="label-block" for="proficiencies">Outras proficiências</label>
                <textarea id="proficiencies" name="proficiencies" rows="3" class="input-shell">{{ $listVal('proficiencies') }}</textarea>
            </div>
        </div>

        <div>
            <label class="label-block" for="allies_organizations">Aliados e organizações</label>
            <textarea id="allies_organizations" name="allies_organizations" rows="3" class="input-shell">{{ $val('allies_organizations') }}</textarea>
        </div>

        <div>
            <label class="label-block" for="backstory">História do personagem</label>
            <textarea id="backstory" name="backstory" rows="5" class="input-shell">{{ $val('backstory') }}</textarea>
        </div>

        <div class="grid gap-6 sm:grid-cols-2 xl:grid-cols-3">
            @foreach (['age' => 'Idade', 'height' => 'Altura', 'weight' => 'Peso', 'eyes' => 'Olhos', 'skin' => 'Pele', 'hair' => 'Cabelo'] as $key => $label)
                <div>
                    <label class="label-block" for="{{ $key }}">{{ $label }}</label>
                    <input id="{{ $key }}" name="{{ $key }}" value="{{ $val($key) }}" class="input-shell">
                </div>
            @endforeach
            <div class="sm:col-span-2 xl:col-span-3">
                <label class="label-block" for="portrait_url">Retrato (URL da imagem)</label>
                <input id="portrait_url" type="url" name="portrait_url" value="{{ $val('portrait_url') }}" class="input-shell" placeholder="https://...">
            </div>
        </div>

        <div>
            <label class="label-block" for="notes">Notas</label>
            <textarea id="notes" name="notes" rows="4" class="input-shell">{{ $val('notes') }}</textarea>
        </div>
    </section>

    <div class="mt-8 flex justify-end gap-3">
        <a href="{{ $c ? route('characters.show', $c) : route('characters.index') }}" class="secondary-button">Cancelar</a>
        <button type="submit" class="golden-button" @click="saving = true" :disabled="saving">
            <span x-show="!saving">{{ $c ? 'Salvar alterações' : 'Salvar ficha' }}</span>
            <span x-show="saving" x-cloak>Salvando...</span>
        </button>
    </div>
</div>
