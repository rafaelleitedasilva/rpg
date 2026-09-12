<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between gap-4">
            <h2 class="text-2xl font-black tracking-tight text-white">Campanhas do grupo</h2>
            <a href="{{ route('campaigns.create') }}" class="golden-button">Nova campanha</a>
        </div>
    </x-slot>

    <div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
        @if ($campaigns->isEmpty())
            <div class="rpg-card p-8 text-center">
                <h3 class="text-xl font-bold text-white">Nenhuma campanha criada ainda</h3>
                <p class="mt-2 text-slate-300">Crie sua primeira aventura e convide o grupo para a mesa.</p>
            </div>
        @else
            <div class="grid gap-6 md:grid-cols-2 xl:grid-cols-3">
                @foreach ($campaigns as $campaign)
                    <article class="rpg-card p-6">
                        <p class="text-xs uppercase tracking-[0.2em] text-violet-300">{{ $campaign->status }}</p>
                        <h3 class="mt-3 text-2xl font-black text-white">{{ $campaign->name }}</h3>
                        <div class="mt-5 space-y-2 text-sm text-slate-300">
                            <p><span class="text-white">Mestre:</span> {{ $campaign->master->name }}</p>
                            <p><span class="text-white">Setting:</span> {{ $campaign->setting ?? 'Não definido' }}</p>
                            <p><span class="text-white">Jogadores:</span> {{ $campaign->max_players }}</p>
                        </div>
                        <a href="{{ route('campaigns.show', $campaign) }}" class="golden-button mt-6 inline-flex w-full justify-center">Abrir campanha</a>
                    </article>
                @endforeach
            </div>
        @endif
    </div>
</x-app-layout>
