@php
    // Permite pasar `width` al componente: <x-layout.sidebar width="220px">...
    $inlineStyle = isset($width) ? "--spacing-sidebar: {$width};" : null;
@endphp

<aside {{ $attributes->merge(['class' => 'sidebar flex h-full flex-col overflow-hidden border-r border-line bg-canvas-alt', 'style' => $inlineStyle]) }}>
    <nav class="sidebar-nav flex-1 overflow-y-auto p-sm">
        {{ $slot }}
    </nav>

    <div class="sidebar-footer border-t border-line bg-canvas-alt p-md">
        {{ $footer ?? '' }}
    </div>
</aside>
