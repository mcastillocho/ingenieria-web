@props(['showToggle' => true])

<header {{ $attributes->merge(['class' => 'h-topbar bg-surface border-b border-line px-md flex items-center justify-between sticky top-0 z-20']) }}>
    <div class="flex items-center gap-sm">
        @if($showToggle)
            <button id="sidebarToggle" class="topbar-toggle text-muted hover:text-ink transition-colors rounded-md hover:bg-canvas-alt" aria-label="Alternar sidebar">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
            </button>
        @endif
        {{ $left ?? '' }}
    </div>
    <div class="flex items-center gap-sm">
        {{ $right ?? '' }}
    </div>
</header>
