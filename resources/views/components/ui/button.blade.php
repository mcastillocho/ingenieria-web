@props([
    'variant' => 'primary',
    'size' => 'md',
    'type' => 'button',
    'href' => null,
])

@php
    $baseClasses = 'inline-flex items-center justify-center gap-2 rounded-md font-medium transition-colors duration-150 focus:outline-none focus:ring-2 focus:ring-accent-ring focus:ring-offset-2 focus:ring-offset-canvas';

    $variantClasses = match ($variant) {
        'secondary' => 'border border-line-strong bg-transparent text-ink hover:bg-canvas-alt',
        'ghost' => 'bg-transparent text-ink-soft hover:bg-canvas-alt',
        'danger' => 'bg-danger text-on-accent hover:bg-danger-hover',
        default => 'bg-accent text-on-accent hover:bg-accent-hover',
    };

    $sizeClasses = match ($size) {
        'sm' => 'px-sm py-xs text-sm',
        'lg' => 'px-lg py-sm text-base',
        default => 'px-md py-sm text-sm',
    };

    $buttonClasses = trim("{$baseClasses} {$variantClasses} {$sizeClasses}");
@endphp

@if ($href)
    <a href="{{ $href }}" {{ $attributes->merge(['class' => $buttonClasses]) }}>
        {{ $slot }}
    </a>
@else
    <button type="{{ $type }}" {{ $attributes->merge(['class' => $buttonClasses]) }}>
        {{ $slot }}
    </button>
@endif
