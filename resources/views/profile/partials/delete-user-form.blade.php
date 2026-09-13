<div class="gh-card gh-card-danger">
    <div class="flex flex-wrap items-center justify-between gap-4">
        <div class="flex items-start gap-3">
            <x-gh-icon name="trash" style="color: var(--gh-danger)"/>
            <div>
                <h3 class="gh-card-title" style="color: var(--gh-danger)">Excluir conta</h3>
                <p class="gh-card-description">Esta ação é irreversível. Todos os seus dados serão permanentemente removidos.</p>
            </div>
        </div>

        <button type="button" class="gh-btn gh-btn-danger" x-data="" x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')">
            <x-gh-icon name="trash"/> Excluir conta
        </button>
    </div>

    <x-modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable>
        <form method="post" action="{{ route('profile.destroy') }}" class="p-6">
            @csrf
            @method('delete')

            <h2 class="gh-card-title">Tem certeza que deseja excluir sua conta?</h2>

            <p class="gh-card-description mt-2">
                Assim que a conta for excluída, todos os seus dados serão permanentemente apagados. Digite sua senha para confirmar que deseja excluir a conta.
            </p>

            <div class="gh-field mt-5 @error('password', 'userDeletion') gh-field-has-error @enderror">
                <label for="password" class="gh-label sr-only">Senha</label>
                <input id="password" name="password" type="password" class="gh-input" placeholder="Senha">
                @error('password', 'userDeletion')<p class="gh-field-error">{{ $message }}</p>@enderror
            </div>

            <div class="mt-6 flex justify-end gap-3">
                <button type="button" class="gh-btn gh-btn-secondary" x-on:click="$dispatch('close')">Cancelar</button>
                <button type="submit" class="gh-btn gh-btn-danger">Excluir conta</button>
            </div>
        </form>
    </x-modal>
</div>
