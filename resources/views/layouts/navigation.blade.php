<nav x-data="{ open: false }" class="border-b border-[#a67a47]/40 bg-[#120d0b]/85 backdrop-blur-xl">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="flex h-20 items-center justify-between gap-4">
            <div class="flex items-center gap-5">
                <a href="{{ route('dashboard') }}" class="flex items-center gap-3 text-lg font-semibold text-[#f5d9a1]">
                    <span class="inline-flex h-10 w-10 items-center justify-center rounded-full border border-[#d6ae6a]/60 bg-gradient-to-br from-[#f1d8a3] via-[#d5a75d] to-[#8a5d32] font-black text-[#1f160f] shadow-lg shadow-[#120d0b]/40">A</span>
                    <span class="tavern-display text-2xl tracking-[0.08em] text-[#f7efe3]">Guildhall</span>
                </a>

                <div class="hidden items-center gap-2 sm:flex">
                    <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">Dashboard</a>
                    <a href="{{ route('characters.index') }}" class="nav-link {{ request()->routeIs('characters.*') ? 'active' : '' }}">Fichas</a>
                    <a href="{{ route('spells.index') }}" class="nav-link {{ request()->routeIs('spells.*') ? 'active' : '' }}">Magias</a>
                    <a href="{{ route('monsters.index') }}" class="nav-link {{ request()->routeIs('monsters.*') ? 'active' : '' }}">Monstros</a>
                    <a href="{{ route('campaigns.index') }}" class="nav-link {{ request()->routeIs('campaigns.*') ? 'active' : '' }}">Campanhas</a>
                    <a href="{{ route('friends.index') }}" class="nav-link {{ request()->routeIs('friends.*') ? 'active' : '' }}">Amigos</a>
                </div>
            </div>

            <div class="hidden items-center gap-3 sm:flex">
                <a href="{{ route('profile.edit') }}" class="nav-link">Perfil</a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="secondary-button px-4 py-2 text-sm">Sair</button>
                </form>
            </div>

            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center rounded-full border border-[#a67a47]/40 bg-[#231b16]/80 p-2 text-[#f5d9a1]">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <div :class="{'block': open, 'hidden': ! open}" class="hidden border-t border-[#a67a47]/30 bg-[#120d0b]/95 px-4 py-3 sm:hidden">
        <div class="space-y-2 text-sm text-[#f3ead8]">
            <a href="{{ route('dashboard') }}" class="block rounded-xl px-3 py-2 hover:bg-[#2a201a]">Dashboard</a>
            <a href="{{ route('characters.index') }}" class="block rounded-xl px-3 py-2 hover:bg-[#2a201a]">Fichas</a>
            <a href="{{ route('spells.index') }}" class="block rounded-xl px-3 py-2 hover:bg-[#2a201a]">Magias</a>
            <a href="{{ route('monsters.index') }}" class="block rounded-xl px-3 py-2 hover:bg-[#2a201a]">Monstros</a>
            <a href="{{ route('campaigns.index') }}" class="block rounded-xl px-3 py-2 hover:bg-[#2a201a]">Campanhas</a>
            <a href="{{ route('friends.index') }}" class="block rounded-xl px-3 py-2 hover:bg-[#2a201a]">Amigos</a>
            <a href="{{ route('profile.edit') }}" class="block rounded-xl px-3 py-2 hover:bg-[#2a201a]">Perfil</a>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="block w-full rounded-xl px-3 py-2 text-left hover:bg-[#2a201a]">Sair</button>
            </form>
        </div>
    </div>
</nav>
