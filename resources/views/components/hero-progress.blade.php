@props(['label' => '', 'value' => 0, 'maxValue' => 100, 'color' => 'bg-emerald-500'])

@php
    // Proteksi Division by Zero & pastikan nilai maksimal 100%
    $denominator = max($maxValue, 1);
    $percentage = min(round(($value / $denominator) * 100, 1), 100);
@endphp

<div class="flex flex-col gap-2 w-full">
    <div class="flex justify-between items-center text-xs font-bold text-slate-500 uppercase tracking-wider">
        <span>{{ $label }}</span>
        <span class="{{ str_replace('bg-', 'text-', $color) }}">{{ $percentage }}%</span>
    </div>

    <div class="w-full bg-slate-100 rounded-full h-2.5 overflow-hidden shadow-inner">
        <div class="{{ $color }} h-2.5 rounded-full transition-all duration-1000 ease-out shadow-sm" style="width: {{ $percentage }}%;"></div>
    </div>
</div>
