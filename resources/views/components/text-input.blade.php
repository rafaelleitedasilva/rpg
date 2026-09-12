@props(['disabled' => false])

<input {{ $disabled ? 'disabled' : '' }} {!! $attributes->merge(['class' => 'rounded-xl border border-[#8b6b47]/70 bg-[#140f0c]/80 px-3 py-2.5 text-[#f7f1e7] placeholder:text-[#c7b59c] shadow-sm transition focus:border-[#d6ae6a] focus:outline-none focus:ring-2 focus:ring-[#d5a75d]/30']) !!}>
