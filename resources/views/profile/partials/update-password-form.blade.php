<div class="gh-card">
    <h3 class="gh-card-title"><x-gh-icon name="lock"/> Atualizar senha</h3>
    <p class="gh-card-description">Mantenha sua conta segura com uma senha forte e única.</p>

    <form method="post" action="{{ route('password.update') }}" class="mt-5 grid gap-4 sm:grid-cols-3" x-data="{ showPassword: false, showConfirm: false }">
        @csrf
        @method('put')

        <div class="gh-field @error('current_password', 'updatePassword') gh-field-has-error @enderror">
            <label class="gh-label" for="update_password_current_password">Senha atual</label>
            <div class="gh-search-wrap">
                <x-gh-icon name="lock" class="gh-search-icon"/>
                <input id="update_password_current_password" name="current_password" type="password" class="gh-input gh-input-with-icon" autocomplete="current-password">
            </div>
            @error('current_password', 'updatePassword')<p class="gh-field-error">{{ $message }}</p>@enderror
        </div>

        <div class="gh-field @error('password', 'updatePassword') gh-field-has-error @enderror">
            <label class="gh-label" for="update_password_password">Nova senha</label>
            <div class="gh-search-wrap">
                <x-gh-icon name="lock" class="gh-search-icon"/>
                <input :type="showPassword ? 'text' : 'password'" id="update_password_password" name="password" class="gh-input gh-input-with-icon gh-input-with-toggle" autocomplete="new-password">
                <button type="button" class="gh-input-toggle" @click="showPassword = !showPassword" :aria-label="showPassword ? 'Ocultar senha' : 'Mostrar senha'">
                    <x-gh-icon name="eye" x-show="!showPassword"/>
                    <x-gh-icon name="eye-off" x-show="showPassword" x-cloak/>
                </button>
            </div>
            @error('password', 'updatePassword')<p class="gh-field-error">{{ $message }}</p>@enderror
        </div>

        <div class="gh-field @error('password_confirmation', 'updatePassword') gh-field-has-error @enderror">
            <label class="gh-label" for="update_password_password_confirmation">Confirmar nova senha</label>
            <div class="gh-search-wrap">
                <x-gh-icon name="lock" class="gh-search-icon"/>
                <input :type="showConfirm ? 'text' : 'password'" id="update_password_password_confirmation" name="password_confirmation" class="gh-input gh-input-with-icon gh-input-with-toggle" autocomplete="new-password">
                <button type="button" class="gh-input-toggle" @click="showConfirm = !showConfirm" :aria-label="showConfirm ? 'Ocultar senha' : 'Mostrar senha'">
                    <x-gh-icon name="eye" x-show="!showConfirm"/>
                    <x-gh-icon name="eye-off" x-show="showConfirm" x-cloak/>
                </button>
            </div>
            @error('password_confirmation', 'updatePassword')<p class="gh-field-error">{{ $message }}</p>@enderror
        </div>

        <p class="gh-hint sm:col-span-3">A senha deve ter pelo menos 8 caracteres, incluindo uma letra maiúscula, um número e um caractere especial.</p>

        <div class="sm:col-span-3">
            <button type="submit" class="gh-btn gh-btn-primary"><x-gh-icon name="lock"/> Atualizar senha</button>
        </div>
    </form>
</div>
