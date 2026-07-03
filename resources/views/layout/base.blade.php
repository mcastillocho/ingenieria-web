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
                    <!--<span class="text-xs text-muted bg-canvas-alt px-sm py-xs rounded-md">@yield('appVersion', 'v0.1.0')</span>-->
                </div>
            </x-slot>
            <x-slot name="right">
                <!--<button class="text-ink-soft hover:text-ink transition-colors p-sm rounded-md hover:bg-canvas-alt">Acción</button>-->

                @php
                $workerTop = null;
                if (session('worker_id')) {
                $workerTop = \App\Models\Worker::find(session('worker_id'));
                }
                $initialsTop = 'JA';
                if ($workerTop) {
                $initialsTop = strtoupper(substr($workerTop->name ?? '', 0, 1) . substr($workerTop->lastname ?? '', 0, 1));
                }
                @endphp

                <div class="flex items-center gap-sm">
                    <div class="text-right">
                        <div class="text-sm font-medium text-ink">{{ $workerTop ? ($workerTop->name . ($workerTop->lastname ? ' ' . $workerTop->lastname : '')) : 'Usuario Demo' }}</div>
                        <div class="text-xs text-muted">{{ $workerTop ? $workerTop->email : 'demo@abad.local' }}</div>
                    </div>
                    <span class="w-8 h-8 rounded-full bg-accent-soft text-accent flex items-center justify-center font-bold text-sm">{{ $initialsTop }}</span>
                </div>
            </x-slot>
        </x-layout.topbar>

        <div class="flex-1 min-h-0 flex">
            <x-layout.sidebar id="sidebar">
                <div class="flex flex-col gap-sm">
                    @php $role = session('role'); @endphp

                    @if($role === 'admin')
                    <x-layout.sidebar-item label="Dashboard" href="#" active="true" iconPath="M3 12l2-2 7-7 7 7M13 5v6h6" />
                    <x-layout.sidebar-item label="Inventario" href="#" iconPath="M3 7l8-4 8 4M4 7v10l8 4 8-4V7" />
                    <x-layout.sidebar-item label="Tienda" href="#" iconPath="M3 3h18v18H3V3z M7 14l3-3 2 2 5-5" />
                    @elseif($role === 'logistica')
                    <x-layout.sidebar-item label="Inventario" href="#" iconPath="M3 7l8-4 8 4M4 7v10l8 4 8-4V7" />
                    @elseif($role === 'ventas')
                    <x-layout.sidebar-item label="Tienda" href="#" iconPath="M3 3h18v18H3V3z M7 14l3-3 2 2 5-5" />
                    @else
                    {{-- Por defecto no muestra nada si tiene un rol desconocido --}}
                    @endif
                </div>

                <x-slot name="footer">
                    @php
                    $worker = null;
                    if (session('worker_id')) {
                    $worker = \App\Models\Worker::find(session('worker_id'));
                    }
                    $initials = 'JA';
                    if ($worker) {
                    $initials = strtoupper(substr($worker->name ?? '', 0, 1) . substr($worker->lastname ?? '', 0, 1));
                    }
                    @endphp

                    <x-layout.sidebar-item
                        label="Cerrar sesión"
                        iconPath="M14 8v-2a2 2 0 0 0 -2 -2h-7a2 2 0 0 0 -2 2v12a2 2 0 0 0 2 2h7a2 2 0 0 0 2 -2v-2 M9 12h12l-3 -3 M18 15l3 -3"
                        formAction="{{ url('/logout') }}"
                        formMethod="POST"
                        class="text-danger"
                    />
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