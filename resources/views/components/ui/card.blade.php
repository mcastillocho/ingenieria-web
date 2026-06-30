@props(['variant' => 'default'])

@php
    $baseClasses = 'rounded-lg border border-line overflow-hidden';
    $variantClasses = match ($variant) {
        'flat' => 'bg-surface shadow-none',
        'highlighted' => 'bg-surface border-2 border-accent-ring shadow-card',
        'stats' => 'bg-surface border border-line shadow-card',
        default => 'bg-surface shadow-card',
    };
@endphp

<div {{ $attributes->merge(['class' => trim("{$baseClasses} {$variantClasses}" )]) }}>
    @if (isset($icon) || isset($title))
        <div class="flex items-center gap-sm px-md py-sm border-b border-line bg-canvas-alt">
            @isset($icon)
                <div class="flex-none">{{ $icon }}</div>
            @endisset
            @isset($title)
                <div class="text-ink font-semibold">{{ $title }}</div>
            @endisset
        </div>
    @endif

    @isset($body)
        <div class="px-md py-md text-ink-soft">{{ $body }}</div>
    @endisset

    {{ $slot }}

    @isset($footer)
        <div class="px-md py-sm border-t border-line bg-canvas-alt">{{ $footer }}</div>
    @endisset
</div>
