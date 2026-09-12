@props(['label' => 'Mais informações'])

{{-- Accessible info tooltip: hover on desktop, click/tap toggle on touch. --}}
<span class="gh-tooltip" x-data="{ open: false }" @mouseenter="open = true" @mouseleave="open = false" @click.outside="open = false">
    <button
        type="button"
        class="gh-tooltip-trigger"
        @click="open = !open"
        :aria-expanded="open.toString()"
        aria-label="{{ $label }}"
    >i</button>

    <span x-show="open" x-transition.duration.150ms x-cloak role="tooltip" class="gh-tooltip-bubble">
        {{ $slot }}
    </span>
</span>
