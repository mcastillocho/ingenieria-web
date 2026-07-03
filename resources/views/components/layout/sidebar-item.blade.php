@props([
    'label' => null,
    'href' => '#',
    'active' => false,
    'icon' => null,
    'iconPath' => null,
    'submenu' => false,
    'id' => null,
    'formAction' => null,
    'formMethod' => 'POST',
    'buttonType' => 'submit',
])

@php
    $classes = 'sidebar-link flex-1 flex items-center gap-sm px-md py-sm rounded-md text-sm font-medium text-ink-soft transition-colors';
    if ($active) {
        $classes .= ' active';
    }
@endphp

<div class="sidebar-item">
    @if($formAction)
        <form method="{{ strtoupper($formMethod) }}" action="{{ $formAction }}">
            @csrf
            <button
                type="{{ $buttonType }}"
                id="{{ $id ?? '' }}"
                {{ $attributes->merge(['class' => $classes . ' w-full text-left']) }}
                @if($submenu) data-has-submenu="true" @endif
            >
                @if($iconPath)
                    <span class="shrink-0">
                        <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2">
                            <path d="{{ $iconPath }}" />
                        </svg>
                    </span>
                @elseif($icon)
                    <span class="shrink-0">{!! $icon !!}</span>
                @endif
                <span class="sidebar-text">{{ $label }}</span>
            </button>
        </form>
    @else
        <a
            href="{{ $href }}"
            id="{{ $id ?? '' }}"
            class="{{ $classes }}"
            @if($submenu) data-has-submenu="true" @endif
        >
            @if($iconPath)
                <span class="shrink-0">
                    <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2">
                        <path d="{{ $iconPath }}" />
                    </svg>
                </span>
            @elseif($icon)
                <span class="shrink-0">{!! $icon !!}</span>
            @endif
            <span class="sidebar-text">{{ $label }}</span>
        </a>
    @endif

    @if($submenu)
        <div class="sidebar-submenu mt-1 space-y-1">
            {{ $slot }}
        </div>
    @endif
</div>
