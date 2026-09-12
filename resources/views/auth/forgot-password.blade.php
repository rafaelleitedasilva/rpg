<x-guest-layout
    heading="Recuperar acesso"
    description="Informe o e-mail associado à sua conta. Enviaremos um link para você criar uma nova senha."
    image="images/auth/forgot-password.jpg"
>
    <div class="pub-steps">
        <div class="pub-step is-active">
            <span class="pub-step-index">1</span> Informar e-mail
        </div>
        <span class="pub-step-connector"></span>
        <div class="pub-step">
            <span class="pub-step-index">2</span> Verificar e-mail
        </div>
    </div>

    <x-auth-session-status class="pub-alert pub-alert-success" :status="session('status')" />

    <form method="POST" action="{{ route('password.email') }}">
        @csrf

        <div class="pub-field" style="margin-top: 0;">
            <label for="email" class="pub-label">E-mail</label>
            <div class="pub-input-group">
                <span class="pub-input-icon"><x-gh-icon name="mail" /></span>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                    class="pub-input" placeholder="seu@email.com">
            </div>
            <x-input-error :messages="$errors->get('email')" class="mt-1" />
        </div>

        <div class="pub-submit">
            <button type="submit" class="pub-btn pub-btn-gold pub-btn-block">
                Enviar link de recuperação <x-gh-icon name="arrow-right" />
            </button>
        </div>
    </form>

    <a href="{{ route('login') }}" class="pub-back-link">
        <x-gh-icon name="arrow-left" /> Voltar para entrar
    </a>
</x-guest-layout>
