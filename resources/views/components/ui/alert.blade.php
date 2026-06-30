@props(['variant' => 'info'])

@php
    $baseClasses = 'inline-block rounded-lg border px-md py-sm text-sm';
    $variantClasses = match ($variant) {
        'success' => 'bg-success-bg border-green-200 text-success',
        'warning' => 'bg-warning-bg border-amber-200 text-warning',
        'danger' => 'bg-danger-bg border-red-200 text-danger',
        default => 'bg-info-bg border-blue-200 text-info',
    };
@endphp

<div {{ $attributes->merge(['class' => trim("{$baseClasses} {$variantClasses}")]) }}>
    {{ $slot }}
</div>
