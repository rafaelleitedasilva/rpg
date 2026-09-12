<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between gap-4">
            <div>
                <p class="text-[0.66rem] uppercase tracking-[0.28em] text-[#d8b97d]">Editar ficha</p>
                <h2 class="tavern-display mt-2 text-4xl text-[#f7efe3]">{{ $character->name }}</h2>
            </div>
            <a href="{{ route('characters.show', $character) }}" class="secondary-button text-sm">Voltar</a>
        </div>
    </x-slot>

    <div class="mx-auto max-w-6xl px-4 py-8 sm:px-6 lg:px-8">
        <form action="{{ route('characters.update', $character) }}" method="POST" class="rpg-card p-6 lg:p-8 sheet-panel">
            @csrf
            @method('PUT')

            @include('characters.partials.form', ['character' => $character])
        </form>
    </div>
</x-app-layout>
