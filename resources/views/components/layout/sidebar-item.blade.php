@props(['label' => null, 'href' => '#', 'active' => false, 'icon' => null, 'submenu' => false, 'id' => null])

@php
    $classes = 'sidebar-link flex-1 flex items-center gap-sm px-md py-sm rounded-md text-sm font-medium text-ink-soft transition-colors';
    if ($active) {
        $classes .= ' active';
    }
@endphp

<div class="sidebar-item">
    <a
        href="{{ $href }}"
        id="{{ $id ?? '' }}"
        class="{{ $classes }}"
        @if($submenu) data-has-submenu="true" @endif
    >
        @if($icon)
            <span class="shrink-0">{{ $icon }}</span>
        @endif
        <span class="sidebar-text">{{ $label }}</span>
    </a>
    @if($submenu)
        <div class="sidebar-submenu mt-1 space-y-1">
            {{ $slot }}
        </div>
    @endif
</div>
