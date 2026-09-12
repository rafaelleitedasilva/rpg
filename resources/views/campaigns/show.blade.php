<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between gap-4">
            <div>
                <p class="text-[0.66rem] uppercase tracking-[0.28em] text-[#d8b97d]">Mesa do mestre</p>
                <h2 class="tavern-display mt-2 text-4xl text-[#f7efe3]">{{ $campaign->name }}</h2>
            </div>
            <a href="{{ route('campaigns.index') }}" class="secondary-button">Voltar</a>
        </div>
    </x-slot>

    <div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
        <div class="grid gap-8 lg:grid-cols-[1.3fr_0.7fr]">
            <div class="space-y-8">
                <div class="rpg-card p-6 sm:p-8">
                    <div class="flex items-center justify-between gap-3">
                        <span class="rpg-badge">{{ $campaign->status }}</span>
                        <span class="rpg-badge subtle">{{ $campaign->max_players }} jogadores</span>
                    </div>

                    <div class="mt-6 grid gap-4 sm:grid-cols-2 text-sm text-slate-300">
                        <div class="rounded-2xl border border-[#a67a47]/30 bg-[#1b1512]/60 p-4">
                            <p class="text-[0.62rem] uppercase tracking-[0.22em] text-[#d8b97d]">Mestre</p>
                            <p class="mt-2 text-lg font-semibold text-white">{{ $campaign->master->name }}</p>
                        </div>
                        <div class="rounded-2xl border border-[#a67a47]/30 bg-[#1b1512]/60 p-4">
                            <p class="text-[0.62rem] uppercase tracking-[0.22em] text-[#d8b97d]">Ambientação</p>
                            <p class="mt-2 text-lg font-semibold text-white">{{ $campaign->setting ?? 'Ainda não definida' }}</p>
                        </div>
                    </div>

                    <div class="mt-8 rounded-2xl border border-[#a67a47]/30 bg-[#1b1512]/70 p-4">
                        <h3 class="text-lg font-bold text-white">Resumo da aventura</h3>
                        <p class="mt-3 text-slate-300">
                            {{ $campaign->description ?? 'A descrição da campanha ainda será preenchida pelo mestre.' }}
                        </p>
                    </div>
                </div>

                <div class="rpg-card p-6">
                    <div class="mb-6 flex items-center justify-between">
                        <h3 class="text-xl font-bold text-white">Mapas de combate</h3>
                        <span class="text-sm text-slate-300">{{ $campaign->maps->count() }} mapas</span>
                    </div>

                    @if ($campaign->maps->isEmpty())
                        <div class="rounded-2xl border border-dashed border-slate-700 bg-slate-950/30 p-8 text-center text-slate-300">
                            Nenhum mapa criado ainda. Adicione o primeiro grid de combate.
                        </div>
                    @else
                        <div class="space-y-5">
                            @foreach ($campaign->maps as $map)
                                <div class="rounded-2xl border border-[#a67a47]/30 bg-[#1b1512]/60 p-4">
                                    <div class="flex items-center justify-between gap-3">
                                        <div>
                                            <p class="text-lg font-bold text-white">{{ $map->name }}</p>
                                            <p class="text-sm text-slate-400">{{ $map->terrain ?? 'Terreno indefinido' }} · {{ $map->width }}x{{ $map->height }} · {{ $map->grid_size }}ft</p>
                                        </div>
                                        <span class="rpg-badge subtle">Ativo</span>
                                    </div>
                                    <div class="mt-4 grid gap-1 rounded-xl border border-slate-700 bg-slate-900/60 p-2" style="grid-template-columns: repeat({{ $map->width }}, minmax(0, 1fr));">
                                        @for ($row = 0; $row < $map->height; $row++)
                                            @for ($col = 0; $col < $map->width; $col++)
                                                <div class="h-4 rounded-sm border border-slate-700 bg-slate-800/80"></div>
                                            @endfor
                                        @endfor
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif

                    <form method="POST" action="{{ route('campaigns.maps.store', $campaign) }}" class="mt-8 space-y-4 rounded-2xl border border-slate-800 bg-slate-950/40 p-4">
                        @csrf
                        <h4 class="text-lg font-bold text-white">Criar mapa</h4>
                        <div class="grid gap-4 md:grid-cols-2">
                            <div>
                                <label for="map_name" class="block text-sm font-medium text-slate-200">Nome</label>
                                <input id="map_name" name="name" type="text" required class="mt-2 w-full rounded-xl border border-slate-600 bg-slate-900/70 px-3 py-2 text-white focus:border-violet-400 focus:outline-none" placeholder="Floresta Sombria">
                            </div>
                            <div>
                                <label for="map_terrain" class="block text-sm font-medium text-slate-200">Terreno</label>
                                <input id="map_terrain" name="terrain" type="text" class="mt-2 w-full rounded-xl border border-slate-600 bg-slate-900/70 px-3 py-2 text-white focus:border-violet-400 focus:outline-none" placeholder="Floresta">
                            </div>
                            <div>
                                <label for="map_width" class="block text-sm font-medium text-slate-200">Largura</label>
                                <input id="map_width" name="width" type="number" min="1" max="50" value="10" required class="mt-2 w-full rounded-xl border border-slate-600 bg-slate-900/70 px-3 py-2 text-white focus:border-violet-400 focus:outline-none">
                            </div>
                            <div>
                                <label for="map_height" class="block text-sm font-medium text-slate-200">Altura</label>
                                <input id="map_height" name="height" type="number" min="1" max="50" value="8" required class="mt-2 w-full rounded-xl border border-slate-600 bg-slate-900/70 px-3 py-2 text-white focus:border-violet-400 focus:outline-none">
                            </div>
                            <div class="md:col-span-2">
                                <label for="map_grid_size" class="block text-sm font-medium text-slate-200">Tamanho da grade</label>
                                <input id="map_grid_size" name="grid_size" type="number" min="1" max="10" value="5" required class="mt-2 w-full rounded-xl border border-slate-600 bg-slate-900/70 px-3 py-2 text-white focus:border-violet-400 focus:outline-none">
                            </div>
                        </div>
                        <button type="submit" class="golden-button">Salvar mapa</button>
                    </form>
                </div>

                <div class="rpg-card p-6">
                    <div class="mb-6 flex items-center justify-between">
                        <h3 class="text-xl font-bold text-white">Cenas de combate</h3>
                        <span class="text-sm text-slate-300">{{ $campaign->combatScenes->count() }} cenas</span>
                    </div>

                    @if ($campaign->combatScenes->isEmpty())
                        <div class="rounded-2xl border border-dashed border-slate-700 bg-slate-950/30 p-8 text-center text-slate-300">
                            Nenhuma cena criada ainda. Inicie a primeira rodada de combate.
                        </div>
                    @else
                        <div class="space-y-4">
                            @foreach ($campaign->combatScenes as $scene)
                                <div class="rounded-2xl border border-[#a67a47]/30 bg-[#1b1512]/60 p-4">
                                    <div class="flex items-center justify-between gap-3">
                                        <div>
                                            <p class="text-lg font-bold text-white">{{ $scene->name }}</p>
                                            <p class="text-sm text-slate-400">Rodada {{ $scene->round }} · Turno {{ $scene->turn }}</p>
                                        </div>
                                        <span class="rpg-badge subtle">{{ $scene->status }}</span>
                                    </div>
                                    <p class="mt-3 text-sm text-slate-300">{{ $scene->initiative_order }}</p>
                                    <form method="POST" action="{{ route('campaigns.combat-scenes.advance', [$campaign, $scene]) }}" class="mt-4">
                                        @csrf
                                        <button type="submit" class="golden-button w-full justify-center text-sm">Próximo turno</button>
                                    </form>
                                </div>
                            @endforeach
                        </div>
                    @endif

                    <form method="POST" action="{{ route('campaigns.combat-scenes.store', $campaign) }}" class="mt-8 space-y-4 rounded-2xl border border-slate-800 bg-slate-950/40 p-4">
                        @csrf
                        <h4 class="text-lg font-bold text-white">Iniciar combate</h4>
                        <div class="space-y-4">
                            <div>
                                <label for="scene_name" class="block text-sm font-medium text-slate-200">Nome da cena</label>
                                <input id="scene_name" name="name" type="text" required class="mt-2 w-full rounded-xl border border-slate-600 bg-slate-900/70 px-3 py-2 text-white focus:border-violet-400 focus:outline-none" placeholder="Batalha na clareira">
                            </div>
                            <div>
                                <label for="scene_initiative" class="block text-sm font-medium text-slate-200">Ordem de iniciativa</label>
                                <input id="scene_initiative" name="initiative_order" type="text" required class="mt-2 w-full rounded-xl border border-slate-600 bg-slate-900/70 px-3 py-2 text-white focus:border-violet-400 focus:outline-none" placeholder="Goblin, Herói, Orc">
                            </div>
                            <div class="grid gap-4 md:grid-cols-2">
                                <div>
                                    <label for="scene_round" class="block text-sm font-medium text-slate-200">Rodada</label>
                                    <input id="scene_round" name="round" type="number" min="1" value="1" required class="mt-2 w-full rounded-xl border border-slate-600 bg-slate-900/70 px-3 py-2 text-white focus:border-violet-400 focus:outline-none">
                                </div>
                                <div>
                                    <label for="scene_turn" class="block text-sm font-medium text-slate-200">Turno</label>
                                    <input id="scene_turn" name="turn" type="number" min="1" value="1" required class="mt-2 w-full rounded-xl border border-slate-600 bg-slate-900/70 px-3 py-2 text-white focus:border-violet-400 focus:outline-none">
                                </div>
                            </div>
                        </div>
                        <button type="submit" class="golden-button">Salvar cena</button>
                    </form>
                </div>
            </div>

            <aside class="space-y-6">
                <div class="rpg-card p-6">
                    <h3 class="text-xl font-bold text-white">Mesa do mestre</h3>
                    <ul class="mt-5 space-y-3 text-sm text-slate-300">
                        <li class="rounded-xl border border-slate-800 bg-slate-950/60 p-3">Preparar o próximo encontro</li>
                        <li class="rounded-xl border border-slate-800 bg-slate-950/60 p-3">Registrar NPCs e ameaças</li>
                        <li class="rounded-xl border border-slate-800 bg-slate-950/60 p-3">Planejar missões e recompensas</li>
                    </ul>
                </div>

                <div class="rpg-card p-6">
                    <h3 class="text-xl font-bold text-white">Monstros do catálogo</h3>

                    @if ($campaign->monsterTemplates->isEmpty())
                        <p class="mt-3 text-sm text-slate-300">Nenhum monstro registrado ainda.</p>
                    @else
                        <ul class="mt-4 space-y-2 text-sm text-slate-300">
                            @foreach ($campaign->monsterTemplates as $monster)
                                <li class="rounded-xl border border-slate-800 bg-slate-950/60 p-3">
                                    <div class="flex items-center justify-between gap-3">
                                        <span class="font-semibold text-white">{{ $monster->name }}</span>
                                        <span class="text-xs uppercase tracking-[0.2em] text-violet-200">CA {{ $monster->armor_class }}</span>
                                    </div>
                                    <p class="mt-1 text-xs text-slate-400">HP {{ $monster->max_hp }} · Iniciativa {{ $monster->initiative }}</p>
                                </li>
                            @endforeach
                        </ul>
                    @endif

                    <form method="POST" action="{{ route('campaigns.monster-templates.store', $campaign) }}" class="mt-5 space-y-4">
                        @csrf
                        <div>
                            <label for="monster_name" class="block text-sm font-medium text-slate-200">Nome</label>
                            <input id="monster_name" name="name" type="text" required class="mt-2 w-full rounded-xl border border-slate-600 bg-slate-900/70 px-3 py-2 text-white focus:border-violet-400 focus:outline-none" placeholder="Goblin">
                        </div>
                        <div class="grid gap-4 sm:grid-cols-2">
                            <div>
                                <label for="monster_ca" class="block text-sm font-medium text-slate-200">CA</label>
                                <input id="monster_ca" name="armor_class" type="number" min="1" max="50" value="13" class="mt-2 w-full rounded-xl border border-slate-600 bg-slate-900/70 px-3 py-2 text-white focus:border-violet-400 focus:outline-none">
                            </div>
                            <div>
                                <label for="monster_hp" class="block text-sm font-medium text-slate-200">HP</label>
                                <input id="monster_hp" name="max_hp" type="number" min="1" value="7" class="mt-2 w-full rounded-xl border border-slate-600 bg-slate-900/70 px-3 py-2 text-white focus:border-violet-400 focus:outline-none">
                            </div>
                            <div>
                                <label for="monster_init" class="block text-sm font-medium text-slate-200">Iniciativa</label>
                                <input id="monster_init" name="initiative" type="number" min="-20" max="30" value="12" class="mt-2 w-full rounded-xl border border-slate-600 bg-slate-900/70 px-3 py-2 text-white focus:border-violet-400 focus:outline-none">
                            </div>
                            <div>
                                <label for="monster_speed" class="block text-sm font-medium text-slate-200">Deslocamento</label>
                                <input id="monster_speed" name="speed" type="number" min="0" max="120" value="30" class="mt-2 w-full rounded-xl border border-slate-600 bg-slate-900/70 px-3 py-2 text-white focus:border-violet-400 focus:outline-none">
                            </div>
                        </div>
                        <div>
                            <label for="monster_cr" class="block text-sm font-medium text-slate-200">Desafio</label>
                            <input id="monster_cr" name="challenge_rating" type="number" min="0" step="0.25" max="30" value="0.25" class="mt-2 w-full rounded-xl border border-slate-600 bg-slate-900/70 px-3 py-2 text-white focus:border-violet-400 focus:outline-none">
                        </div>
                        <div>
                            <label for="monster_desc" class="block text-sm font-medium text-slate-200">Descrição</label>
                            <textarea id="monster_desc" name="description" rows="3" class="mt-2 w-full rounded-xl border border-slate-600 bg-slate-900/70 px-3 py-2 text-white focus:border-violet-400 focus:outline-none" placeholder="Cria astuta e agressiva."></textarea>
                        </div>
                        <button type="submit" class="golden-button w-full justify-center">Salvar monstro</button>
                    </form>
                </div>

                @if ($campaign->combatScenes->isNotEmpty())
                    @php $sceneForTokens = $campaign->combatScenes->first(); @endphp
                    <div class="rpg-card p-6">
                        <h3 class="text-xl font-bold text-white">Tokens da cena</h3>

                        @if ($sceneForTokens->tokens->isNotEmpty())
                            <div class="mt-4 space-y-3">
                                @foreach ($sceneForTokens->tokens as $token)
                                    <div class="rounded-xl border border-slate-800 bg-slate-950/60 p-3">
                                        <div class="flex items-center justify-between gap-3">
                                            <div>
                                                <p class="font-semibold text-white">{{ $token->name }}</p>
                                                <p class="text-xs text-slate-400">{{ $token->x }}, {{ $token->y }} · HP {{ $token->hp }}/{{ $token->max_hp ?: $token->hp }}</p>
                                            </div>
                                            <span class="rounded-full border border-violet-500/40 bg-violet-500/10 px-2 py-1 text-[10px] uppercase tracking-[0.2em] text-violet-200">{{ $token->type }}</span>
                                        </div>

                                        <form method="POST" action="{{ route('campaigns.combat-scenes.tokens.move', [$campaign, $sceneForTokens, $token]) }}" class="mt-3">
                                            @csrf
                                            <div class="grid grid-cols-2 gap-3">
                                                <input type="number" name="x" value="{{ $token->x }}" min="0" class="rounded-lg border border-slate-600 bg-slate-900/70 px-2 py-2 text-sm text-white focus:border-violet-400 focus:outline-none">
                                                <input type="number" name="y" value="{{ $token->y }}" min="0" class="rounded-lg border border-slate-600 bg-slate-900/70 px-2 py-2 text-sm text-white focus:border-violet-400 focus:outline-none">
                                            </div>
                                            <button type="submit" class="secondary-button mt-3 w-full justify-center px-3 py-2 text-xs">Mover</button>
                                        </form>

                                        <div class="mt-3 grid grid-cols-2 gap-2">
                                            <form method="POST" action="{{ route('campaigns.combat-scenes.tokens.damage', [$campaign, $sceneForTokens, $token]) }}">
                                                @csrf
                                                <div class="space-y-2">
                                                    <input type="number" name="amount" value="1" min="0" max="200" class="w-full rounded-lg border border-slate-600 bg-slate-900/70 px-2 py-2 text-sm text-white focus:border-violet-400 focus:outline-none">
                                                    <button type="submit" class="secondary-button w-full justify-center px-3 py-2 text-xs">Dano</button>
                                                </div>
                                            </form>
                                            <form method="POST" action="{{ route('campaigns.combat-scenes.tokens.heal', [$campaign, $sceneForTokens, $token]) }}">
                                                @csrf
                                                <div class="space-y-2">
                                                    <input type="number" name="amount" value="1" min="0" max="200" class="w-full rounded-lg border border-slate-600 bg-slate-900/70 px-2 py-2 text-sm text-white focus:border-violet-400 focus:outline-none">
                                                    <button type="submit" class="golden-button w-full justify-center px-3 py-2 text-xs">Cura</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif

                        <form method="POST" action="{{ route('campaigns.combat-scenes.tokens.store', [$campaign, $sceneForTokens]) }}" class="mt-5 space-y-4">
                            @csrf
                            <div>
                                <label for="token_monster" class="block text-sm font-medium text-slate-200">Modelo</label>
                                <select id="token_monster" name="monster_template_id" class="mt-2 w-full rounded-xl border border-slate-600 bg-slate-900/70 px-3 py-2 text-white focus:border-violet-400 focus:outline-none">
                                    <option value="">Sem modelo</option>
                                    @foreach ($campaign->monsterTemplates as $monster)
                                        <option value="{{ $monster->id }}">{{ $monster->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label for="token_name" class="block text-sm font-medium text-slate-200">Nome do token</label>
                                <input id="token_name" name="name" type="text" required class="mt-2 w-full rounded-xl border border-slate-600 bg-slate-900/70 px-3 py-2 text-white focus:border-violet-400 focus:outline-none" placeholder="Goblin #1">
                            </div>
                            <div class="grid gap-4 sm:grid-cols-2">
                                <div>
                                    <label for="token_x" class="block text-sm font-medium text-slate-200">Posição X</label>
                                    <input id="token_x" name="x" type="number" min="0" value="0" class="mt-2 w-full rounded-xl border border-slate-600 bg-slate-900/70 px-3 py-2 text-white focus:border-violet-400 focus:outline-none">
                                </div>
                                <div>
                                    <label for="token_y" class="block text-sm font-medium text-slate-200">Posição Y</label>
                                    <input id="token_y" name="y" type="number" min="0" value="0" class="mt-2 w-full rounded-xl border border-slate-600 bg-slate-900/70 px-3 py-2 text-white focus:border-violet-400 focus:outline-none">
                                </div>
                                <div>
                                    <label for="token_initiative" class="block text-sm font-medium text-slate-200">Iniciativa</label>
                                    <input id="token_initiative" name="initiative" type="number" min="-20" max="30" value="12" class="mt-2 w-full rounded-xl border border-slate-600 bg-slate-900/70 px-3 py-2 text-white focus:border-violet-400 focus:outline-none">
                                </div>
                                <div>
                                    <label for="token_hp" class="block text-sm font-medium text-slate-200">HP</label>
                                    <input id="token_hp" name="hp" type="number" min="0" value="7" class="mt-2 w-full rounded-xl border border-slate-600 bg-slate-900/70 px-3 py-2 text-white focus:border-violet-400 focus:outline-none">
                                </div>
                            </div>
                            <div>
                                <label for="token_color" class="block text-sm font-medium text-slate-200">Cor</label>
                                <input id="token_color" name="color" type="color" value="#ef4444" class="mt-2 h-12 w-full rounded-xl border border-slate-600 bg-slate-900/70 px-2 py-2 text-white focus:border-violet-400 focus:outline-none">
                            </div>
                            <button type="submit" class="golden-button w-full justify-center">Adicionar token</button>
                        </form>
                    </div>
                @endif

                <div class="rpg-card p-6">
                    <h3 class="text-xl font-bold text-white">Convidar amigos</h3>
                    <form method="POST" action="{{ route('campaigns.invites.store', $campaign) }}" class="mt-5 space-y-4">
                        @csrf
                        <div>
                            <label for="invite_user_id" class="block text-sm font-medium text-slate-200">ID do jogador</label>
                            <input id="invite_user_id" name="user_id" type="number" min="1" required class="mt-2 w-full rounded-xl border border-slate-600 bg-slate-900/70 px-3 py-2 text-white focus:border-violet-400 focus:outline-none" placeholder="ID do usuário">
                        </div>
                        <button type="submit" class="golden-button w-full justify-center">Enviar convite</button>
                    </form>
                </div>

                <div class="rpg-card p-6">
                    <h3 class="text-xl font-bold text-white">Ações rápidas</h3>
                    <div class="mt-5 space-y-3">
                        <a href="{{ route('campaigns.index') }}" class="secondary-button w-full justify-center">Ver campanhas</a>
                        <a href="{{ route('dashboard') }}" class="golden-button w-full justify-center">Voltar ao dashboard</a>
                    </div>
                </div>
            </aside>
        </div>
    </div>
</x-app-layout>
