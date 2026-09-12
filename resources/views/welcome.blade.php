<!DOCTYPE html>
<html lang="pt-BR">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Guildhall — Plataforma de RPG</title>
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="rpg-shell">
        <div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
            <header class="rpg-card flex flex-col gap-6 px-6 py-5 md:flex-row md:items-center md:justify-between">
                <div class="flex items-center gap-4">
                    <div class="flex h-12 w-12 items-center justify-center rounded-2xl border border-[#d6ae6a]/60 bg-gradient-to-br from-[#f1d8a3] via-[#d5a75d] to-[#8a5d32] font-black text-[#1f160f] shadow-lg shadow-[#120d0b]/40">
                        A
                    </div>
                    <div>
                        <p class="text-[0.7rem] uppercase tracking-[0.3em] text-[#d8b97d]">Guildhall</p>
                        <h1 class="tavern-display text-3xl text-[#f7efe3]">AGENTE</h1>
                    </div>
                </div>

                <div class="flex items-center gap-3">
                    @if (Route::has('login'))
                        @auth
                            <a href="{{ url('/dashboard') }}" class="golden-button">Painel</a>
                        @else
                            <a href="{{ route('login') }}" class="secondary-button">Entrar</a>
                            @if (Route::has('register'))
                                <a href="{{ route('register') }}" class="golden-button">Criar conta</a>
                            @endif
                        @endauth
                    @endif
                </div>
            </header>

            <main class="mt-10 grid gap-8 lg:grid-cols-[1.2fr_0.8fr]">
                <section class="rpg-card p-8 sm:p-10">
                    <p class="mb-4 inline-flex rounded-full border border-[#d6ae6a]/40 bg-[#2f221b]/80 px-3 py-1 text-[0.7rem] font-semibold uppercase tracking-[0.25em] text-[#f5d9a1]">
                        D&D 5e + campanha + mesa
                    </p>
                    <h2 class="max-w-xl tavern-display text-5xl leading-none text-[#f7efe3] sm:text-6xl">
                        Sua mesa de RPG, pronta para a próxima aventura.
                    </h2>
                    <p class="mt-5 max-w-2xl text-lg text-[#dcccb0]">
                        Organize fichas, magias, grupos, mapas, monstros e sessões em um sistema pensado para jogadores e mestres.
                    </p>

                    <div class="mt-8 flex flex-wrap gap-4">
                        <a href="{{ route('register') }}" class="golden-button">Montar minha ficha</a>
                        <a href="#features" class="secondary-button">Explorar módulos</a>
                    </div>

                    <div class="mt-10 grid gap-4 sm:grid-cols-3">
                        <div class="stat-chip">
                            <p class="text-[0.68rem] uppercase tracking-[0.22em] text-[#d8b97d]">Fichas</p>
                            <p class="mt-3 text-3xl font-black text-[#fffaf1]">D&D 5e</p>
                        </div>
                        <div class="stat-chip">
                            <p class="text-[0.68rem] uppercase tracking-[0.22em] text-[#d8b97d]">Magias</p>
                            <p class="mt-3 text-3xl font-black text-[#fffaf1]">Busca</p>
                        </div>
                        <div class="stat-chip">
                            <p class="text-[0.68rem] uppercase tracking-[0.22em] text-[#d8b97d]">Mesa</p>
                            <p class="mt-3 text-3xl font-black text-[#fffaf1]">Combate</p>
                        </div>
                    </div>
                </section>

                <aside class="rpg-card overflow-hidden">
                    <div class="border-b border-[#a67a47]/40 bg-gradient-to-r from-[#201913] via-[#2d221a] to-[#3a2b20] p-6">
                        <p class="text-[0.68rem] uppercase tracking-[0.25em] text-[#d8b97d]">Visão geral</p>
                        <p class="mt-3 tavern-display text-3xl text-[#f7efe3]">Sistema modular</p>
                    </div>
                    <div class="space-y-4 p-6 text-sm text-[#dcccb0]">
                        <div class="rounded-2xl border border-[#a67a47]/30 bg-[#130f0d]/60 p-4">
                            <p class="font-semibold text-[#fffaf1]">Jogador</p>
                            <p class="mt-2">Ficha completa, atributos, perícias, magias e equipamentos.</p>
                        </div>
                        <div class="rounded-2xl border border-[#a67a47]/30 bg-[#130f0d]/60 p-4">
                            <p class="font-semibold text-[#fffaf1]">Mestre</p>
                            <p class="mt-2">Mapas, grid de combate, monstros, tokens e cenas narrativas.</p>
                        </div>
                        <div class="rounded-2xl border border-[#a67a47]/30 bg-[#130f0d]/60 p-4">
                            <p class="font-semibold text-[#fffaf1]">Social</p>
                            <p class="mt-2">Convide amigos, monte grupos e acompanhe a sessão em tempo real.</p>
                        </div>
                    </div>
                </aside>
            </main>

            <section id="features" class="mt-12 grid gap-6 md:grid-cols-3">
                <div class="rpg-card p-6">
                    <p class="text-[#d8b97d]">01</p>
                    <h3 class="mt-3 text-xl font-bold text-[#fffaf1]">Ficha de personagem</h3>
                    <p class="mt-3 text-[#dcccb0]">Crie e gerencie fichas D&D 5e com atributos, bônus, perícias, HP, CA e magias.</p>
                </div>
                <div class="rpg-card p-6">
                    <p class="text-[#d8b97d]">02</p>
                    <h3 class="mt-3 text-xl font-bold text-[#fffaf1]">Catálogo de magias</h3>
                    <p class="mt-3 text-[#dcccb0]">Pesquise por nível, classe, escola e filtros para montar a lista disponível do personagem.</p>
                </div>
                <div class="rpg-card p-6">
                    <p class="text-[#d8b97d]">03</p>
                    <h3 class="mt-3 text-xl font-bold text-[#fffaf1]">Mesa do mestre</h3>
                    <p class="mt-3 text-[#dcccb0]">Prepare cenas, monstros, mapas, grid e narrativas em um ambiente exclusivo para a campanha.</p>
                </div>
            </section>
        </div>
    </body>
</html>
