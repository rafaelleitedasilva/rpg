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
            <path d="M11 3v4M11 15v4M4 9h4M14 9h4M6 6l2 2M15 11l2 2M16 6l-2 2M8 11l-2 2"/>
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
        @default
            <circle cx="12" cy="12" r="9"/>
    @endswitch
</svg>
