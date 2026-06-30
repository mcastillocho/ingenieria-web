@props(['variant' => 'regular'])

@php
    $classes = match ($variant) {
        'sale' => 'text-price-sale font-semibold',
        'previous' => 'text-price-prev line-through text-sm',
        default => 'text-price font-semibold',
    };
@endphp

<span {{ $attributes->merge(['class' => $classes]) }}>{{ $slot }}</span>
