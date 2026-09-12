<x-app-layout>
    <x-slot name="header">
        <h2 class="text-2xl font-black tracking-tight text-white">Dashboard</h2>
    </x-slot>

    <div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
        <div class="grid gap-6 md:grid-cols-2 xl:grid-cols-4">
            @foreach ($stats as $stat)
                <div class="rpg-card p-5">
                    <p class="text-sm uppercase tracking-[0.2em] text-violet-300">{{ $stat['label'] }}</p>
                    <p class="mt-4 text-4xl font-black text-white">{{ $stat['value'] }}</p>
                    <p class="mt-2 text-sm text-slate-400">{{ $stat['description'] }}</p>
                </div>
            @endforeach
        </div>

        <div class="mt-8 grid gap-8 lg:grid-cols-[1.3fr_0.7fr]">
            <div class="space-y-8">
                <div class="rpg-card p-6">
                    <div class="mb-6 flex items-center justify-between">
                        <h3 class="text-xl font-bold text-white">Minhas campanhas</h3>
                        <a href="{{ route('campaigns.create') }}" class="golden-button text-sm">Nova campanha</a>
                    </div>

                    @if ($campaigns->isEmpty())
                        <div class="rounded-2xl border border-dashed border-slate-700 bg-slate-950/30 p-8 text-center text-slate-300">
                            Ainda não há campanhas criadas. Monte a primeira aventura para o grupo.
                        </div>
                    @else
                        <div class="space-y-4">
                            @foreach ($campaigns as $campaign)
                                <div class="flex items-center justify-between rounded-2xl border border-slate-800 bg-slate-950/60 p-4">
                                    <div>
                                        <p class="text-lg font-bold text-white">{{ $campaign->name }}</p>
                                        <p class="text-sm text-slate-400">{{ $campaign->status }} · {{ $campaign->setting ?? 'Ambientação em criação' }}</p>
                                    </div>
                                    <a href="{{ route('campaigns.show', $campaign) }}" class="secondary-button text-sm px-4 py-2">Abrir</a>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>

                <div class="rpg-card p-6">
                    <div class="mb-6 flex items-center justify-between">
                        <h3 class="text-xl font-bold text-white">Fichas recentes</h3>
                        <a href="{{ route('characters.create') }}" class="golden-button text-sm">Nova ficha</a>
                    </div>

                    @if ($recentCharacters->isEmpty())
                        <div class="rounded-2xl border border-dashed border-slate-700 bg-slate-950/30 p-8 text-center text-slate-300">
                            Ainda não há personagens salvos. Crie sua primeira ficha e comece sua aventura.
                        </div>
                    @else
                        <div class="space-y-4">
                            @foreach ($recentCharacters as $character)
                                <div class="flex items-center justify-between rounded-2xl border border-slate-800 bg-slate-950/60 p-4">
                                    <div>
                                        <p class="text-lg font-bold text-white">{{ $character->name }}</p>
                                        <p class="text-sm text-slate-400">{{ $character->race }} · {{ $character->class }} · Nível {{ $character->level }}</p>
                                    </div>
                                    <a href="{{ route('characters.show', $character) }}" class="secondary-button text-sm px-4 py-2">Abrir</a>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>

            <div class="rpg-card p-6">
                <h3 class="text-xl font-bold text-white">Fluxo de campanha</h3>
                <ul class="mt-5 space-y-4 text-sm text-slate-300">
                    <li class="rounded-xl border border-slate-800 bg-slate-950/60 p-4">
                        <span class="font-semibold text-violet-200">01</span> · Esboço do personagem
                    </li>
                    <li class="rounded-xl border border-slate-800 bg-slate-950/60 p-4">
                        <span class="font-semibold text-violet-200">02</span> · Seleção de magias e perícias
                    </li>
                    <li class="rounded-xl border border-slate-800 bg-slate-950/60 p-4">
                        <span class="font-semibold text-violet-200">03</span> · Campanha e combate de mesa
                    </li>
                </ul>
            </div>
        </div>
    </div>
</x-app-layout>
