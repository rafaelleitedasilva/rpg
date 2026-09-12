<x-guest-layout>
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <div class="mb-6">
        <p class="text-[0.68rem] uppercase tracking-[0.35em] text-[#d6ae6a]">Bem-vindo</p>
        <h2 class="tavern-display mt-2 text-4xl text-[#f6efe5]">Entrar na mesa</h2>
    </div>

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="mt-2 block w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div class="mt-5">
            <x-input-label for="password" :value="__('Senha')" />
            <x-text-input id="password" class="mt-2 block w-full"
                type="password"
                name="password"
                required autocomplete="current-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div class="mt-5 flex items-center justify-between gap-4">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox" class="h-4 w-4 rounded border-[#8b6b47] bg-[#1a1512] text-[#d5a75d] shadow-sm focus:ring-[#d5a75d]" name="remember">
                <span class="ms-2 text-sm text-[#e5d6b7]">Lembrar-me</span>
            </label>

            @if (Route::has('password.request'))
                <a class="text-sm text-[#d8b97d] transition hover:text-[#f5d9a1]" href="{{ route('password.request') }}">
                    Esqueci minha senha
                </a>
            @endif
        </div>

        <div class="mt-8 flex items-center justify-end gap-3">
            <x-primary-button>
                {{ __('Entrar') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
