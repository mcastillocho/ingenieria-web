@props(['variant' => 'neutral'])

@php
    $classes = match ($variant) {
        'ok' => 'bg-stock-ok-bg text-stock-ok border border-green-200',
        'low' => 'bg-stock-low-bg text-stock-low border border-amber-200',
        'out' => 'bg-stock-out-bg text-stock-out border border-red-200',
        default => 'bg-stock-neutral-bg text-stock-neutral border border-line',
    };
@endphp

<span {{ $attributes->merge(['class' => trim("inline-flex items-center gap-1 rounded-full px-sm py-xs text-xs font-medium {$classes}" )]) }}>
    {{ $slot }}
</span>
