<?php

namespace App\Http\Controllers;

use App\Models\Character;
use App\Modules\Rpg\Services\Dnd5eCharacterService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class CharacterController extends Controller
{
    public function __construct(private readonly Dnd5eCharacterService $rules)
    {
    }

    public function index(): View
    {
        $characters = Character::where('user_id', auth()->id())->with('images')->latest()->get();

        $campaignsCount = $characters->pluck('campaign_name')->filter()->unique()->count();

        $filterOptions = [
            'campaigns' => $characters->pluck('campaign_name')->filter()->unique()->sort()->values(),
            'classes' => $characters->pluck('class')->filter()->unique()->sort()->values(),
            'races' => $characters->pluck('race')->filter()->unique()->sort()->values(),
            'levels' => $characters->pluck('level')->unique()->sort()->values(),
        ];

        return view('characters.index', compact('characters', 'campaignsCount', 'filterOptions'));
    }

    public function create(): View
    {
        return view('characters.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validateCharacterData($request);
        $validated['user_id'] = auth()->id();
        $validated = $this->prepareCharacterData($validated);

        $character = Character::create($validated);

        return redirect()->route('characters.show', $character)->with('status', 'Ficha criada com sucesso.');
    }

    public function show(Character $character): View
    {
        abort_unless($character->user_id === auth()->id(), 403);

        $character->loadMissing('user', 'images');

        return view('characters.show', compact('character'));
    }

    public function edit(Character $character): View
    {
        abort_unless($character->user_id === auth()->id(), 403);

        $character->loadMissing('images');

        return view('characters.edit', compact('character'));
    }

    public function update(Request $request, Character $character): RedirectResponse
    {
        abort_unless($character->user_id === auth()->id(), 403);

        $validated = $this->validateCharacterData($request);
        $validated = $this->prepareCharacterData($validated);

        $character->update($validated);

        return redirect()->route('characters.show', $character)->with('status', 'Ficha atualizada com sucesso.');
    }

    public function destroy(Character $character): RedirectResponse
    {
        abort_unless($character->user_id === auth()->id(), 403);

        $disk = Storage::disk(config('filesystems.default'));
        $character->images->each(fn ($image) => $disk->delete($image->path));

        $character->delete();

        return redirect()->route('characters.index')->with('status', 'Ficha excluída.');
    }

    private function validateCharacterData(Request $request): array
    {
        $skillKeys = array_keys(Dnd5eCharacterService::SKILLS);
        $abilityKeys = array_keys(Dnd5eCharacterService::ABILITIES);

        return $request->validate([
            // Identificação
            'name' => ['required', 'string', 'max:120'],
            'player_name' => ['nullable', 'string', 'max:120'],
            'master_name' => ['nullable', 'string', 'max:120'],
            'campaign_name' => ['nullable', 'string', 'max:120'],
            'race' => ['required', 'string', 'max:80'],
            'class' => ['required', 'string', 'max:80'],
            'secondary_class' => ['nullable', 'string', 'max:80'],
            'secondary_class_level' => ['nullable', 'integer', 'min:1', 'max:20'],
            'level' => ['required', 'integer', 'min:1', 'max:20'],
            'background' => ['required', 'string', 'max:120'],
            'alignment' => ['required', 'string', 'max:80'],
            'deity' => ['nullable', 'string', 'max:120'],
            'size' => ['nullable', 'string', 'max:40'],
            'experience' => ['nullable', 'integer', 'min:0', 'max:355000'],

            // Aparência
            'age' => ['nullable', 'string', 'max:40'],
            'height' => ['nullable', 'string', 'max:40'],
            'weight' => ['nullable', 'string', 'max:40'],
            'eyes' => ['nullable', 'string', 'max:40'],
            'skin' => ['nullable', 'string', 'max:40'],
            'hair' => ['nullable', 'string', 'max:40'],
            'portrait_url' => ['nullable', 'url', 'max:2048'],

            // Atributos
            'strength' => ['required', 'integer', 'min:1', 'max:30'],
            'dexterity' => ['required', 'integer', 'min:1', 'max:30'],
            'constitution' => ['required', 'integer', 'min:1', 'max:30'],
            'intelligence' => ['required', 'integer', 'min:1', 'max:30'],
            'wisdom' => ['required', 'integer', 'min:1', 'max:30'],
            'charisma' => ['required', 'integer', 'min:1', 'max:30'],

            // Testes de resistência e perícias
            'saving_throw_proficiencies' => ['nullable', 'array'],
            'saving_throw_proficiencies.*' => ['string', 'in:'.implode(',', $abilityKeys)],
            'skill_proficiencies' => ['nullable', 'array'],
            'skill_proficiencies.*' => ['string', 'in:'.implode(',', $skillKeys)],
            'skill_expertise' => ['nullable', 'array'],
            'skill_expertise.*' => ['string', 'in:'.implode(',', $skillKeys)],

            // Combate
            'armor_class' => ['nullable', 'integer', 'min:1', 'max:50'],
            'speed' => ['nullable', 'integer', 'min:1', 'max:120'],
            'current_hp' => ['nullable', 'integer', 'min:0', 'max:500'],
            'max_hp' => ['nullable', 'integer', 'min:1', 'max:500'],
            'temp_hp' => ['nullable', 'integer', 'min:0', 'max:200'],
            'hit_dice' => ['nullable', 'integer', 'min:1', 'max:20'],
            'death_save_successes' => ['nullable', 'integer', 'min:0', 'max:3'],
            'death_save_failures' => ['nullable', 'integer', 'min:0', 'max:3'],
            'inspiration' => ['nullable', 'boolean'],
            'attacks' => ['nullable', 'string'],

            // Proficiências, idiomas e equipamento
            'armor_proficiencies' => ['nullable', 'string'],
            'weapon_proficiencies' => ['nullable', 'string'],
            'tool_proficiencies' => ['nullable', 'string'],
            'languages' => ['nullable', 'string'],
            'equipment' => ['nullable', 'string'],
            'coins_cp' => ['nullable', 'integer', 'min:0', 'max:1000000'],
            'coins_sp' => ['nullable', 'integer', 'min:0', 'max:1000000'],
            'coins_ep' => ['nullable', 'integer', 'min:0', 'max:1000000'],
            'coins_gp' => ['nullable', 'integer', 'min:0', 'max:1000000'],
            'coins_pp' => ['nullable', 'integer', 'min:0', 'max:1000000'],
            'treasure' => ['nullable', 'string'],

            // Conjuração
            'spellcasting_ability' => ['nullable', 'string', 'in:'.implode(',', array_keys(Dnd5eCharacterService::SPELLCASTING_ABILITIES))],
            'cantrips' => ['nullable', 'string'],
            'spells' => ['nullable', 'string'],

            // Personalidade e história
            'personality_traits' => ['nullable', 'string'],
            'ideals' => ['nullable', 'string'],
            'bonds' => ['nullable', 'string'],
            'flaws' => ['nullable', 'string'],
            'features' => ['nullable', 'string'],
            'proficiencies' => ['nullable', 'string'],
            'allies_organizations' => ['nullable', 'string'],
            'backstory' => ['nullable', 'string'],
            'notes' => ['nullable', 'string'],
        ]);
    }

    private function prepareCharacterData(array $validated): array
    {
        $level = (int) $validated['level'];
        $validated['proficiency_bonus'] = $this->rules->proficiencyBonus($level);

        $validated['saving_throw_proficiencies'] = array_values(array_unique($validated['saving_throw_proficiencies'] ?? []));
        $validated['skill_proficiencies'] = array_values(array_unique($validated['skill_proficiencies'] ?? []));
        // Expertise only makes sense on skills the character is already proficient in.
        $validated['skill_expertise'] = array_values(array_intersect($validated['skill_expertise'] ?? [], $validated['skill_proficiencies']));

        $validated['initiative'] = $this->rules->modifier((int) $validated['dexterity']);

        $perceptionBonus = $this->rules->skillBonus(
            (int) $validated['wisdom'],
            $validated['proficiency_bonus'],
            in_array('perception', $validated['skill_proficiencies'], true),
            in_array('perception', $validated['skill_expertise'], true)
        );
        $validated['passive_perception'] = $this->rules->passivePerception($perceptionBonus);

        $spellcastingAbility = $validated['spellcasting_ability'] ?? $this->rules->defaultSpellcastingAbility($validated['class'] ?? null);
        $validated['spellcasting_ability'] = $spellcastingAbility;
        $spellcastingScore = (int) ($validated[$spellcastingAbility] ?? 10);
        $validated['spell_save_dc'] = $this->rules->spellSaveDc($spellcastingScore, $validated['proficiency_bonus']);
        $validated['spell_attack_bonus'] = $this->rules->spellAttackBonus($spellcastingScore, $validated['proficiency_bonus']);

        $validated['experience'] = $validated['experience'] ?? 0;
        $validated['alignment'] = $validated['alignment'] ?? 'Neutro';
        $validated['size'] = $validated['size'] ?? 'Médio';
        $validated['hit_dice'] = $validated['hit_dice'] ?? 8;
        $validated['armor_class'] = $validated['armor_class'] ?? 10;
        $validated['speed'] = $validated['speed'] ?? 30;
        $validated['max_hp'] = $validated['max_hp'] ?? 8;
        $validated['current_hp'] = $validated['current_hp'] ?? $validated['max_hp'];
        $validated['temp_hp'] = $validated['temp_hp'] ?? 0;
        $validated['death_save_successes'] = $validated['death_save_successes'] ?? 0;
        $validated['death_save_failures'] = $validated['death_save_failures'] ?? 0;
        $validated['inspiration'] = (bool) ($validated['inspiration'] ?? false);
        $validated['coins_cp'] = $validated['coins_cp'] ?? 0;
        $validated['coins_sp'] = $validated['coins_sp'] ?? 0;
        $validated['coins_ep'] = $validated['coins_ep'] ?? 0;
        $validated['coins_gp'] = $validated['coins_gp'] ?? 0;
        $validated['coins_pp'] = $validated['coins_pp'] ?? 0;

        $validated['personality_traits'] = $this->normalizeList($validated['personality_traits'] ?? null);
        $validated['ideals'] = $this->normalizeList($validated['ideals'] ?? null);
        $validated['bonds'] = $this->normalizeList($validated['bonds'] ?? null);
        $validated['flaws'] = $this->normalizeList($validated['flaws'] ?? null);
        $validated['features'] = $this->normalizeList($validated['features'] ?? null);
        $validated['proficiencies'] = $this->normalizeList($validated['proficiencies'] ?? null);
        $validated['armor_proficiencies'] = $this->normalizeList($validated['armor_proficiencies'] ?? null);
        $validated['weapon_proficiencies'] = $this->normalizeList($validated['weapon_proficiencies'] ?? null);
        $validated['tool_proficiencies'] = $this->normalizeList($validated['tool_proficiencies'] ?? null);
        $validated['languages'] = $this->normalizeList($validated['languages'] ?? null);
        $validated['equipment'] = $this->normalizeList($validated['equipment'] ?? null);
        $validated['spells'] = $this->normalizeList($validated['spells'] ?? null);
        $validated['cantrips'] = $this->normalizeList($validated['cantrips'] ?? null);
        $validated['attacks'] = $this->normalizeAttacks($validated['attacks'] ?? null);

        if (empty($validated['secondary_class'])) {
            $validated['secondary_class'] = null;
            $validated['secondary_class_level'] = null;
        }

        return $validated;
    }

    private function normalizeList(mixed $value): array
    {
        if (is_array($value)) {
            return array_values(array_filter(array_map(static fn ($item) => trim((string) $item), $value), static fn ($item) => $item !== ''));
        }

        if (! is_string($value)) {
            return [];
        }

        $parts = preg_split('/[\r\n,]+/', $value) ?: [];

        return array_values(array_filter(array_map(static fn ($item) => trim((string) $item), $parts), static fn ($item) => $item !== ''));
    }

    /**
     * Parses attack lines in the "Nome | Bônus | Dano/Tipo" format into structured entries.
     *
     * @return list<array{name: string, bonus: string, damage: string}>
     */
    private function normalizeAttacks(mixed $value): array
    {
        if (! is_string($value) || trim($value) === '') {
            return [];
        }

        $lines = preg_split('/[\r\n]+/', $value) ?: [];
        $attacks = [];

        foreach ($lines as $line) {
            if (trim($line) === '') {
                continue;
            }

            $parts = array_map('trim', explode('|', $line));

            $attacks[] = [
                'name' => $parts[0] ?? '',
                'bonus' => $parts[1] ?? '',
                'damage' => $parts[2] ?? '',
            ];
        }

        return $attacks;
    }
}
