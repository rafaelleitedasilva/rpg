<x-guest-layout
    eyebrow="A Taverna"
    heading="Guildhall"
    subheading="Entre na sua mesa"
    description="Acesse suas fichas, campanhas e aventuras."
    image="images/auth/login.jpg"
>
    <h2 class="pub-auth-title">Entrar no Guildhall</h2>
    <p class="pub-auth-subtitle">Acesse suas fichas, campanhas e aventuras.</p>

    <x-auth-session-status class="pub-alert pub-alert-success mt-6" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" x-data="{ showPassword: false }">
        @csrf

        <div class="pub-field" style="margin-top: 1.75rem;">
            <label for="email" class="pub-label">E-mail</label>
            <div class="pub-input-group">
                <span class="pub-input-icon"><x-gh-icon name="mail" /></span>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                    autocomplete="username" class="pub-input" placeholder="seu@email.com">
            </div>
            <x-input-error :messages="$errors->get('email')" class="mt-1" />
        </div>

        <div class="pub-field">
            <label for="password" class="pub-label">Senha</label>
            <div class="pub-input-group">
                <span class="pub-input-icon"><x-gh-icon name="lock" /></span>
                <input :type="showPassword ? 'text' : 'password'" id="password" name="password" required
                    autocomplete="current-password" class="pub-input" placeholder="Digite sua senha"
                    style="padding-right: 2.5rem;">
                <button type="button" class="pub-input-toggle" @click="showPassword = !showPassword"
                    :aria-label="showPassword ? 'Ocultar senha' : 'Mostrar senha'">
                    <x-gh-icon name="eye" x-show="!showPassword" />
                    <x-gh-icon name="eye-off" x-show="showPassword" x-cloak />
                </button>
            </div>
            <x-input-error :messages="$errors->get('password')" class="mt-1" />
        </div>

        <div class="pub-row-between">
            <label class="pub-checkbox">
                <input type="checkbox" name="remember">
                Lembrar-me
            </label>

            @if (Route::has('password.request'))
                <a href="{{ route('password.request') }}" class="pub-link-gold">Esqueci minha senha</a>
            @endif
        </div>

        <div class="pub-submit">
            <button type="submit" class="pub-btn pub-btn-gold pub-btn-block">
                Entrar <x-gh-icon name="arrow-right" />
            </button>
        </div>
    </form>

    <div class="pub-divider">ou</div>

    {{-- Login social ainda não está conectado (sem provedor OAuth configurado). --}}
    <button type="button" class="pub-btn pub-btn-social pub-btn-block" disabled aria-disabled="true" title="Em breve">
        <svg viewBox="0 0 48 48" width="18" height="18" aria-hidden="true">
            <path fill="#FFC107" d="M43.611 20.083H42V20H24v8h11.303c-1.649 4.657-6.08 8-11.303 8-6.627 0-12-5.373-12-12s5.373-12 12-12c3.059 0 5.842 1.154 7.961 3.039l5.657-5.657C34.046 6.053 29.268 4 24 4 12.955 4 4 12.955 4 24s8.955 20 20 20c11.045 0 20-8.955 20-20 0-1.341-.138-2.65-.389-3.917z"/>
            <path fill="#FF3D00" d="m6.306 14.691 6.571 4.819C14.655 15.108 18.961 12 24 12c3.059 0 5.842 1.154 7.961 3.039l5.657-5.657C34.046 6.053 29.268 4 24 4 16.318 4 9.656 8.337 6.306 14.691z"/>
            <path fill="#4CAF50" d="M24 44c5.166 0 9.86-1.977 13.409-5.192l-6.19-5.238C29.211 35.091 26.715 36 24 36c-5.202 0-9.619-3.317-11.283-7.946l-6.522 5.025C9.505 39.556 16.227 44 24 44z"/>
            <path fill="#1976D2" d="M43.611 20.083H42V20H24v8h11.303c-.792 2.237-2.231 4.166-4.087 5.571.001-.001.002-.001.003-.002l6.19 5.238C36.971 39.205 44 34 44 24c0-1.341-.138-2.65-.389-3.917z"/>
        </svg>
        Continuar com Google
    </button>

    <p class="pub-foot-note">
        Não possui uma conta?
        <a href="{{ route('register') }}" class="pub-link-gold">Criar conta</a>
    </p>
</x-guest-layout>
