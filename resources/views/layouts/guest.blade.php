<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Guildhall') }}</title>
        @include('layouts.partials.favicon')

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="gh-auth">
        <div class="pub-auth-shell">
            <div class="pub-auth-visual">
                {{--
                    Espaço reservado para a foto de fundo deste painel.
                    Quando a imagem chegar, salve-a em public/images/auth/<nome>.jpg
                    e informe o caminho na prop `image` do <x-guest-layout>, ex.:
                    <x-guest-layout image="images/auth/login.jpg" ...>
                --}}
                @if ($image && file_exists(public_path($image)))
                    <img src="{{ asset($image) }}" alt="">
                @else
                    <div class="pub-auth-visual-placeholder">
                        <x-gh-icon name="scroll" />
                        <span>Imagem em breve</span>
                    </div>
                @endif

                <a href="{{ url('/') }}" class="pub-brand">
                    <span class="pub-brand-mark">G</span>
                    <span class="pub-brand-name">Guildhall</span>
                </a>

                <div class="pub-auth-visual-body">
                    @if ($eyebrow)
                        <p class="pub-eyebrow">{{ $eyebrow }}</p>
                    @endif
                    <h1 class="pub-auth-heading">{{ $heading }}</h1>
                    @if ($subheading)
                        <p class="pub-auth-subheading">{{ $subheading }}</p>
                    @endif
                    @if ($description)
                        <p class="pub-auth-description">{{ $description }}</p>
                    @endif
                </div>
            </div>

            <div class="pub-auth-form-side">
                <a href="{{ url('/') }}" class="pub-brand">
                    <span class="pub-brand-mark">G</span>
                    <span class="pub-brand-name">Guildhall</span>
                </a>

                <div class="pub-auth-form-side-inner">
                    {{ $slot }}
                </div>
            </div>
        </div>
    </body>
</html>
