@php
    use App\Modules\Rpg\Services\Dnd5eCharacterService;

    /** @var \App\Models\Character|null $character */
    $c = $character ?? null;
    $sections = include resource_path('views/characters/partials/sections.php');
    $sectionKeys = array_keys($sections);
    $prevOf = fn (string $key) => array_search($key, $sectionKeys, true) > 0 ? $sectionKeys[array_search($key, $sectionKeys, true) - 1] : null;
    $nextOf = fn (string $key) => $sectionKeys[array_search($key, $sectionKeys, true) + 1] ?? null;

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
@endphp

@foreach ($sections as $key => $section)
    <section x-show="tab === '{{ $key }}'" x-cloak x-transition.opacity.duration.150ms role="tabpanel" id="panel-{{ $key }}" aria-labelledby="tab-{{ $key }}">

        @if ($key === 'identity')
            <div class="gh-card">
                <h3 class="gh-card-title"><x-gh-icon name="user"/> Informações básicas</h3>
                <p class="gh-card-description">Preencha os dados principais do seu personagem.</p>

                <div class="mt-5 grid gap-4 sm:grid-cols-2 xl:grid-cols-3">
                    <div class="gh-field @error('name') gh-field-has-error @enderror">
                        <label class="gh-label gh-label-required" for="name">Nome do personagem</label>
                        <input id="name" name="name" value="{{ $val('name') }}" class="gh-input" required>
                        @error('name')<p class="gh-field-error">{{ $message }}</p>@enderror
                    </div>
                    <div class="gh-field">
                        <label class="gh-label" for="player_name">Jogador</label>
                        <input id="player_name" name="player_name" value="{{ $val('player_name') }}" class="gh-input" placeholder="Digite o nome do jogador">
                    </div>
                    <div class="gh-field">
                        <label class="gh-label" for="master_name">Mestre</label>
                        <input id="master_name" name="master_name" value="{{ $val('master_name') }}" class="gh-input" placeholder="Digite o nome do mestre">
                    </div>
                    <div class="gh-field">
                        <label class="gh-label" for="campaign_name">Campanha</label>
                        <input id="campaign_name" name="campaign_name" value="{{ $val('campaign_name') }}" class="gh-input" placeholder="Opcional">
                    </div>
                    <div class="gh-field @error('race') gh-field-has-error @enderror">
                        <label class="gh-label gh-label-required" for="race">Raça / espécie</label>
                        <input id="race" name="race" value="{{ $val('race', 'Humano') }}" class="gh-input" required>
                        @error('race')<p class="gh-field-error">{{ $message }}</p>@enderror
                    </div>
                    <div class="gh-field @error('class') gh-field-has-error @enderror">
                        <label class="gh-label gh-label-required" for="class">Classe</label>
                        <input id="class" name="class" value="{{ $val('class', 'Mago') }}" class="gh-input" required>
                        @error('class')<p class="gh-field-error">{{ $message }}</p>@enderror
                    </div>
                </div>
            </div>

            <div class="gh-card">
                <h3 class="gh-card-title"><x-gh-icon name="shield"/> Classificação do personagem</h3>
                <p class="gh-card-description">Nível, antecedente e demais dados que definem seu personagem no mundo.</p>

                <div class="mt-5 grid gap-4 sm:grid-cols-2 xl:grid-cols-3">
                    <div class="gh-field">
                        <label class="gh-label" for="secondary_class">Segunda classe</label>
                        <input id="secondary_class" name="secondary_class" x-model="secondaryClass" value="{{ $val('secondary_class') }}" class="gh-input" placeholder="Ex.: Bardo">
                        <p class="gh-hint">Deixe em branco se o personagem não tiver multiclasse.</p>
                    </div>
                    <div class="gh-field">
                        <label class="gh-label" for="secondary_class_level">
                            Nível da segunda classe
                            <x-gh-tooltip label="O que é isso?">Quantos níveis o personagem tem na segunda classe (multiclasse). Só se aplica se você preencheu "Segunda classe".</x-gh-tooltip>
                        </label>
                        <input id="secondary_class_level" type="number" name="secondary_class_level" value="{{ $val('secondary_class_level') }}" min="1" max="20" class="gh-input" placeholder="1–20" :disabled="!secondaryClass">
                    </div>
                    <div class="gh-field @error('level') gh-field-has-error @enderror">
                        <label class="gh-label gh-label-required" for="level">
                            Nível
                            <x-gh-tooltip label="O que é isso?">Nível atual do seu personagem na classe principal. Define o bônus de proficiência e outros valores calculados automaticamente.</x-gh-tooltip>
                        </label>
                        <input id="level" type="number" name="level" value="{{ $val('level', 1) }}" min="1" max="20" class="gh-input" required>
                        @error('level')<p class="gh-field-error">{{ $message }}</p>@enderror
                    </div>
                    <div class="gh-field">
                        <label class="gh-label" for="background">
                            Antecedente
                            <x-gh-tooltip label="O que é isso?">A vida do personagem antes de se aventurar (ex.: Soldado, Criminoso, Acólito). Costuma conceder perícias e proficiências extras.</x-gh-tooltip>
                        </label>
                        <input id="background" name="background" value="{{ $val('background', 'Aventureiro') }}" class="gh-input">
                    </div>
                    <div class="gh-field">
                        <label class="gh-label" for="alignment">
                            Tendência
                            <x-gh-tooltip label="O que é isso?">Descreve a moral e a ética do personagem, cruzando Leal/Neutro/Caótico com Bom/Neutro/Mau.</x-gh-tooltip>
                        </label>
                        <select id="alignment" name="alignment" class="gh-select">
                            @foreach (Dnd5eCharacterService::ALIGNMENTS as $alignment)
                                <option value="{{ $alignment }}" @selected($val('alignment', 'Neutro') === $alignment)>{{ $alignment }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="gh-field">
                        <label class="gh-label" for="deity">
                            Divindade
                            <x-gh-tooltip label="O que é isso?">A divindade que o personagem venera, se houver. Comum para Clérigos e Paladinos.</x-gh-tooltip>
                        </label>
                        <input id="deity" name="deity" value="{{ $val('deity') }}" class="gh-input" placeholder="Opcional">
                    </div>
                    <div class="gh-field">
                        <label class="gh-label" for="size">
                            Tamanho
                            <x-gh-tooltip label="O que é isso?">Categoria de tamanho da raça (afeta alcance, espaço ocupado e algumas regras de combate).</x-gh-tooltip>
                        </label>
                        <select id="size" name="size" class="gh-select">
                            @foreach (Dnd5eCharacterService::SIZES as $size)
                                <option value="{{ $size }}" @selected($val('size', 'Médio') === $size)>{{ $size }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="gh-field">
                        <label class="gh-label" for="experience">Experiência (XP)</label>
                        <input id="experience" type="number" name="experience" value="{{ $val('experience', 0) }}" min="0" max="355000" class="gh-input">
                    </div>
                </div>
            </div>
        @endif

        @if ($key === 'abilities')
            <div class="gh-card">
                <h3 class="gh-card-title"><x-gh-icon name="shield"/> Atributos</h3>
                <p class="gh-card-description">
                    Marque "proficiente" nos testes de resistência concedidos pela sua classe
                    <x-gh-tooltip label="O que é um teste de resistência?">Um teste de resistência (saving throw) usa o modificador do atributo, mais o bônus de proficiência se o personagem for proficiente nele.</x-gh-tooltip>.
                </p>

                <div class="mt-5 grid gap-4 sm:grid-cols-2 xl:grid-cols-3">
                    @foreach (Dnd5eCharacterService::ABILITIES as $abilityKey => $abilityLabel)
                        <div class="gh-stat">
                            <label class="gh-label" for="{{ $abilityKey }}">{{ $abilityLabel }}</label>
                            <input id="{{ $abilityKey }}" type="number" name="{{ $abilityKey }}" value="{{ $val($abilityKey, 10) }}" min="1" max="30" class="gh-input mt-2" required>
                            <label class="gh-check mt-3">
                                <input type="checkbox" name="saving_throw_proficiencies[]" value="{{ $abilityKey }}" @checked(in_array($abilityKey, $savingThrows, true))>
                                <span>Proficiente no teste de resistência</span>
                            </label>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        @if ($key === 'skills')
            <div class="gh-card">
                <h3 class="gh-card-title"><x-gh-icon name="sparkles"/> Perícias</h3>
                <p class="gh-card-description">
                    Marque a proficiência e, quando aplicável, a
                    <x-gh-tooltip label="O que é expertise?">Expertise dobra o bônus de proficiência numa perícia em que o personagem já é proficiente. Alguns Ladinos e Bardos recebem isso.</x-gh-tooltip>
                    especialização.
                </p>

                <div class="mt-5 grid gap-4 lg:grid-cols-2">
                    @foreach (Dnd5eCharacterService::ABILITIES as $abilityKey => $abilityLabel)
                        @php
                            $skillsForAbility = array_filter(Dnd5eCharacterService::SKILLS, fn ($skill) => $skill['ability'] === $abilityKey);
                        @endphp
                        @if ($skillsForAbility)
                            <div class="gh-stat">
                                <p class="gh-stat-label">{{ $abilityLabel }}</p>
                                <div class="mt-3 space-y-2">
                                    @foreach ($skillsForAbility as $skillKey => $skill)
                                        <div x-data="{ proficient: proficient['{{ $skillKey }}'] }" class="flex items-center justify-between gap-3">
                                            <span class="text-sm" style="color: var(--gh-text)">{{ $skill['label'] }}</span>
                                            <div class="flex items-center gap-3">
                                                <label class="gh-check">
                                                    <input type="checkbox" name="skill_proficiencies[]" value="{{ $skillKey }}" x-model="proficient" @checked(in_array($skillKey, $skillProficiencies, true))>
                                                    <span>Proficiente</span>
                                                </label>
                                                <label class="gh-check" :class="!proficient && 'gh-check-disabled'">
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
                </div>
            </div>
        @endif

        @if ($key === 'combat')
            <div class="gh-card">
                <h3 class="gh-card-title"><x-gh-icon name="sword"/> Combate</h3>
                <p class="gh-card-description">Classe de armadura, pontos de vida e o que o personagem usa para atacar.</p>

                <div class="mt-5 grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
                    <div class="gh-field">
                        <label class="gh-label" for="armor_class">Classe de armadura</label>
                        <input id="armor_class" type="number" name="armor_class" value="{{ $val('armor_class', 10) }}" min="1" max="50" class="gh-input">
                    </div>
                    <div class="gh-field">
                        <label class="gh-label" for="speed">Deslocamento (pés)</label>
                        <input id="speed" type="number" name="speed" value="{{ $val('speed', 30) }}" min="1" max="120" class="gh-input">
                    </div>
                    <div class="gh-field">
                        <label class="gh-label" for="max_hp">Pontos de vida (máx.)</label>
                        <input id="max_hp" type="number" name="max_hp" value="{{ $val('max_hp', 8) }}" min="1" max="500" class="gh-input">
                    </div>
                    <div class="gh-field">
                        <label class="gh-label" for="current_hp">PV atuais</label>
                        <input id="current_hp" type="number" name="current_hp" value="{{ $val('current_hp', 8) }}" min="0" max="500" class="gh-input">
                    </div>
                    <div class="gh-field">
                        <label class="gh-label" for="temp_hp">PV temporários</label>
                        <input id="temp_hp" type="number" name="temp_hp" value="{{ $val('temp_hp', 0) }}" min="0" max="200" class="gh-input">
                    </div>
                    <div class="gh-field">
                        <label class="gh-label" for="hit_dice">Dados de vida</label>
                        <input id="hit_dice" type="number" name="hit_dice" value="{{ $val('hit_dice', 8) }}" min="1" max="20" class="gh-input">
                    </div>
                    <div class="gh-field justify-center">
                        <label class="gh-check">
                            <input type="checkbox" name="inspiration" value="1" @checked($val('inspiration', false))>
                            <span>Inspiração</span>
                        </label>
                        <x-gh-tooltip label="O que é inspiração?">Quando o Mestre concede inspiração, o jogador pode usá-la depois para ter vantagem numa jogada.</x-gh-tooltip>
                    </div>
                </div>
            </div>

            <div class="gh-card">
                <h3 class="gh-card-title">
                    Testes de morte
                    <x-gh-tooltip label="O que são testes de morte?">Feitos quando o personagem chega a 0 PV. Três sucessos estabilizam; três falhas costumam significar morte.</x-gh-tooltip>
                </h3>
                <div class="mt-4 grid gap-4 sm:grid-cols-2">
                    <div class="gh-stat">
                        <p class="gh-stat-label">Sucessos</p>
                        <div class="mt-3 flex gap-4">
                            <label class="gh-check"><input type="radio" name="death_save_successes" value="0" @checked((int) $val('death_save_successes', 0) === 0)><span>Nenhum</span></label>
                            @for ($i = 1; $i <= 3; $i++)
                                <label class="gh-check"><input type="radio" name="death_save_successes" value="{{ $i }}" @checked((int) $val('death_save_successes', 0) === $i)><span>{{ $i }}</span></label>
                            @endfor
                        </div>
                    </div>
                    <div class="gh-stat">
                        <p class="gh-stat-label">Falhas</p>
                        <div class="mt-3 flex gap-4">
                            <label class="gh-check"><input type="radio" name="death_save_failures" value="0" @checked((int) $val('death_save_failures', 0) === 0)><span>Nenhuma</span></label>
                            @for ($i = 1; $i <= 3; $i++)
                                <label class="gh-check"><input type="radio" name="death_save_failures" value="{{ $i }}" @checked((int) $val('death_save_failures', 0) === $i)><span>{{ $i }}</span></label>
                            @endfor
                        </div>
                    </div>
                </div>
            </div>

            <div class="gh-card">
                <h3 class="gh-card-title">Ataques e magias de ataque</h3>
                <p class="gh-card-description">Uma linha por ataque, no formato: Nome | Bônus de ataque | Dano/Tipo.</p>
                <textarea name="attacks" rows="4" class="gh-textarea mt-3" placeholder="Espada longa | +5 | 1d8+3 cortante">{{ $attacksVal() }}</textarea>
            </div>
        @endif

        @if ($key === 'gear')
            <div class="gh-card">
                <h3 class="gh-card-title"><x-gh-icon name="bag"/> Proficiências & idiomas</h3>
                <div class="mt-4 grid gap-4 lg:grid-cols-2">
                    <div class="gh-field">
                        <label class="gh-label" for="armor_proficiencies">Proficiências em armaduras</label>
                        <textarea id="armor_proficiencies" name="armor_proficiencies" rows="2" class="gh-textarea" placeholder="Leves, Médias, Escudos">{{ $listVal('armor_proficiencies') }}</textarea>
                    </div>
                    <div class="gh-field">
                        <label class="gh-label" for="weapon_proficiencies">Proficiências em armas</label>
                        <textarea id="weapon_proficiencies" name="weapon_proficiencies" rows="2" class="gh-textarea" placeholder="Armas simples, Espadas longas">{{ $listVal('weapon_proficiencies') }}</textarea>
                    </div>
                    <div class="gh-field">
                        <label class="gh-label" for="tool_proficiencies">Ferramentas</label>
                        <textarea id="tool_proficiencies" name="tool_proficiencies" rows="2" class="gh-textarea" placeholder="Ferramentas de ladrão, Alaúde">{{ $listVal('tool_proficiencies') }}</textarea>
                    </div>
                    <div class="gh-field">
                        <label class="gh-label" for="languages">Idiomas</label>
                        <textarea id="languages" name="languages" rows="2" class="gh-textarea" placeholder="Comum, Élfico">{{ $listVal('languages') }}</textarea>
                    </div>
                </div>
            </div>

            <div class="gh-card">
                <h3 class="gh-card-title">Equipamento & tesouro</h3>
                <div class="mt-4 gh-field">
                    <label class="gh-label" for="equipment">Equipamentos</label>
                    <textarea id="equipment" name="equipment" rows="4" class="gh-textarea" placeholder="Mochila, Corda (15m), Tocha, Rações de viagem (5 dias)">{{ $listVal('equipment') }}</textarea>
                </div>

                <div class="mt-4 grid gap-4 sm:grid-cols-5">
                    @foreach (['coins_cp' => 'PC', 'coins_sp' => 'PP', 'coins_ep' => 'PE', 'coins_gp' => 'PO', 'coins_pp' => 'PL'] as $coinKey => $coinLabel)
                        <div class="gh-field">
                            <label class="gh-label" for="{{ $coinKey }}">
                                {{ $coinLabel }}
                                @if ($coinKey === 'coins_ep')
                                    <x-gh-tooltip label="O que é PE?">Peças de electrum — uma moeda intermediária entre prata e ouro, pouco usada em algumas campanhas.</x-gh-tooltip>
                                @endif
                            </label>
                            <input id="{{ $coinKey }}" type="number" name="{{ $coinKey }}" value="{{ $val($coinKey, 0) }}" min="0" class="gh-input">
                        </div>
                    @endforeach
                </div>

                <div class="mt-4 gh-field">
                    <label class="gh-label" for="treasure">Tesouro e itens mágicos</label>
                    <textarea id="treasure" name="treasure" rows="3" class="gh-textarea">{{ $val('treasure') }}</textarea>
                </div>
            </div>
        @endif

        @if ($key === 'spellcasting')
            <div class="gh-card">
                <h3 class="gh-card-title"><x-gh-icon name="book"/> Conjuração</h3>
                <p class="gh-card-description">Deixe em branco se o personagem não conjurar magias.</p>

                <div class="mt-4 grid gap-4 sm:grid-cols-2">
                    <div class="gh-field">
                        <label class="gh-label" for="spellcasting_ability">
                            Atributo de conjuração
                            <x-gh-tooltip label="O que é isso?">Define a CD e o bônus de ataque das suas magias. Se deixar em automático, o sistema tenta adivinhar pela classe.</x-gh-tooltip>
                        </label>
                        <select id="spellcasting_ability" name="spellcasting_ability" class="gh-select">
                            <option value="">Automático pela classe</option>
                            @foreach (Dnd5eCharacterService::SPELLCASTING_ABILITIES as $abilityKey => $abilityLabel)
                                <option value="{{ $abilityKey }}" @selected($val('spellcasting_ability') === $abilityKey)>{{ $abilityLabel }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="mt-4 gh-field">
                    <label class="gh-label" for="cantrips">
                        Truques (cantrips)
                        <x-gh-tooltip label="Truques vs. magias">Truques são magias de nível 0: podem ser usadas quantas vezes quiser, sem gastar espaço de magia.</x-gh-tooltip>
                    </label>
                    <textarea id="cantrips" name="cantrips" rows="3" class="gh-textarea" placeholder="Prestidigitação, Luz">{{ $listVal('cantrips') }}</textarea>
                </div>
                <div class="mt-4 gh-field">
                    <label class="gh-label" for="spells">Magias conhecidas/preparadas</label>
                    <textarea id="spells" name="spells" rows="4" class="gh-textarea" placeholder="Míssil Mágico, Escudo Arcano, Identificação">{{ $listVal('spells') }}</textarea>
                </div>
            </div>
        @endif

        @if ($key === 'story')
            <div class="gh-card">
                <h3 class="gh-card-title"><x-gh-icon name="scroll"/> Personalidade</h3>
                <div class="mt-4 grid gap-4 lg:grid-cols-2">
                    <div class="gh-field">
                        <label class="gh-label" for="personality_traits">Traços de personalidade</label>
                        <textarea id="personality_traits" name="personality_traits" rows="3" class="gh-textarea">{{ $listVal('personality_traits') }}</textarea>
                    </div>
                    <div class="gh-field">
                        <label class="gh-label" for="ideals">Ideais</label>
                        <textarea id="ideals" name="ideals" rows="3" class="gh-textarea">{{ $listVal('ideals') }}</textarea>
                    </div>
                    <div class="gh-field">
                        <label class="gh-label" for="bonds">
                            Vínculos
                            <x-gh-tooltip label="O que são vínculos?">Pessoas, lugares ou coisas importantes para o personagem — dão motivação para a aventura.</x-gh-tooltip>
                        </label>
                        <textarea id="bonds" name="bonds" rows="3" class="gh-textarea">{{ $listVal('bonds') }}</textarea>
                    </div>
                    <div class="gh-field">
                        <label class="gh-label" for="flaws">Defeitos</label>
                        <textarea id="flaws" name="flaws" rows="3" class="gh-textarea">{{ $listVal('flaws') }}</textarea>
                    </div>
                </div>
            </div>

            <div class="gh-card">
                <h3 class="gh-card-title">Características & outras proficiências</h3>
                <div class="mt-4 grid gap-4 lg:grid-cols-2">
                    <div class="gh-field">
                        <label class="gh-label" for="features">Características e talentos</label>
                        <textarea id="features" name="features" rows="3" class="gh-textarea">{{ $listVal('features') }}</textarea>
                    </div>
                    <div class="gh-field">
                        <label class="gh-label" for="proficiencies">Outras proficiências</label>
                        <textarea id="proficiencies" name="proficiencies" rows="3" class="gh-textarea">{{ $listVal('proficiencies') }}</textarea>
                    </div>
                </div>
            </div>

            <div class="gh-card">
                <h3 class="gh-card-title">História</h3>
                <div class="mt-4 gh-field">
                    <label class="gh-label" for="allies_organizations">Aliados e organizações</label>
                    <textarea id="allies_organizations" name="allies_organizations" rows="3" class="gh-textarea">{{ $val('allies_organizations') }}</textarea>
                </div>
                <div class="mt-4 gh-field">
                    <label class="gh-label" for="backstory">História do personagem</label>
                    <textarea id="backstory" name="backstory" rows="5" class="gh-textarea">{{ $val('backstory') }}</textarea>
                </div>
            </div>

            <div class="gh-card">
                <h3 class="gh-card-title">Aparência</h3>
                <div class="mt-4 grid gap-4 sm:grid-cols-2 xl:grid-cols-3">
                    @foreach (['age' => 'Idade', 'height' => 'Altura', 'weight' => 'Peso', 'eyes' => 'Olhos', 'skin' => 'Pele', 'hair' => 'Cabelo'] as $fieldKey => $fieldLabel)
                        <div class="gh-field">
                            <label class="gh-label" for="{{ $fieldKey }}">{{ $fieldLabel }}</label>
                            <input id="{{ $fieldKey }}" name="{{ $fieldKey }}" value="{{ $val($fieldKey) }}" class="gh-input">
                        </div>
                    @endforeach
                    <div class="gh-field sm:col-span-2 xl:col-span-3">
                        <label class="gh-label" for="portrait_url">Retrato (URL da imagem)</label>
                        <input id="portrait_url" type="url" name="portrait_url" value="{{ $val('portrait_url') }}" class="gh-input" placeholder="https://...">
                    </div>
                </div>
            </div>

            <div class="gh-card">
                <h3 class="gh-card-title">Notas</h3>
                <textarea name="notes" rows="4" class="gh-textarea mt-3">{{ $val('notes') }}</textarea>
            </div>
        @endif

        <div class="gh-section-footer">
            @if ($prevOf($key))
                <button type="button" class="gh-btn gh-btn-ghost" @click="tab = '{{ $prevOf($key) }}'"><x-gh-icon name="arrow-left"/> Voltar</button>
            @else
                <span></span>
            @endif

            @if ($nextOf($key))
                <button type="button" class="gh-btn gh-btn-secondary" @click="tab = '{{ $nextOf($key) }}'">Próximo: {{ $sections[$nextOf($key)]['label'] }} <x-gh-icon name="arrow-right"/></button>
            @endif
        </div>
    </section>
@endforeach
