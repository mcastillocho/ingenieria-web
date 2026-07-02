<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Layout Test — Abad</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-canvas text-ink font-body min-h-screen">
    <div class="min-h-screen flex flex-col" id="app">
        <x-layout.topbar>
            <x-slot name="left">
                <div class="flex items-center gap-md">
                    <span class="text-ink font-semibold text-sm">Componente Layout</span>
                    <span class="text-xs text-muted bg-canvas-alt px-sm py-xs rounded-md">Prueba</span>
                </div>
            </x-slot>
            <x-slot name="right">
                <button class="text-ink-soft hover:text-ink transition-colors p-sm rounded-md hover:bg-canvas-alt">Acción</button>
                <span class="w-8 h-8 rounded-full bg-accent-soft text-accent flex items-center justify-center font-bold text-sm">LT</span>
            </x-slot>
        </x-layout.topbar>

        <div class="flex-1 min-h-0 flex">
            <x-layout.sidebar id="sidebar">
                <div class="flex flex-col gap-sm">
                    <x-layout.sidebar-item label="Layout" href="#" active="true" icon="◼" />
                    <x-layout.sidebar-item label="Sidebar" href="#sidebar-preview" icon="▣" />
                    <x-layout.sidebar-item label="Submenú" href="#" submenu="true" icon="▾">
                        <x-layout.sidebar-item label="Opción A" href="#sidebar-preview" icon="◼" />
                        <x-layout.sidebar-item label="Opción B" href="#topbar-preview" />
                        <x-layout.sidebar-item label="Opción C" href="#content-preview" />
                    </x-layout.sidebar-item>
                    <x-layout.sidebar-item label="Contenido" href="#content-preview" icon="◻" />
                </div>

                <x-slot name="footer">
                    <div class="flex items-center gap-sm text-sm text-ink-soft">
                        <span class="w-8 h-8 rounded-full bg-accent-soft text-accent flex items-center justify-center font-bold shrink-0">LT</span>
                        <div class="sidebar-text">
                            <p class="font-medium text-ink">Layout Tester</p>
                            <p class="text-xs text-muted">layout@test.local</p>
                        </div>
                    </div>
                </x-slot>
            </x-layout.sidebar>

            <div class="flex-1 min-w-0 flex flex-col min-h-0 overflow-y-auto">
                <main class="p-lg max-w-container mx-auto w-full" id="mainContent">
                    <section class="space-y-lg">
                        <div class="bg-surface rounded-lg border border-line shadow-card p-md">
                            <h1 class="text-2xl font-semibold text-ink">Prueba de Layout</h1>
                            <p class="text-ink-soft mt-sm">Esta página valida la estructura de los componentes de layout: sidebar y topbar. Usa el botón de la esquina superior izquierda para contraer/expandir el sidebar y desplázate para comprobar el comportamiento sticky.</p>
                        </div>

                        <div id="sidebar-preview" class="grid gap-lg lg:grid-cols-2">
                            <div class="bg-surface rounded-lg border border-line shadow-card p-md">
                                <h2 class="text-lg font-semibold text-ink mb-sm">Sidebar</h2>
                                <p class="text-ink-soft">El sidebar debe permanecer visible y escalable con el scroll. El pie de página del sidebar también se mantiene al fondo.</p>
                                <ul class="space-y-2 mt-md">
                                    <li class="rounded-md bg-canvas-alt border border-line p-md">Ver altura completa</li>
                                    <li class="rounded-md bg-canvas-alt border border-line p-md">Prueba texto e iconos</li>
                                    <li class="rounded-md bg-canvas-alt border border-line p-md">Validar estado collapsed</li>
                                </ul>
                            </div>
                            <div class="bg-surface rounded-lg border border-line shadow-card p-md">
                                <h2 class="text-lg font-semibold text-ink mb-sm">Topbar</h2>
                                <p class="text-ink-soft">El topbar superior debe permanecer sticky al hacer scroll dentro del área de contenido principal.</p>
                                <div class="mt-md space-y-sm">
                                    <div class="rounded-md bg-canvas-alt border border-line p-md">Slot izquierdo</div>
                                    <div class="rounded-md bg-canvas-alt border border-line p-md">Slot derecho</div>
                                </div>
                            </div>
                        </div>

                        <div id="content-preview" class="bg-surface rounded-lg border border-line shadow-card p-md">
                            <h2 class="text-lg font-semibold text-ink mb-sm">Contenido de prueba</h2>
                            <p class="text-ink-soft mb-md">Genera suficiente contenido para verificar el scroll, el ancho de la sección principal y la cohesión con el sidebar.</p>
                            <div class="grid gap-md lg:grid-cols-2">
                                <div class="rounded-lg border border-line bg-canvas-alt p-md space-y-md">
                                    <div class="h-28 rounded-md bg-white border border-line"></div>
                                    <div class="h-28 rounded-md bg-white border border-line"></div>
                                    <div class="h-28 rounded-md bg-white border border-line"></div>
                                </div>
                                <div class="rounded-lg border border-line bg-canvas-alt p-md space-y-md">
                                    <div class="h-28 rounded-md bg-white border border-line"></div>
                                    <div class="h-28 rounded-md bg-white border border-line"></div>
                                    <div class="h-28 rounded-md bg-white border border-line"></div>
                                </div>
                            </div>
                        </div>

                        <div class="grid gap-lg lg:grid-cols-3">
                            <div class="bg-surface rounded-lg border border-line p-md">
                                <p class="text-ink-soft">Comprueba la separación y el padding dentro del layout.</p>
                            </div>
                            <div class="bg-surface rounded-lg border border-line p-md">
                                <p class="text-ink-soft">El contenido principal debe ser responsive y adaptar su ancho.</p>
                            </div>
                            <div class="bg-surface rounded-lg border border-line p-md">
                                <p class="text-ink-soft">La barra lateral debe usar `w-full` en sus elementos y no forzar scroll horizontal.</p>
                            </div>
                        </div>
                    </section>
                </main>
            </div>
        </div>

</body>

</html>