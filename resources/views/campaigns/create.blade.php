<x-app-layout>
    <x-slot name="header">
        <h2 class="text-2xl font-black tracking-tight text-white">Nova campanha</h2>
    </x-slot>

    <div class="mx-auto max-w-3xl px-4 py-8 sm:px-6 lg:px-8">
        <div class="rpg-card p-6 sm:p-8">
            <form method="POST" action="{{ route('campaigns.store') }}" class="space-y-6">
                @csrf

                <div>
                    <label for="name" class="block text-sm font-medium text-slate-200">Nome da campanha</label>
                    <input id="name" name="name" type="text" value="{{ old('name') }}" required class="mt-2 w-full rounded-xl border border-slate-600 bg-slate-900/70 px-3 py-2 text-white placeholder-slate-400 focus:border-violet-400 focus:outline-none" placeholder="A Sombra do Círculo">
                    @error('name')
                        <p class="mt-2 text-sm text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="setting" class="block text-sm font-medium text-slate-200">Ambientação</label>
                    <input id="setting" name="setting" type="text" value="{{ old('setting') }}" class="mt-2 w-full rounded-xl border border-slate-600 bg-slate-900/70 px-3 py-2 text-white placeholder-slate-400 focus:border-violet-400 focus:outline-none" placeholder="Faerûn">
                    @error('setting')
                        <p class="mt-2 text-sm text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid gap-6 md:grid-cols-2">
                    <div>
                        <label for="status" class="block text-sm font-medium text-slate-200">Status</label>
                        <input id="status" name="status" type="text" value="{{ old('status', 'Em preparação') }}" required class="mt-2 w-full rounded-xl border border-slate-600 bg-slate-900/70 px-3 py-2 text-white placeholder-slate-400 focus:border-violet-400 focus:outline-none">
                        @error('status')
                            <p class="mt-2 text-sm text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="max_players" class="block text-sm font-medium text-slate-200">Máximo de jogadores</label>
                        <input id="max_players" name="max_players" type="number" min="1" max="20" value="{{ old('max_players', 4) }}" required class="mt-2 w-full rounded-xl border border-slate-600 bg-slate-900/70 px-3 py-2 text-white placeholder-slate-400 focus:border-violet-400 focus:outline-none">
                        @error('max_players')
                            <p class="mt-2 text-sm text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div>
                    <label for="description" class="block text-sm font-medium text-slate-200">Descrição</label>
                    <textarea id="description" name="description" rows="5" class="mt-2 w-full rounded-xl border border-slate-600 bg-slate-900/70 px-3 py-2 text-white placeholder-slate-400 focus:border-violet-400 focus:outline-none" placeholder="Descreva a aventura, a ameaça principal e o tom da campanha.">{{ old('description') }}</textarea>
                    @error('description')
                        <p class="mt-2 text-sm text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex justify-end gap-3">
                    <a href="{{ route('campaigns.index') }}" class="inline-flex items-center justify-center rounded-xl border border-slate-600 px-4 py-2 text-sm font-semibold text-slate-200 hover:border-slate-500 hover:text-white">Cancelar</a>
                    <button type="submit" class="golden-button">Salvar campanha</button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
