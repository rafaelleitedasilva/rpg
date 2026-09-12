<x-app-layout>
    <x-slot name="header">
        <h2 class="tavern-display text-2xl text-[#f7efe3]">Nova ficha D&D 5e</h2>
    </x-slot>

    <div class="mx-auto max-w-6xl px-4 py-8 sm:px-6 lg:px-8">
        <form action="{{ route('characters.store') }}" method="POST" class="rpg-card p-6 lg:p-8">
            @csrf

            @include('characters.partials.form', ['character' => null])
        </form>
    </div>
</x-app-layout>
