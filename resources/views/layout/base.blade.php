<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Ferretería Abad')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @yield('extraHead')
</head>
<body class="bg-canvas text-ink font-body min-h-screen overflow-x-hidden">
    <div id="app" class="min-h-screen flex flex-col">
        <x-layout.topbar>
            <x-slot name="left">
                <div class="flex items-center gap-md">
                    <span class="text-ink font-semibold text-sm" id="pageTitle">@yield('pageTitle', 'Panel de Control')</span>
                    <span class="text-xs text-muted bg-canvas-alt px-sm py-xs rounded-md">@yield('appVersion', 'v0.1.0')</span>
                </div>
            </x-slot>
            <x-slot name="right">
                <button class="text-ink-soft hover:text-ink transition-colors p-sm rounded-md hover:bg-canvas-alt">Acción</button>
                <span class="w-8 h-8 rounded-full bg-accent-soft text-accent flex items-center justify-center font-bold text-sm">JA</span>
            </x-slot>
        </x-layout.topbar>

        <div class="flex-1 min-h-0 flex">
            <x-layout.sidebar id="sidebar">
                <div class="flex flex-col gap-sm">
                    <x-layout.sidebar-item label="Dashboard" href="#" active="true" iconPath="M3 12l2-2 7-7 7 7M13 5v6h6" />
                    <x-layout.sidebar-item label="Inventario" href="#" iconPath="M3 7l8-4 8 4M4 7v10l8 4 8-4V7" />
                    <x-layout.sidebar-item label="Productos" href="#" iconPath="M21 16V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2h14a2 2 0 002-2z M7 8h10M7 12h10M7 16h10" />
                    <x-layout.sidebar-item label="Ventas" href="#" iconPath="M3 3h18v18H3V3z M7 14l3-3 2 2 5-5" />
                </div>

                <x-slot name="footer">
                    <div class="flex items-center gap-sm text-sm text-ink-soft">
                        <span class="w-8 h-8 rounded-full bg-accent-soft text-accent flex items-center justify-center font-bold shrink-0">JA</span>
                        <div class="sidebar-text">
                            <p class="font-medium text-ink">Usuario Demo</p>
                            <p class="text-xs text-muted">demo@abad.local</p>
                        </div>
                    </div>
                </x-slot>
            </x-layout.sidebar>

            <div class="flex-1 min-w-0 flex flex-col min-h-0 overflow-y-auto">
                <main class="p-lg max-w-container mx-auto w-full">
                    @yield('content')
                </main>
            </div>
        </div>
    </div>
</body>
</html>
