<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>UI Test — Abad</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body {
            min-height: 100vh;
        }
        .page-shell {
            min-height: 100vh;
            padding: 2rem 1.5rem;
            background: var(--color-canvas);
        }
        .page-header {
            max-width: 1120px;
            margin: 0 auto 2rem;
        }
        .section {
            margin-bottom: 2.5rem;
        }
        .section-grid {
            display: grid;
            gap: 1rem;
            grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
        }
        .cards-row {
            display: grid;
            gap: 1rem;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        }
    </style>
</head>
<body class="bg-canvas text-ink font-body">
    <div class="page-shell">
        <header class="page-header">
            <p class="text-xs uppercase tracking-wide text-muted">UI Test</p>
            <h1 class="text-3xl font-semibold text-ink mt-2">Comprobación de componentes</h1>
            <p class="text-ink-soft mt-2 max-w-2xl">Esta página muestra cada componente UI disponible en <code>resources/views/components/ui</code> con sus variantes principales. No se incluyen componentes de layout ni formularios.</p>
        </header>

        <main class="space-y-10">
            <section class="section">
                <div class="flex items-center justify-between gap-4 mb-4">
                    <div>
                        <h2 class="text-xl font-semibold text-ink">Badges</h2>
                        <p class="text-sm text-muted mt-1">Variantes de estado para etiquetas pequeñas.</p>
                    </div>
                </div>
                <div class="flex flex-wrap gap-sm">
                    <x-ui.badge>Neutral</x-ui.badge>
                    <x-ui.badge variant="ok">OK</x-ui.badge>
                    <x-ui.badge variant="low">Bajo</x-ui.badge>
                    <x-ui.badge variant="out">Sin stock</x-ui.badge>
                </div>
            </section>

            <section class="section">
                <div class="flex items-center justify-between gap-4 mb-4">
                    <div>
                        <h2 class="text-xl font-semibold text-ink">Botones</h2>
                        <p class="text-sm text-muted mt-1">Variantes de botón con tamaños y estilos.</p>
                    </div>
                </div>
                <div class="section-grid">
                    <div class="flex flex-wrap gap-sm items-center">
                        <x-ui.button>Primary</x-ui.button>
                        <x-ui.button variant="secondary">Secondary</x-ui.button>
                        <x-ui.button variant="ghost">Ghost</x-ui.button>
                        <x-ui.button variant="danger">Danger</x-ui.button>
                    </div>
                    <div class="flex flex-wrap gap-sm items-center">
                        <x-ui.button size="sm">Small</x-ui.button>
                        <x-ui.button>Medium</x-ui.button>
                        <x-ui.button size="lg">Large</x-ui.button>
                    </div>
                    <div class="flex flex-wrap gap-sm items-center">
                        <x-ui.button href="#">Link button</x-ui.button>
                        <x-ui.button variant="secondary" href="#">Secondary link</x-ui.button>
                    </div>
                </div>
            </section>

            <section class="section">
                <div class="flex items-center justify-between gap-4 mb-4">
                    <div>
                        <h2 class="text-xl font-semibold text-ink">Precios</h2>
                        <p class="text-sm text-muted mt-1">Variantes de precio con estado actual, anterior y oferta.</p>
                    </div>
                </div>
                <div class="flex flex-wrap gap-sm items-center">
                    <x-ui.price>$89.99</x-ui.price>
                    <x-ui.price variant="sale">$74.50</x-ui.price>
                    <x-ui.price variant="previous">$124.99</x-ui.price>
                </div>
            </section>

            <section class="section">
                <div class="flex items-center justify-between gap-4 mb-4">
                    <div>
                        <h2 class="text-xl font-semibold text-ink">Cards</h2>
                        <p class="text-sm text-muted mt-1">Tarjetas con variantes por defecto, plana y destacada.</p>
                    </div>
                </div>
                <div class="cards-row">
                    <x-ui.card>
                        <x-slot name="title">Card default</x-slot>
                        <x-slot name="body">
                            <p class="text-ink-soft">Contenido estándar con sombra y bordes.</p>
                        </x-slot>
                        <x-slot name="footer">
                            <span class="text-xs text-muted">Footer</span>
                        </x-slot>
                    </x-ui.card>

                    <x-ui.card variant="flat">
                        <x-slot name="title">Card flat</x-slot>
                        <x-slot name="body">
                            <p class="text-ink-soft">Sin sombra, estilo limpio y con borde.</p>
                        </x-slot>
                    </x-ui.card>

                    <x-ui.card variant="highlighted">
                        <x-slot name="title">Card highlighted</x-slot>
                        <x-slot name="body">
                            <p class="text-ink-soft">Variante resaltada con borde doble y sombra.</p>
                        </x-slot>
                    </x-ui.card>
                </div>
            </section>

            <section class="section">
                <div class="flex items-center justify-between gap-4 mb-4">
                    <div>
                        <h2 class="text-xl font-semibold text-ink">Cards especializadas</h2>
                        <p class="text-sm text-muted mt-1">Ejemplos de card para estadísticas y paneles de gráfico.</p>
                    </div>
                </div>
                <div class="cards-row">
                    <x-ui.card variant="stats">
                        <x-slot name="body">
                            <p class="text-xs text-muted uppercase tracking-wide">Ventas hoy</p>
                            <p class="text-3xl font-bold text-ink">$4,320</p>
                            <p class="text-xs text-stock-ok">+8.2% vs ayer</p>
                        </x-slot>
                    </x-ui.card>
                </div>
            </section>

            <section class="section">
                <div class="flex items-center justify-between gap-4 mb-4">
                    <div>
                        <h2 class="text-xl font-semibold text-ink">Data Table</h2>
                        <p class="text-sm text-muted mt-1">Tabla de datos simple con cabecera y filas.</p>
                    </div>
                </div>
                <x-ui.data-table>
                    <x-slot name="header">
                        <tr>
                            <th class="px-md py-sm">Producto</th>
                            <th class="px-md py-sm">Stock</th>
                            <th class="px-md py-sm">Estado</th>
                        </tr>
                    </x-slot>
                    <tr>
                        <td class="px-md py-sm">Taladro</td>
                        <td class="px-md py-sm">56</td>
                        <td class="px-md py-sm"><x-ui.badge variant="ok">OK</x-ui.badge></td>
                    </tr>
                    <tr>
                        <td class="px-md py-sm">Lijadora</td>
                        <td class="px-md py-sm">12</td>
                        <td class="px-md py-sm"><x-ui.badge variant="low">Bajo</x-ui.badge></td>
                    </tr>
                    <tr>
                        <td class="px-md py-sm">Sierra</td>
                        <td class="px-md py-sm">0</td>
                        <td class="px-md py-sm"><x-ui.badge variant="out">Agotado</x-ui.badge></td>
                    </tr>
                </x-ui.data-table>
            </section>

            <section class="section">
                <div class="flex items-center justify-between gap-4 mb-4">
                    <div>
                        <h2 class="text-xl font-semibold text-ink">Alertas</h2>
                        <p class="text-sm text-muted mt-1">Mensajes de estado para información, éxito, advertencia y error.</p>
                    </div>
                </div>
                <div class="space-y-sm">
                    <x-ui.alert>Este es un mensaje informativo.</x-ui.alert>
                    <x-ui.alert variant="success">Acción completada correctamente.</x-ui.alert>
                    <x-ui.alert variant="warning">Atención: revisa los datos antes de continuar.</x-ui.alert>
                    <x-ui.alert variant="danger">Error: no se pudo procesar la solicitud.</x-ui.alert>
                </div>
            </section>

            <section class="section">
                <div class="flex items-center justify-between gap-4 mb-4">
                    <div>
                        <h2 class="text-xl font-semibold text-ink">Product Cards</h2>
                        <p class="text-sm text-muted mt-1">Cartas de producto con imagen, precio, stock y acción.</p>
                    </div>
                </div>
                <div class="cards-row">
                    <x-ui.product-card>
                        <x-slot name="image">
                            <img src="https://via.placeholder.com/400x240?text=Taladro" alt="Taladro" class="w-full" />
                        </x-slot>
                        <x-slot name="title">Taladro Percutor</x-slot>
                        <x-slot name="subtitle">HP-2000 · DeWalt</x-slot>
                        <x-slot name="description">Taladro percutor profesional con 1200W y sistema antivibración.</x-slot>
                        <x-slot name="price"><x-ui.price>$89.99</x-ui.price></x-slot>
                        <x-slot name="previousPrice">$124.99</x-slot>
                        <x-slot name="stockText">Stock: 42</x-slot>
                        <x-slot name="stockBadge"><x-ui.badge variant="ok">Stock OK</x-ui.badge></x-slot>
                        <x-slot name="footer"><x-ui.button class="w-full">Agregar</x-ui.button><x-ui.button variant="secondary" class="w-full">Ver</x-ui.button></x-slot>
                    </x-ui.product-card>

                    <x-ui.product-card>
                        <x-slot name="image">
                            <img src="https://via.placeholder.com/400x240?text=Sierra" alt="Sierra" class="w-full" />
                        </x-slot>
                        <x-slot name="title">Sierra Circular</x-slot>
                        <x-slot name="subtitle">SC-7 · Makita</x-slot>
                        <x-slot name="description">Sierra de 7 1/4" con motor de 15A y guía láser.</x-slot>
                        <x-slot name="price"><x-ui.price>$159.00</x-ui.price></x-slot>
                        <x-slot name="stockText">Stock: 0</x-slot>
                        <x-slot name="stockBadge"><x-ui.badge variant="out">Sin stock</x-ui.badge></x-slot>
                        <x-slot name="footer"><x-ui.button variant="secondary" class="w-full">Ver</x-ui.button></x-slot>
                    </x-ui.product-card>
                </div>
            </section>
        </main>
    </div>
</body>
</html>
