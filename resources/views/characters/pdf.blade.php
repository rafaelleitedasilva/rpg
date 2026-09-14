@php
    use App\Modules\Rpg\Services\Dnd5eCharacterService;

    // Fonts are vector data dompdf embeds directly, but raster images (the
    // brand mark, portraits) go through dompdf's image decoder, which needs
    // the GD or Imagick PHP extension. Skip them gracefully if neither is
    // installed rather than failing the whole export.
    $fontData = fn (string $file) => base64_encode(file_get_contents(resource_path("fonts/{$file}")));
    $hasImageSupport = extension_loaded('gd') || extension_loaded('imagick');
    $dragonLogo = $hasImageSupport ? base64_encode(file_get_contents(resource_path('images/brand-dragon.png'))) : null;

    $coinLabels = ['coins_cp' => 'PC', 'coins_sp' => 'PP', 'coins_ep' => 'PE', 'coins_gp' => 'PO', 'coins_pp' => 'PL'];
    $appearance = collect(['age' => 'Idade', 'height' => 'Altura', 'weight' => 'Peso', 'eyes' => 'Olhos', 'skin' => 'Pele', 'hair' => 'Cabelo'])
        ->filter(fn ($label, $key) => filled($character->$key));

    // The embedded webfonts don't carry the "●"/"○" glyphs used on the live
    // sheet (dompdf has no fallback font for a missing glyph, it just shows
    // a tofu box), so proficiency markers are drawn as small CSS circles
    // instead of relying on a specific character being in the font.
    $dot = fn (bool $filled) => '<span class="pdf-dot" style="background:'.($filled ? '#e9c274' : 'transparent').';"></span>';
    $dotRow = fn (int $filled, int $total) => collect(range(1, $total))->map(fn ($i) => $dot($i <= $filled))->implode('');
