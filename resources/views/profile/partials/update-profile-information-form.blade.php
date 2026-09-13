<div class="gh-card">
    <h3 class="gh-card-title"><x-gh-icon name="user"/> Informações do perfil</h3>
    <p class="gh-card-description">Atualize seus dados pessoais e como você será identificado na plataforma.</p>

    <div class="mt-5 flex flex-wrap items-start gap-5" x-data="{ avatarPreview: null }">
        <div class="gh-avatar-upload">
            <template x-if="avatarPreview">
                <img :src="avatarPreview" alt="" class="gh-avatar-upload-image">
            </template>
            <template x-if="!avatarPreview">
                @if ($user->avatarUrl())
                    <img src="{{ $user->avatarUrl() }}" alt="" class="gh-avatar-upload-image">
                @else
                    <span class="gh-avatar-upload-placeholder"><x-gh-icon name="user"/></span>
                @endif
            </template>

            <label for="avatar" class="gh-avatar-upload-edit" aria-label="Alterar foto do perfil">
                <x-gh-icon name="camera"/>
            </label>
            <input
                id="avatar"
                name="avatar"
                type="file"
                accept="image/*"
                form="profile-information-form"
                class="sr-only"
                @change="avatarPreview = $event.target.files[0] ? URL.createObjectURL($event.target.files[0]) : null"
            >
        </div>

        <div>
            <p class="gh-hint">JPG, PNG ou WEBP. Máximo de 4&nbsp;MB.</p>
            @error('avatar')<p class="gh-field-error mt-1">{{ $message }}</p>@enderror

            @if ($user->avatarUrl())
                <form method="post" action="{{ route('profile.avatar.destroy') }}" class="mt-2">
                    @csrf
                    @method('delete')
                    <button type="submit" class="gh-avatar-upload-remove">Remover foto</button>
                </form>
            @endif
        </div>
    </div>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form id="profile-information-form" method="post" action="{{ route('profile.update') }}" enctype="multipart/form-data" class="mt-5 grid gap-4 sm:grid-cols-2">
        @csrf
        @method('patch')

        <div class="gh-field @error('name') gh-field-has-error @enderror">
            <label class="gh-label gh-label-required" for="name">Nome</label>
            <div class="gh-search-wrap">
                <x-gh-icon name="user" class="gh-search-icon"/>
                <input id="name" name="name" class="gh-input gh-input-with-icon" value="{{ old('name', $user->name) }}" required autofocus autocomplete="name">
            </div>
            @error('name')<p class="gh-field-error">{{ $message }}</p>@enderror
        </div>

        <div class="gh-field @error('email') gh-field-has-error @enderror">
            <label class="gh-label gh-label-required" for="email">E-mail</label>
            <div class="gh-search-wrap">
                <x-gh-icon name="mail" class="gh-search-icon"/>
                <input id="email" name="email" type="email" class="gh-input gh-input-with-icon" value="{{ old('email', $user->email) }}" required autocomplete="username">
            </div>
            @error('email')<p class="gh-field-error">{{ $message }}</p>@enderror

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <p class="gh-hint">
                    Seu e-mail não foi verificado.
                    <button type="submit" form="send-verification" class="gh-link-muted">Reenviar e-mail de verificação.</button>
                </p>

                @if (session('status') === 'verification-link-sent')
                    <p class="gh-hint" style="color: var(--gh-success)">Um novo link de verificação foi enviado para o seu e-mail.</p>
                @endif
            @endif
        </div>

        <div class="gh-field sm:col-span-2" x-data="{ bio: {{ \Illuminate\Support\Js::from(old('bio', $user->bio ?? '')) }} }">
            <label class="gh-label" for="bio">Biografia (opcional)</label>
            <textarea id="bio" name="bio" rows="3" maxlength="200" class="gh-textarea" x-model="bio" placeholder="Conte um pouco sobre você e seu estilo de jogo.">{{ old('bio', $user->bio) }}</textarea>
            <p class="gh-hint" style="text-align:right" x-text="bio.length + '/200'"></p>
            @error('bio')<p class="gh-field-error">{{ $message }}</p>@enderror
        </div>

        <div class="sm:col-span-2 flex items-center gap-3">
            <button type="submit" class="gh-btn gh-btn-primary"><x-gh-icon name="save"/> Salvar alterações</button>
        </div>
    </form>
</div>
