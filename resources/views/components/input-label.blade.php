@props(['value'])

<label {{ $attributes->merge(['class' => 'block text-sm font-medium uppercase tracking-[0.18em] text-[#d8b97d]']) }}>
    {{ $value ?? $slot }}
</label>
