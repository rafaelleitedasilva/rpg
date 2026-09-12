@props(['status'])

@if ($status)
    <div {{ $attributes->merge(['class' => 'rounded-xl border border-[#58705d]/60 bg-[#1b241d]/80 px-3 py-2 text-sm font-medium text-[#cfe8d0]']) }}>
        {{ $status }}
    </div>
@endif
