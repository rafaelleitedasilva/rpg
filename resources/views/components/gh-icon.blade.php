@props(['name'])

<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" {{ $attributes }}>
    @switch($name)
        @case('user')
            <circle cx="12" cy="8" r="3.5"/><path d="M5 20c0-3.6 3.1-6 7-6s7 2.4 7 6"/>
            @break
        @case('shield')
            <path d="M12 3.5 5 6v5.5c0 4.4 2.9 7.5 7 9 4.1-1.5 7-4.6 7-9V6l-7-2.5Z"/>
            @break
        @case('sparkles')
            <path d="M9.9 15.5a2 2 0 0 0-1.4-1.4L2.4 12.5a.5.5 0 0 1 0-1l6.1-1.6a2 2 0 0 0 1.4-1.4l1.6-6.1a.5.5 0 0 1 1 0l1.6 6.1a2 2 0 0 0 1.4 1.4l6.1 1.6a.5.5 0 0 1 0 1l-6.1 1.6a2 2 0 0 0-1.4 1.4l-1.6 6.1a.5.5 0 0 1-1 0Z" stroke-linejoin="round"/><path d="M20 3v4M22 5h-4M4 17v2M5 18H3"/>
            @break
        @case('sword')
            <path d="M14.5 3.5 20.5 9.5 10 20l-4-4L16.5 6l-2-2Z"/><path d="M3.5 20.5 6 18"/>
            @break
        @case('bag')
            <rect x="4" y="8" width="16" height="12" rx="2"/><path d="M9 8V6a3 3 0 0 1 6 0v2"/>
            @break
        @case('book')
            <path d="M4 5.5A2.5 2.5 0 0 1 6.5 3H20v15H6.5A2.5 2.5 0 0 0 4 20.5V5.5Z"/><path d="M4 20.5A2.5 2.5 0 0 1 6.5 18H20"/>
            @break
        @case('scroll')
            <path d="M6 4h9a3 3 0 0 1 3 3v11a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2z"/><path d="M6 4a2 2 0 0 0-2 2v1a2 2 0 0 0 2 2"/>
            @break
        @case('check')
            <path d="m4 12 5 5L20 6"/>
            @break
        @case('eye')
            <path d="M2.5 12S6 5.5 12 5.5 21.5 12 21.5 12 18 18.5 12 18.5 2.5 12 2.5 12Z"/><circle cx="12" cy="12" r="2.75"/>
            @break
        @case('arrow-left')
            <path d="M19 12H5M5 12l6-6M5 12l6 6"/>
            @break
        @case('arrow-right')
            <path d="M5 12h14M13 6l6 6-6 6"/>
            @break
        @case('save')
            <path d="M5 4h11l3 3v13H5z"/><path d="M8 4v5h7V4M8 20v-6h8v6"/>
            @break
        @case('mail')
            <rect x="3" y="5" width="18" height="14" rx="2"/><path d="m4 7 8 6 8-6"/>
            @break
        @case('lock')
            <rect x="4.5" y="10.5" width="15" height="9.5" rx="2"/><path d="M8 10.5V8a4 4 0 0 1 8 0v2.5"/>
            @break
        @case('eye-off')
            <path d="M3 3l18 18"/><path d="M10.6 5.6A10.7 10.7 0 0 1 12 5.5c6 0 9.5 6.5 9.5 6.5a13.9 13.9 0 0 1-3.14 3.9M6.6 6.6C4.16 8.2 2.5 12 2.5 12S6 18.5 12 18.5c1.36 0 2.6-.32 3.7-.84"/><path d="M9.9 9.9a3 3 0 0 0 4.2 4.2"/>
            @break
        @case('chevron-down')
            <path d="m6 9 6 6 6-6"/>
            @break
        @case('download')
            <path d="M12 3v12m0 0 4.5-4.5M12 15 7.5 10.5"/><path d="M4.5 17v2a2 2 0 0 0 2 2h11a2 2 0 0 0 2-2v-2"/>
            @break
        @case('home')
            <path d="M4 11.5 12 4l8 7.5"/><path d="M6 10v9a1 1 0 0 0 1 1h4v-6h2v6h4a1 1 0 0 0 1-1v-9"/>
            @break
        @case('flag')
            <path d="M6 3v18"/><path d="M6 4h10l-2.2 3.5L16 11H6"/>
            @break
        @case('people')
            <circle cx="8.5" cy="8" r="3"/><path d="M2.5 20c0-3.3 2.7-5.5 6-5.5s6 2.2 6 5.5"/><path d="M15.5 8.3a2.6 2.6 0 1 0 0-5.2"/><path d="M16 14.7c2.6.4 4.5 2.4 4.5 5.3"/>
            @break
        @case('compass')
            <circle cx="12" cy="12" r="9"/><path d="M12 2.5v2.2M12 19.3v2.2M2.5 12h2.2M19.3 12h2.2"/><path d="M12 8.2 13.4 12 12 15.8 10.6 12Z"/>
            @break
        @case('chevron-right')
            <path d="m9 6 6 6-6 6"/>
            @break
        @case('zap')
            <path d="M12 2 4 14h6l-1 8 9-13h-6l1-7Z"/>
            @break
        @case('calendar')
            <rect x="3.5" y="5" width="17" height="15" rx="2"/><path d="M3.5 9.5h17M8 3v3.5M16 3v3.5"/>
            @break
        @case('camera')
            <path d="M4 8a2 2 0 0 1 2-2h1.5l1-1.5h7l1 1.5H18a2 2 0 0 1 2 2v10a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2Z"/><circle cx="12" cy="13" r="3.5"/>
            @break
        @case('bell')
            <path d="M6 8a6 6 0 1 1 12 0c0 4 1.5 5.5 1.5 5.5H4.5S6 12 6 8Z" stroke-linejoin="round"/><path d="M10 19a2 2 0 0 0 4 0" stroke-linecap="round"/>
            @break
        @case('sliders')
            <path d="M5 6h14M5 12h14M5 18h14" stroke-linecap="round"/><circle cx="9" cy="6" r="1.8"/><circle cx="16" cy="12" r="1.8"/><circle cx="8" cy="18" r="1.8"/>
            @break
        @case('trash')
            <path d="M5 7h14M9 7V5.5A1.5 1.5 0 0 1 10.5 4h3A1.5 1.5 0 0 1 15 5.5V7m2 0-.7 12a2 2 0 0 1-2 1.9H9.7a2 2 0 0 1-2-1.9L7 7" stroke-linejoin="round"/>
            @break
        @case('search')
            <circle cx="11" cy="11" r="7"/><path d="m20.5 20.5-3.6-3.6"/>
            @break
        @case('filter')
            <path d="M4 5h16l-6.2 7.2v6.1l-3.6 2v-8.1L4 5Z" stroke-linejoin="round"/>
            @break
        @case('star')
            <path d="m12 3.5 2.5 5.3 5.8.6-4.3 3.9 1.2 5.7L12 16l-5.2 3 1.2-5.7-4.3-3.9 5.8-.6L12 3.5Z" stroke-linejoin="round"/>
            @break
        @case('dots-vertical')
            <circle cx="12" cy="5.5" r="1.3" fill="currentColor" stroke="none"/><circle cx="12" cy="12" r="1.3" fill="currentColor" stroke="none"/><circle cx="12" cy="18.5" r="1.3" fill="currentColor" stroke="none"/>
            @break
        @case('flame')
            <path d="M12 2.5c1.3 2.7 3.8 4.6 3.8 8.2a3.8 3.8 0 1 1-7.6 0c0-1 .5-1.8 1-2.5-.1.8.2 1.6 1 1.6.8 0 1.2-.6 1.2-1.3 0-1.5-1.2-3-.4-6Z" stroke-linejoin="round"/>
            @break
        @case('snowflake')
            <path d="M12 2.5v19M4.4 6.75l15.2 10.5M19.6 6.75 4.4 17.25"/>
            @break
        @case('droplet')
            <path d="M12 3s6.2 7.2 6.2 11.4A6.2 6.2 0 1 1 5.8 14.4C5.8 10.2 12 3 12 3Z" stroke-linejoin="round"/>
            @break
        @case('skull')
            <path d="M12 3a7 7 0 0 0-7 7c0 2.4 1.2 3.9 2.4 5.1V17a1 1 0 0 0 1 1H10v2h1.2v-2h1.6v2H14v-2h1.6a1 1 0 0 0 1-1v-1.9C17.8 13.9 19 12.4 19 10a7 7 0 0 0-7-7Z" stroke-linejoin="round"/><circle cx="9.6" cy="10" r="1.1" fill="currentColor" stroke="none"/><circle cx="14.4" cy="10" r="1.1" fill="currentColor" stroke="none"/>
            @break
        @case('sun')
            <circle cx="12" cy="12" r="4"/><path d="M12 2.5v3M12 18.5v3M3.8 12h3M17.2 12h3M6 6l2.1 2.1M15.9 15.9 18 18M18 6l-2.1 2.1M8.1 15.9 6 18"/>
            @break
        @case('diamond')
            <path d="M12 3 20 12 12 21 4 12Z" stroke-linejoin="round"/>
            @break
        @case('wind')
            <path d="M3 8h11.2a2.4 2.4 0 1 0-1.9-3.9"/><path d="M3 12.5h15.2a2.4 2.4 0 1 1-1.9 3.9"/><path d="M3 17h9.5"/>
            @break
        @case('leaf')
            <path d="M20 4C10 4 4 10 4 18c0 .5.5 1 1 1 8 0 14-6 14-15 0-.5-.5-1-1-1Z" stroke-linejoin="round"/><path d="M6.5 17.5c3.5-2 7-5.5 9-11" stroke-linecap="round"/>
            @break
        @case('portal')
            {{-- Conjuração: um círculo de invocação, com marcas nos quatro pontos cardeais. --}}
            <circle cx="12" cy="12" r="7.5"/><circle cx="12" cy="12" r="3.2"/><path d="M12 3v1.6M12 19.4V21M3 12h1.6M19.4 12H21" stroke-linecap="round"/>
            @break
        @case('spiral')
            {{-- Encantamento: um redemoinho hipnótico convergindo para um ponto. --}}
            <path d="M12 4a8 8 0 1 0 8 8" stroke-linecap="round"/><path d="M12 7.2a4.8 4.8 0 1 0 4.8 4.8" stroke-linecap="round"/><circle cx="12" cy="12" r="1.4" fill="currentColor" stroke="none"/>
            @break
        @case('mask')
            {{-- Ilusão: uma máscara/véu com olhos e sorriso pontilhados. --}}
            <path d="M4.5 8.5c0-3 3.3-5 7.5-5s7.5 2 7.5 5c0 5.5-3.2 10-7.5 12.5C7.7 18.5 4.5 14 4.5 8.5Z" stroke-linejoin="round"/><path d="M8.5 9.5h1.4M14.1 9.5h1.4" stroke-linecap="round"/><path d="M9 13.5c1 .7 2 1 3 1s2-.3 3-1"/>
            @break
        @case('cycle')
            {{-- Transmutação: duas setas em arco, representando transformação. --}}
            <path d="M4 9a8 8 0 0 1 13.8-5.6M20 5v4h-4"/><path d="M20 15a8 8 0 0 1-13.8 5.6M4 19v-4h4"/>
            @break
        @default
            <circle cx="12" cy="12" r="9"/>
    @endswitch
</svg>
