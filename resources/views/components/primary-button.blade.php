<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center justify-center rounded-full border border-[#d6ae6a]/60 bg-gradient-to-r from-[#f1d8a3] via-[#d5a75d] to-[#b97a37] px-5 py-2.5 text-sm font-semibold uppercase tracking-[0.18em] text-[#1f160f] shadow-lg shadow-[#120d0b]/30 transition duration-150 hover:-translate-y-0.5 focus:outline-none focus:ring-2 focus:ring-[#d5a75d] focus:ring-offset-2 focus:ring-offset-[#130f0d]']) }}>
    {{ $slot }}
</button>
