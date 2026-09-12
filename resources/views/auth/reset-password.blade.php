<x-guest-layout
    heading="Recuperar acesso"
    description="Escolha uma nova senha para acessar sua conta com segurança."
    image="images/auth/reset-password.jpg"
>
    <div class="pub-steps">
        <div class="pub-step">
            <span class="pub-step-index">1</span> Informar e-mail
        </div>
        <span class="pub-step-connector"></span>
        <div class="pub-step is-active">
            <span class="pub-step-index">2</span> Nova senha
        </div>
    </div>

    <h2 class="pub-auth-title">Definir nova senha</h2>

    <form method="POST" action="{{ route('password.store') }}" x-data="{ showPassword: false, showConfirm: false }">
        @csrf

        <input type="hidden" name="token" value="{{ $request->route('token') }}">

        <div class="pub-field" style="margin-top: 1.5rem;">
            <label for="email" class="pub-label">E-mail</label>
            <div class="pub-input-group">
                <span class="pub-input-icon"><x-gh-icon name="mail" /></span>
                <input id="email" type="email" name="email" value="{{ old('email', $request->email) }}" required
                    autofocus autocomplete="username" class="pub-input" placeholder="seu@email.com">
            </div>
            <x-input-error :messages="$errors->get('email')" class="mt-1" />
        </div>

        <div class="pub-field">
            <label for="password" class="pub-label">Nova senha</label>
            <div class="pub-input-group">
                <span class="pub-input-icon"><x-gh-icon name="lock" /></span>
                <input :type="showPassword ? 'text' : 'password'" id="password" name="password" required
                    autocomplete="new-password" class="pub-input" placeholder="Digite sua nova senha"
                    style="padding-right: 2.5rem;">
                <button type="button" class="pub-input-toggle" @click="showPassword = !showPassword"
                    :aria-label="showPassword ? 'Ocultar senha' : 'Mostrar senha'">
                    <x-gh-icon name="eye" x-show="!showPassword" />
                    <x-gh-icon name="eye-off" x-show="showPassword" x-cloak />
                </button>
            </div>
            <x-input-error :messages="$errors->get('password')" class="mt-1" />
        </div>

        <div class="pub-field">
            <label for="password_confirmation" class="pub-label">Confirmar nova senha</label>
            <div class="pub-input-group">
                <span class="pub-input-icon"><x-gh-icon name="lock" /></span>
                <input :type="showConfirm ? 'text' : 'password'" id="password_confirmation"
                    name="password_confirmation" required autocomplete="new-password" class="pub-input"
                    placeholder="Confirme sua nova senha" style="padding-right: 2.5rem;">
                <button type="button" class="pub-input-toggle" @click="showConfirm = !showConfirm"
                    :aria-label="showConfirm ? 'Ocultar senha' : 'Mostrar senha'">
                    <x-gh-icon name="eye" x-show="!showConfirm" />
                    <x-gh-icon name="eye-off" x-show="showConfirm" x-cloak />
                </button>
            </div>
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-1" />
        </div>

        <div class="pub-submit">
            <button type="submit" class="pub-btn pub-btn-gold pub-btn-block">
                Redefinir senha <x-gh-icon name="arrow-right" />
            </button>
        </div>
    </form>
</x-guest-layout>
