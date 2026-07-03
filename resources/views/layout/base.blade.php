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
                    <x-layout.sidebar-item label="Dashboard"    href="/"             :active="request()->is('/')"             iconPath="M3 12l2-2 7-7 7 7M13 5v6h6" />
                    <x-layout.sidebar-item label="Inventario"   href="/inventario"   :active="request()->is('inventario*')"   iconPath="M3 7l8-4 8 4M4 7v10l8 4 8-4V7" />
                    <x-layout.sidebar-item label="Productos"    href="/productos"    :active="request()->is('productos*')"    iconPath="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z M3.27 6.96L12 12.01l8.73-5.05 M12 22.08V12" />
                    <x-layout.sidebar-item label="Proveedores"  href="/proveedores"  :active="request()->is('proveedores*')"  iconPath="M1 3h15v13H1zM16 8h4l3 3v5h-7V8zM5.5 21a1.5 1.5 0 1 0 0-3 1.5 1.5 0 0 0 0 3zM18.5 21a1.5 1.5 0 1 0 0-3 1.5 1.5 0 0 0 0 3z" />
                    <x-layout.sidebar-item label="Tienda"       href="#"             :active="false"                          iconPath="M3 3h18v18H3V3z M7 14l3-3 2 2 5-5" />
                    @elseif($role === 'logistica')
                    <x-layout.sidebar-item label="Inventario"   href="/inventario"   :active="request()->is('inventario*')"   iconPath="M3 7l8-4 8 4M4 7v10l8 4 8-4V7" />
                    <x-layout.sidebar-item label="Productos"    href="/productos"    :active="request()->is('productos*')"    iconPath="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z M3.27 6.96L12 12.01l8.73-5.05 M12 22.08V12" />
                    <x-layout.sidebar-item label="Proveedores"  href="/proveedores"  :active="request()->is('proveedores*')"  iconPath="M1 3h15v13H1zM16 8h4l3 3v5h-7V8zM5.5 21a1.5 1.5 0 1 0 0-3 1.5 1.5 0 0 0 0 3zM18.5 21a1.5 1.5 0 1 0 0-3 1.5 1.5 0 0 0 0 3z" />
                    @elseif($role === 'ventas')
                    <x-layout.sidebar-item label="Tienda"       href="#"             :active="false"                          iconPath="M3 3h18v18H3V3z M7 14l3-3 2 2 5-5" />
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

    {{-- ── Modal de confirmación de cierre de sesión ── --}}
    <div
        id="logout-modal"
        role="dialog"
        aria-modal="true"
        aria-labelledby="logout-modal-title"
        class="fixed inset-0 z-50 flex items-center justify-center"
        style="display: none !important;"
    >
        {{-- Backdrop --}}
        <div
            id="logout-modal-backdrop"
            class="absolute inset-0"
            style="opacity: 0; transition: opacity 0.2s ease; background: rgba(15,23,42,0.60); backdrop-filter: blur(4px);"
        ></div>

        {{-- Panel --}}
        <div
            id="logout-modal-panel"
            class="relative bg-surface rounded-lg flex flex-col"
            style="
                opacity: 0;
                transform: scale(0.95) translateY(-8px);
                transition: opacity 0.2s ease, transform 0.2s ease;
                width: calc(100% - 48px);
                max-width: 400px;
                padding: var(--spacing-lg);
                gap: var(--spacing-md);
                box-shadow: var(--shadow-elevated);
            "
        >
            {{-- Icono --}}
            <div class="flex items-center justify-center w-12 h-12 rounded-full bg-danger-bg mx-auto">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                     stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"
                     class="text-danger">
                    <path d="M14 8v-2a2 2 0 0 0 -2 -2h-7a2 2 0 0 0 -2 2v12a2 2 0 0 0 2 2h7a2 2 0 0 0 2 -2v-2"/>
                    <path d="M9 12h12l-3 -3M18 15l3 -3"/>
                </svg>
            </div>

            {{-- Texto --}}
            <div class="text-center">
                <h2 id="logout-modal-title" class="text-base font-semibold text-ink">
                    ¿Cerrar sesión?
                </h2>
                <p class="text-sm text-ink-soft mt-xs">
                    Tu sesión actual se cerrará y tendrás que volver a iniciar sesión para acceder al sistema.
                </p>
            </div>

            {{-- Acciones --}}
            <div class="flex gap-sm mt-xs">
                <button
                    id="logout-cancel-btn"
                    type="button"
                    class="flex-1 px-md py-sm rounded-md text-sm font-medium bg-canvas-alt text-ink-soft
                           border border-line hover:bg-line hover:text-ink transition-colors cursor-pointer"
                >
                    Cancelar
                </button>
                <button
                    id="logout-confirm-btn"
                    type="button"
                    class="flex-1 px-md py-sm rounded-md text-sm font-medium bg-danger text-on-accent
                           hover:bg-danger-hover transition-colors cursor-pointer"
                >
                    Sí, cerrar sesión
                </button>
            </div>
        </div>
    </div>

    <script>
        (function () {
            const modal     = document.getElementById('logout-modal');
            const backdrop  = document.getElementById('logout-modal-backdrop');
            const panel     = document.getElementById('logout-modal-panel');
            const cancelBtn = document.getElementById('logout-cancel-btn');
            const confirmBtn = document.getElementById('logout-confirm-btn');

            let logoutForm = null;

            // Buscar el formulario de logout generado por x-layout.sidebar-item
            function findLogoutForm() {
                // El sidebar-item con formAction genera un <form> con un <button> que contiene el label
                return document.querySelector('form[action="{{ url("/logout") }}"], form[action*="/logout"]');
            }

            function openModal() {
                modal.style.removeProperty('display');
                // Forzar reflow para que la transición CSS arranque
                void modal.offsetWidth;
                backdrop.style.opacity = '1';
                panel.style.opacity = '1';
                panel.style.transform = 'scale(1) translateY(0)';
                document.body.style.overflow = 'hidden';
                cancelBtn.focus();
            }

            function closeModal() {
                backdrop.style.opacity = '0';
                panel.style.opacity = '0';
                panel.style.transform = 'scale(0.95) translateY(-8px)';
                document.body.style.overflow = '';
                setTimeout(function () {
                    modal.style.display = 'none';
                }, 210);
            }

            function init() {
                logoutForm = findLogoutForm();
                if (!logoutForm) return;

                // Interceptar el submit del formulario de logout
                logoutForm.addEventListener('submit', function (e) {
                    e.preventDefault();
                    openModal();
                });

                // Botón cancelar
                cancelBtn.addEventListener('click', closeModal);

                // Botón confirmar: hace submit real del formulario
                confirmBtn.addEventListener('click', function () {
                    confirmBtn.disabled = true;
                    confirmBtn.textContent = 'Cerrando…';
                    logoutForm.submit();
                });

                // Cerrar con Escape
                document.addEventListener('keydown', function (e) {
                    if (e.key === 'Escape' && modal.style.display !== 'none') {
                        closeModal();
                    }
                });

                // Cerrar al hacer clic en el backdrop
                backdrop.addEventListener('click', closeModal);
            }

            // Ejecutar cuando el DOM esté listo
            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', init);
            } else {
                init();
            }
        })();
    </script>
</body>

</html>