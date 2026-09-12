<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased">
        <div class="rpg-shell flex min-h-screen items-center justify-center px-4 py-10 sm:px-6 lg:px-8">
            <div class="w-full max-w-xl">
                <div class="rpg-card overflow-hidden">
                    <div class="border-b border-[#a67a47]/50 bg-[#201913]/80 px-6 py-5 sm:px-8">
                        <div class="flex items-center gap-4">
                            <a href="/" class="inline-flex items-center justify-center rounded-full border border-[#d6ae6a]/40 bg-[#2f221b] p-3 text-[#f5d9a1] shadow-lg shadow-[#120d0b]/30">
                                <x-application-logo class="h-10 w-10 fill-current" />
                            </a>
                            <div>
                                <p class="text-[0.7rem] uppercase tracking-[0.3em] text-[#d8b97d]">A Taverna</p>
                                <h1 class="tavern-display text-3xl text-[#f6efe5]">Guildhall</h1>
                            </div>
                        </div>
                    </div>

                    <div class="px-6 py-6 sm:px-8 sm:py-8">
                        {{ $slot }}
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>