@endphp
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>{{ $character->name }} — Guildhall</title>
<style>
    @font-face { font-family: 'DM Sans'; font-weight: 400; font-style: normal; src: url(data:font/woff;base64,{{ $fontData('DMSans-Regular.woff') }}) format('woff'); }
    @font-face { font-family: 'DM Sans'; font-weight: 500; font-style: normal; src: url(data:font/woff;base64,{{ $fontData('DMSans-Medium.woff') }}) format('woff'); }
    @font-face { font-family: 'DM Sans'; font-weight: 600; font-style: normal; src: url(data:font/woff;base64,{{ $fontData('DMSans-SemiBold.woff') }}) format('woff'); }
    @font-face { font-family: 'DM Sans'; font-weight: 700; font-style: normal; src: url(data:font/woff;base64,{{ $fontData('DMSans-Bold.woff') }}) format('woff'); }
    @font-face { font-family: 'Marcellus SC'; font-weight: 400; font-style: normal; src: url(data:font/woff;base64,{{ $fontData('MarcellusSC-Regular.woff') }}) format('woff'); }

    @page { margin: 108px 34px 46px 34px; }

    /*
        White A4 page with the Guildhall dark/gold identity carried by the
        card accents (stat tiles, prose boxes) rather than the whole page —
        a full dark background looked out of place against the paper white
        a printed/exported document is expected to have.
    */
    html, body { background: #ffffff; }

    body {
        margin: 0;
        padding: 0;
        color: #1c2333;
        font-family: 'DM Sans', sans-serif;
        font-size: 9.5px;
        line-height: 1.5;
    }

    /* Repeating header/footer — see the dompdf notes in CharacterController::exportPdf(). */
    .pdf-header {
        position: fixed;
        top: -88px;
        left: 0;
        right: 0;
        height: 62px;
        padding-bottom: 10px;
        border-bottom: 2px solid #d4a24c;
    }

    .pdf-header-inner { width: 100%; border-collapse: collapse; }
    .pdf-header-inner td { vertical-align: middle; }
    .pdf-brand-mark { width: 34px; height: 34px; }
    .pdf-brand-name { font-family: 'Marcellus SC', serif; font-size: 15px; letter-spacing: 0.04em; color: #1c2333; }
    .pdf-brand-sub { margin-top: 2px; font-size: 8px; text-transform: uppercase; letter-spacing: 0.16em; color: #8089a0; }
    .pdf-header-meta { text-align: right; font-size: 8px; line-height: 1.6; color: #8089a0; }

    .pdf-footer {
        position: fixed;
        bottom: -34px;
        left: 0;
        right: 0;
        height: 20px;
        padding-top: 6px;
        border-top: 1px solid #e3e6ec;
        font-size: 8px;
        color: #8089a0;
    }

    .pdf-footer-inner { width: 100%; border-collapse: collapse; }
    .pdf-footer-inner .right { text-align: right; }
    .pagenum:before { content: counter(page); }

    .pdf-title { width: 100%; border-collapse: collapse; margin-bottom: 4px; }
    .pdf-title-name { font-family: 'Marcellus SC', serif; font-size: 25px; text-transform: uppercase; letter-spacing: 0.02em; color: #1c2333; }
    .pdf-title-meta { margin-top: 4px; font-size: 10px; color: #5b6472; }
    .pdf-title-tags { margin-top: 8px; }
    .pdf-portrait { width: 64px; height: 64px; border-radius: 8px; object-fit: cover; border: 1px solid #e3e6ec; }

    .pdf-tag {
        display: inline-block;
        margin: 0 6px 6px 0;
        padding: 3px 9px;
        border: 1px solid #dcc38c;
        border-radius: 999px;
        background: #fbf5e8;
        font-size: 7.5px;
        letter-spacing: 0.05em;
        text-transform: uppercase;
        color: #8a6a2c;
    }

    .pdf-card { margin-top: 13px; }

    .pdf-card-title {
        margin: 0 0 9px;
        padding-bottom: 5px;
        border-bottom: 1px solid #e3e6ec;
        font-family: 'Marcellus SC', serif;
        font-size: 13px;
        color: #1c2333;
    }

    .pdf-card-title:before {
        content: "";
        display: inline-block;
        width: 7px;
        height: 7px;
        margin-right: 7px;
        background: #d4a24c;
        border-radius: 2px;
    }

    /* Stat tiles keep the dark/gold card look — the accent that carries the
       app's identity — sitting on the white page instead of covering it. */
    table.pdf-grid { width: 100%; border-collapse: separate; border-spacing: 5px; margin: -5px 0 -5px -5px; }
    .pdf-stat { vertical-align: top; background: #111a2c; border: 1px solid #263049; border-radius: 6px; padding: 6px 8px; }
    .pdf-stat-filler { background: none; border: none; }
    .pdf-stat-label { font-size: 7px; text-transform: uppercase; letter-spacing: 0.08em; color: #9aa8c0; }
    .pdf-stat-value { margin-top: 3px; font-size: 12px; font-weight: 700; color: #f4f2ec; }
    .pdf-stat-hint { margin-top: 2px; font-size: 7.5px; color: #9aa8c0; }
    .pdf-stat-hint .pdf-dot { border-color: #e9c274; }
    .pdf-dot { display: inline-block; width: 6px; height: 6px; margin-right: 2px; border: 1px solid #e9c274; border-radius: 50%; }
    .pdf-stat-list { margin: 4px 0 0; padding-left: 11px; color: #e7ecf6; }
    .pdf-stat-list li { margin-bottom: 2px; }

    .pdf-badges { margin-top: 4px; }

    .pdf-badge {
        display: inline-block;
        margin: 0 4px 4px 0;
        padding: 3px 8px;
        border-radius: 999px;
        background: #1c2a45;
        border: 1px solid #33415c;
        color: #dbe1ec;
        font-size: 7.5px;
    }

    .pdf-badge-accent { background: rgba(212, 162, 76, 0.2); border-color: rgba(212, 162, 76, 0.6); color: #e9c274; }

    table.pdf-table { width: 100%; border-collapse: collapse; font-size: 8.5px; margin-top: 4px; }
    table.pdf-table th, table.pdf-table td { text-align: left; padding: 5px 7px; border-bottom: 1px solid #e3e6ec; }
    table.pdf-table th { color: #8089a0; font-size: 7.5px; text-transform: uppercase; letter-spacing: 0.05em; font-weight: 600; }
    table.pdf-table td { color: #1c2333; }

    table.pdf-skills { width: 100%; }
    table.pdf-skills td { padding: 2px 0; color: #e7ecf6; }
    table.pdf-skills td.bonus { text-align: right; font-weight: 700; color: #f4f2ec; white-space: nowrap; }

    /* Prose boxes (backstory, notes...) also stay as dark cards, matching the stat tiles. */
    .pdf-prose-box { margin-top: 8px; background: #111a2c; border: 1px solid #263049; border-radius: 6px; padding: 8px 10px; }
    .pdf-prose-box p { margin: 4px 0 0; font-size: 8.5px; line-height: 1.6; color: #d7dbe5; }
</style>
</head>
<body>

<div class="pdf-header">
    <table class="pdf-header-inner">
        <tr>
            @if ($dragonLogo)
                <td style="width: 40px;"><img src="data:image/png;base64,{{ $dragonLogo }}" class="pdf-brand-mark"></td>
            @endif
            <td>
                <div class="pdf-brand-name">GUILDHALL</div>
                <div class="pdf-brand-sub">Ficha de personagem</div>
            </td>
            <td class="pdf-header-meta">
                Gerado em {{ now()->format('d/m/Y') }}<br>
                {{ $character->name }}
            </td>
        </tr>
    </table>
</div>

<div class="pdf-footer">
    <table class="pdf-footer-inner">
        <tr>
            <td>Guildhall — sua mesa de RPG</td>
            <td class="right">Página <span class="pagenum"></span></td>
        </tr>
    </table>
</div>

<table class="pdf-title">
    <tr>
        <td style="vertical-align: top;">
            <div class="pdf-title-name">{{ $character->name }}</div>
            <div class="pdf-title-meta">
                {{ $character->race }} · {{ $character->class }}@if ($character->secondary_class) / {{ $character->secondary_class }}@endif · Nível {{ $character->level }}
            </div>
            <div class="pdf-title-tags">
                @if ($character->campaign_name)<span class="pdf-tag">{{ $character->campaign_name }}</span>@endif
                @if ($character->background)<span class="pdf-tag">{{ $character->background }}</span>@endif
                @if ($character->alignment)<span class="pdf-tag">{{ $character->alignment }}</span>@endif
            </div>
        </td>
        @if (($portraitSrc ?? null) && $hasImageSupport)
            <td style="width: 70px; text-align: right; vertical-align: top;">
                <img src="{{ $portraitSrc }}" class="pdf-portrait">
            </td>
        @endif
    </tr>
</table>

{{-- Identificação --}}
<div class="pdf-card">
    <h2 class="pdf-card-title">Identificação</h2>
    @php
        $identityItems = [];
        foreach ([
            'player_name' => 'Jogador', 'master_name' => 'Mestre', 'campaign_name' => 'Campanha', 'deity' => 'Divindade',
            'background' => 'Antecedente', 'alignment' => 'Tendência', 'size' => 'Tamanho',
        ] as $field => $label) {
            if ($character->$field) {
                $identityItems[] = ['label' => $label, 'value' => $character->$field];
            }
        }
        $identityItems[] = ['label' => 'Experiência', 'value' => number_format($character->experience, 0, ',', '.').' XP'];
    @endphp
    @include('characters.partials.pdf-stat-grid', ['items' => $identityItems, 'columns' => 4])
</div>

{{-- Atributos & resistências --}}
<div class="pdf-card">
    <h2 class="pdf-card-title">Atributos & resistências</h2>
    @php
        $abilityItems = [];
        foreach (Dnd5eCharacterService::ABILITIES as $abilityKey => $abilityLabel) {
            $mod = $character->abilityModifier($abilityKey);
            $save = $character->savingThrowBonus($abilityKey);
            $proficient = $character->isProficientInSavingThrow($abilityKey);
            $abilityItems[] = [
                'label' => $abilityLabel,
                'value' => $character->$abilityKey,
                'hint' => 'Mod '.($mod >= 0 ? '+' : '').$mod.' · '.$dot($proficient).' Resist. '.($save >= 0 ? '+' : '').$save,
            ];
        }
    @endphp
    @include('characters.partials.pdf-stat-grid', ['items' => $abilityItems, 'columns' => 6])

    @include('characters.partials.pdf-stat-grid', ['columns' => 3, 'items' => [
        ['label' => 'Bônus de proficiência', 'value' => '+'.$character->proficiency_bonus],
        ['label' => 'Percepção passiva', 'value' => $character->passive_perception],
        ['label' => 'Inspiração', 'value' => $character->inspiration ? 'Sim' : 'Não'],
    ]])
</div>

{{-- Perícias --}}
<div class="pdf-card">
    <h2 class="pdf-card-title">Perícias</h2>
    @php $skillsByAbility = collect($character->skillsBreakdown())->groupBy('ability'); @endphp
    <table class="pdf-grid">
        @foreach (array_chunk(array_keys(Dnd5eCharacterService::ABILITIES), 3) as $abilityRow)
            <tr>
                @foreach ($abilityRow as $abilityKey)
                    @php $skills = $skillsByAbility->get($abilityKey, collect()); @endphp
                    <td class="pdf-stat" style="width: 33.3333%;">
                        @if ($skills->isNotEmpty())
                            <div class="pdf-stat-label">{{ Dnd5eCharacterService::ABILITIES[$abilityKey] }}</div>
                            <table class="pdf-skills">
                                @foreach ($skills as $skill)
                                    <tr>
                                        <td>{!! $dot($skill['proficient']) !!} {{ $skill['label'] }}@if ($skill['expertise']) <span class="pdf-badge pdf-badge-accent" style="padding:1px 5px;">exp.</span>@endif</td>
                                        <td class="bonus">{{ $skill['bonus'] >= 0 ? '+' : '' }}{{ $skill['bonus'] }}</td>
                                    </tr>
                                @endforeach
                            </table>
                        @endif
                    </td>
                @endforeach
            </tr>
        @endforeach
    </table>
</div>

{{-- Combate --}}
<div class="pdf-card">
    <h2 class="pdf-card-title">Combate</h2>
    @include('characters.partials.pdf-stat-grid', ['columns' => 6, 'items' => [
        ['label' => 'CA', 'value' => $character->armor_class],
        ['label' => 'Iniciativa', 'value' => ($character->initiative >= 0 ? '+' : '').$character->initiative],
        ['label' => 'PV', 'value' => $character->current_hp.'/'.$character->max_hp],
        ['label' => 'PV temp.', 'value' => $character->temp_hp],
        ['label' => 'Deslocamento', 'value' => $character->speed.'pés'],
        ['label' => 'Dado de vida', 'value' => '1d'.$character->hit_dice],
    ]])

    @if ($character->current_hp <= 0)
        <div class="pdf-prose-box">
            <div class="pdf-stat-label">Testes de morte</div>
            <p>
                Sucessos: {!! $dotRow($character->death_save_successes, 3) !!}
                &nbsp;&nbsp; Falhas: {!! $dotRow($character->death_save_failures, 3) !!}
            </p>
        </div>
    @endif

    @if ($character->attacks)
        <table class="pdf-table">
            <thead><tr><th>Nome</th><th>Bônus de ataque</th><th>Dano / Tipo</th></tr></thead>
            <tbody>
                @foreach ($character->attacks as $attack)
                    <tr><td>{{ $attack['name'] ?? '' }}</td><td>{{ $attack['bonus'] ?? '' }}</td><td>{{ $attack['damage'] ?? '' }}</td></tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>

{{-- Proficiências, idiomas & equipamento --}}
@php
    $gearItems = [];
    foreach ([
        'armor_proficiencies' => 'Armaduras', 'weapon_proficiencies' => 'Armas', 'tool_proficiencies' => 'Ferramentas',
        'languages' => 'Idiomas', 'proficiencies' => 'Outras proficiências', 'equipment' => 'Equipamentos',
    ] as $field => $label) {
        if ($character->$field) {
            $gearItems[] = ['label' => $label, 'list' => $character->$field];
        }
    }
    $hasCoins = collect($coinLabels)->keys()->contains(fn ($key) => $character->$key > 0);
@endphp
@if ($gearItems || $hasCoins || $character->treasure)
    <div class="pdf-card">
        <h2 class="pdf-card-title">Proficiências, idiomas & equipamento</h2>
        @if ($gearItems)
            @include('characters.partials.pdf-stat-grid', ['items' => $gearItems, 'columns' => 2])
        @endif

        @if ($hasCoins || $character->treasure)
            <div class="pdf-prose-box">
                <div class="pdf-stat-label">Moedas & tesouro</div>
                @if ($hasCoins)
                    <div class="pdf-badges">
                        @foreach ($coinLabels as $field => $label)
                            @if ($character->$field > 0)<span class="pdf-badge">{{ $character->$field }} {{ $label }}</span>@endif
                        @endforeach
                    </div>
                @endif
                @if ($character->treasure)<p>{{ $character->treasure }}</p>@endif
            </div>
        @endif
    </div>
@endif

{{-- Conjuração --}}
@if ($character->hasSpellcasting() || $character->cantrips || $character->spells)
    <div class="pdf-card">
        <h2 class="pdf-card-title">Conjuração</h2>

        @php
            $spellHeaderItems = [];
            if ($character->spellcasting_ability) {
                $spellHeaderItems[] = ['label' => 'Atributo', 'value' => Dnd5eCharacterService::SPELLCASTING_ABILITIES[$character->spellcasting_ability] ?? $character->spellcasting_ability];
            }
            if ($character->hasSpellcasting()) {
                $spellHeaderItems[] = ['label' => 'CD de magia', 'value' => $character->spell_save_dc];
                $spellHeaderItems[] = ['label' => 'Ataque com magia', 'value' => ($character->spell_attack_bonus >= 0 ? '+' : '').$character->spell_attack_bonus];
            }
        @endphp
        @if ($spellHeaderItems)
            @include('characters.partials.pdf-stat-grid', ['items' => $spellHeaderItems, 'columns' => 3])
        @endif

        @if ($character->hasSpellcasting() && $character->spellSlots())
            @php
                $slotItems = collect($character->spellSlots())->map(fn ($amount, $level) => ['label' => 'Nível '.$level, 'value' => $amount])->values()->all();
            @endphp
            <div style="margin-top: 5px;">
                @include('characters.partials.pdf-stat-grid', ['items' => $slotItems, 'columns' => 5])
            </div>
        @endif

        @if ($character->cantrips)
            <div class="pdf-prose-box">
                <div class="pdf-stat-label">Truques</div>
                <div class="pdf-badges">
                    @foreach ($character->cantrips as $cantrip)<span class="pdf-badge">{{ $cantrip }}</span>@endforeach
                </div>
            </div>
        @endif

        @if ($character->spells)
            <div class="pdf-prose-box">
                <div class="pdf-stat-label">Magias conhecidas</div>
                <div class="pdf-badges">
                    @foreach ($character->spells as $spell)<span class="pdf-badge pdf-badge-accent">{{ $spell }}</span>@endforeach
                </div>
            </div>
        @endif
    </div>
@endif

{{-- Personalidade & história --}}
@php
    $storyItems = [];
    foreach (['personality_traits' => 'Traços', 'ideals' => 'Ideais', 'bonds' => 'Vínculos', 'flaws' => 'Defeitos', 'features' => 'Características e talentos'] as $field => $label) {
        if ($character->$field) {
            $storyItems[] = ['label' => $label, 'list' => $character->$field];
        }
    }
    $hasStory = $storyItems || $appearance->isNotEmpty() || $character->allies_organizations || $character->backstory || $character->notes;
@endphp
@if ($hasStory)
<div class="pdf-card">
    <h2 class="pdf-card-title">Personalidade & história</h2>

    @if ($storyItems)
        @include('characters.partials.pdf-stat-grid', ['items' => $storyItems, 'columns' => 2])
    @endif

    @if ($appearance->isNotEmpty())
        @php $appearanceItems = $appearance->map(fn ($label, $key) => ['label' => $label, 'value' => $character->$key])->values()->all(); @endphp
        <div style="margin-top: 5px;">
            @include('characters.partials.pdf-stat-grid', ['items' => $appearanceItems, 'columns' => 3])
        </div>
    @endif

    @if ($character->allies_organizations)
        <div class="pdf-prose-box"><div class="pdf-stat-label">Aliados e organizações</div><p>{{ $character->allies_organizations }}</p></div>
    @endif

    @if ($character->backstory)
        <div class="pdf-prose-box"><div class="pdf-stat-label">História</div><p>{{ $character->backstory }}</p></div>
    @endif

    @if ($character->notes)
        <div class="pdf-prose-box"><div class="pdf-stat-label">Notas</div><p>{{ $character->notes }}</p></div>
    @endif
</div>
@endif

</body>
</html>
