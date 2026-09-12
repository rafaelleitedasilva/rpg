<x-guest-layout
    heading="Comece sua aventura"
    description="Crie sua conta para organizar suas fichas e campanhas."
    image="images/auth/register.jpg"
>
    <h2 class="pub-auth-title">Criar conta</h2>

    <form method="POST" action="{{ route('register') }}" x-data="{
        password: '',
        showPassword: false,
        showConfirm: false,
        get hasLength() { return this.password.length >= 8 },
        get hasUpper() { return /[A-Z]/.test(this.password) },
        get hasNumber() { return /[0-9]/.test(this.password) },
    }">
        @csrf

        <div class="pub-field" style="margin-top: 1.75rem;">
            <label for="name" class="pub-label">Nome completo</label>
            <div class="pub-input-group">
                <span class="pub-input-icon"><x-gh-icon name="user" /></span>
                <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus
                    autocomplete="name" class="pub-input" placeholder="Seu nome completo">
            </div>
            <x-input-error :messages="$errors->get('name')" class="mt-1" />
        </div>

        <div class="pub-field">
            <label for="email" class="pub-label">E-mail</label>
            <div class="pub-input-group">
                <span class="pub-input-icon"><x-gh-icon name="mail" /></span>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required
                    autocomplete="username" class="pub-input" placeholder="seu@email.com">
            </div>
            <x-input-error :messages="$errors->get('email')" class="mt-1" />
        </div>

        <div class="pub-field">
            <label for="password" class="pub-label">Senha</label>
            <div class="pub-input-group">
                <span class="pub-input-icon"><x-gh-icon name="lock" /></span>
                <input :type="showPassword ? 'text' : 'password'" id="password" name="password" x-model="password"
                    required autocomplete="new-password" class="pub-input" placeholder="Digite sua senha"
                    style="padding-right: 2.5rem;">
                <button type="button" class="pub-input-toggle" @click="showPassword = !showPassword"
                    :aria-label="showPassword ? 'Ocultar senha' : 'Mostrar senha'">
                    <x-gh-icon name="eye" x-show="!showPassword" />
                    <x-gh-icon name="eye-off" x-show="showPassword" x-cloak />
                </button>
            </div>
            <x-input-error :messages="$errors->get('password')" class="mt-1" />

            <div class="pub-checklist">
                <span class="pub-checklist-item" :class="{ 'is-met': hasLength }">
                    <x-gh-icon name="check" /> Pelo menos 8 caracteres
                </span>
                <span class="pub-checklist-item" :class="{ 'is-met': hasUpper }">
                    <x-gh-icon name="check" /> Uma letra maiúscula
                </span>
                <span class="pub-checklist-item" :class="{ 'is-met': hasNumber }">
                    <x-gh-icon name="check" /> Um número
                </span>
            </div>
        </div>

        <div class="pub-field">
            <label for="password_confirmation" class="pub-label">Confirmar senha</label>
            <div class="pub-input-group">
                <span class="pub-input-icon"><x-gh-icon name="lock" /></span>
                <input :type="showConfirm ? 'text' : 'password'" id="password_confirmation"
                    name="password_confirmation" required autocomplete="new-password" class="pub-input"
                    placeholder="Confirme sua senha" style="padding-right: 2.5rem;">
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
                Criar conta <x-gh-icon name="arrow-right" />
            </button>
        </div>
    </form>

    <p class="pub-foot-note">
        Já possui uma conta?
        <a href="{{ route('login') }}" class="pub-link-gold">Entrar</a>
    </p>
</x-guest-layout>
