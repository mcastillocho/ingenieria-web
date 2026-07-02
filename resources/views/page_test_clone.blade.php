<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Ferretería Abad — Page Test</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        .chart-placeholder {
            min-height: 12rem;
        }
        .product-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(15, 23, 42, 0.10);
        }
        .truncate-2 {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
        .page-content {
            transition: opacity 0.25s ease, transform 0.25s ease;
            opacity: 0;
            transform: translateY(10px);
        }
        .page-content.active {
            opacity: 1;
            transform: translateY(0);
        }
        .page-content.hidden {
            display: none;
        }
    </style>
</head>
<body class="bg-canvas text-ink font-body min-h-screen overflow-x-hidden">
    <div class="min-h-screen flex" id="app">
        <x-layout.sidebar id="sidebar">
            <x-layout.sidebar-item label="Dashboard" href="#" active="true" icon="◼" />
            <x-layout.sidebar-item label="Inventario" href="#" icon="▣" />
            <x-layout.sidebar-item label="Productos" href="#" icon="🧰" />
            <x-layout.sidebar-item label="Ventas" href="#" icon="💲" />

            <x-slot name="footer">
                <div class="flex items-center gap-sm text-sm text-ink-soft">
                    <span class="w-8 h-8 rounded-full bg-accent-soft text-accent flex items-center justify-center font-bold shrink-0">JA</span>
                    <div class="sidebar-text">
                        <p class="font-medium text-ink">Juan Abad</p>
                        <p class="text-xs text-muted">juan@abad.com</p>
                    </div>
                </div>
            </x-slot>
        </x-layout.sidebar>

        <div class="flex-1 min-w-0 flex flex-col min-h-0 overflow-y-auto">
            <x-layout.topbar>
                <x-slot name="left">
                    <div class="flex items-center gap-md">
                        <span class="text-ink font-semibold text-sm" id="pageTitle">Panel de Control</span>
                        <span class="text-xs text-muted bg-canvas-alt px-sm py-xs rounded-md">v0.1.0</span>
                    </div>
                </x-slot>
                <x-slot name="right">
                    <button class="text-ink-soft hover:text-ink transition-colors p-sm rounded-md hover:bg-canvas-alt">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                        </svg>
                    </button>
                    <span class="w-8 h-8 rounded-full bg-accent-soft text-accent flex items-center justify-center font-bold text-sm">JA</span>
                </x-slot>
            </x-layout.topbar>

            <main class="p-lg max-w-container mx-auto" id="mainContent">
                <div id="page-dashboard" class="page-content active">
                    <div class="flex flex-wrap gap-sm mb-lg">
                        <div class="flex-1 min-w-[200px] bg-info-bg border border-blue-200 rounded-md px-md py-sm text-sm text-info flex items-center gap-sm">
                            <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            Actualización de stock disponible.
                        </div>
                    <div class="flex-1 min-w-[200px] bg-success-bg border border-green-200 rounded-md px-md py-sm text-sm text-success flex items-center gap-sm">
                        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Orden #1289 completada.
                    </div>
                    <div class="flex-1 min-w-[200px] bg-warning-bg border border-amber-200 rounded-md px-md py-sm text-sm text-warning flex items-center gap-sm">
                        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                        </svg>
                        Stock bajo en 3 productos.
                    </div>
                    <div class="flex-1 min-w-[200px] bg-danger-bg border border-red-200 rounded-md px-md py-sm text-sm text-danger flex items-center gap-sm">
                        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Error al sincronizar proveedor.
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-md mb-lg">
                    <div class="bg-surface shadow-card rounded-lg p-md border border-line">
                        <p class="text-xs text-muted uppercase tracking-wide">Productos</p>
                        <p class="text-2xl font-bold text-ink">1,284</p>
                        <p class="text-xs text-stock-ok">+12 esta semana</p>
                    </div>
                    <div class="bg-surface shadow-card rounded-lg p-md border border-line">
                        <p class="text-xs text-muted uppercase tracking-wide">Ventas hoy</p>
                        <p class="text-2xl font-bold text-ink">$4,320</p>
                        <p class="text-xs text-stock-ok">+8.2% vs ayer</p>
                    </div>
                    <div class="bg-surface shadow-card rounded-lg p-md border border-line">
                        <p class="text-xs text-muted uppercase tracking-wide">Stock crítico</p>
                        <p class="text-2xl font-bold text-warning">7</p>
                        <p class="text-xs text-muted">3 sin stock</p>
                    </div>
                    <div class="bg-surface shadow-card rounded-lg p-md border border-line">
                        <p class="text-xs text-muted uppercase tracking-wide">Órdenes</p>
                        <p class="text-2xl font-bold text-ink">32</p>
                        <p class="text-xs text-muted">12 pendientes</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-lg mb-lg">
                    <x-ui.card class="lg:col-span-2" variant="default">
                        <x-slot name="body">
                            <div class="flex items-center justify-between mb-md">
                                <h3 class="text-ink-soft text-sm font-medium">Ventas (últimos 7 días)</h3>
                                <div class="flex gap-sm">
                                    <x-ui.button variant="ghost" size="sm">Hoy</x-ui.button>
                                    <x-ui.button variant="ghost" size="sm">Semana</x-ui.button>
                                    <x-ui.button variant="ghost" size="sm">Mes</x-ui.button>
                                </div>
                            </div>
                            <div class="chart-placeholder bg-canvas-alt rounded-md flex items-center justify-center text-muted text-sm border border-line">
                                Gráfico de ventas (placeholder)
                            </div>
                        </x-slot>
                    </x-ui.card>

                    <x-ui.card variant="highlighted">
                        <x-slot name="title">Producto destacado</x-slot>
                        <x-slot name="body">
                            <div class="flex items-start justify-between">
                                <div>
                                    <p class="text-xs text-muted uppercase tracking-wide">Producto destacado</p>
                                    <h4 class="text-ink font-semibold text-lg">Taladro Percutor</h4>
                                    <p class="text-ink-soft text-sm">Modelo HP-2000</p>
                                </div>
                                <x-ui.badge variant="ok">Stock OK</x-ui.badge>
                            </div>
                            <div class="mt-md flex items-end justify-between">
                                <div>
                                    <x-ui.price class="text-xl">$89.99</x-ui.price>
                                    <x-ui.price variant="previous">$124.99</x-ui.price>
                                </div>
                            </div>
                        </x-slot>
                    </x-ui.card>
                </div>

                <section class="grid gap-lg lg:grid-cols-2">
                    <x-ui.card variant="default">
                        <x-slot name="title">Resumen de acciones</x-slot>
                        <x-slot name="body">
                            <p class="text-ink-soft">Revisar los estados y acciones en el dashboard.</p>
                        </x-slot>
                        <div class="flex flex-wrap gap-3">
                            <x-ui.button size="sm">Small</x-ui.button>
                            <x-ui.button>Medium</x-ui.button>
                            <x-ui.button size="lg">Large</x-ui.button>
                        </div>
                    </x-ui.card>

                    <x-ui.card variant="flat">
                        <x-slot name="title">Tabla de ejemplo</x-slot>
                        <x-ui.data-table>
                            <x-slot name="header">
                                <tr>
                                    <th class="px-md py-sm">Producto</th>
                                    <th class="px-md py-sm">Stock</th>
                                    <th class="px-md py-sm">Estado</th>
                                </tr>
                            </x-slot>
                            <tr class="hover:bg-canvas-alt">
                                <td class="px-md py-sm">Taladro</td>
                                <td class="px-md py-sm">56</td>
                                <td class="px-md py-sm"><x-ui.badge variant="ok">Stock OK</x-ui.badge></td>
                            </tr>
                            <tr class="hover:bg-canvas-alt">
                                <td class="px-md py-sm">Sierra</td>
                                <td class="px-md py-sm">12</td>
                                <td class="px-md py-sm"><x-ui.badge variant="low">Bajo</x-ui.badge></td>
                            </tr>
                            <tr class="hover:bg-canvas-alt">
                                <td class="px-md py-sm">Llave Inglesa</td>
                                <td class="px-md py-sm">0</td>
                                <td class="px-md py-sm"><x-ui.badge variant="out">Sin stock</x-ui.badge></td>
                            </tr>
                        </x-ui.data-table>
                    </x-ui.card>
                </section>

                <section class="grid gap-lg lg:grid-cols-2 items-start">
                    <x-ui.product-card>
                        <x-slot name="image">
                            <img src="https://via.placeholder.com/400x240" alt="Taladro" class="w-full" />
                        </x-slot>
                        <x-slot name="title">Taladro Percutor</x-slot>
                        <x-slot name="subtitle">HP-2000 • DeWalt</x-slot>
                        <x-slot name="description">Taladro percutor profesional con 1200W, velocidad variable y sistema antivibración.</x-slot>
                        <x-slot name="price"><x-ui.price>$89.99</x-ui.price></x-slot>
                        <x-slot name="previousPrice">$124.99</x-slot>
                        <x-slot name="stockText">Stock: 42</x-slot>
                        <x-slot name="stockBadge"><x-ui.badge variant="ok">Stock OK</x-ui.badge></x-slot>
                        <x-slot name="footer">
                            <x-ui.button class="w-full">Agregar al carrito</x-ui.button>
                        </x-slot>
                    </x-ui.product-card>

                    <x-ui.card variant="flat">
                        <x-slot name="title">Notas de diseño</x-slot>
                        <x-slot name="body">
                            <p class="text-ink-soft">Esta página está construida con componentes Blade y utilidades definidas por el <code>@theme</code> de <code>resources/css/app.css</code>.</p>
                            <p class="text-ink-soft">Las clases sin componente directo se mantienen como CSS local en esta prueba.</p>
                        </x-slot>
                    </x-ui.card>
                </section>
            </div>

            <div id="page-products" class="page-content hidden">
                <div class="mb-lg">
                    <h2 class="text-2xl font-semibold text-ink">Catálogo de Productos</h2>
                    <p class="text-ink-soft">Navega entre los productos y prueba la transición de pestañas.</p>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-md items-start">
                    <x-ui.product-card>
                        <x-slot name="image">
                            <img src="https://via.placeholder.com/400x240" alt="Taladro Percutor" class="w-full" />
                        </x-slot>
                        <x-slot name="title">Taladro Percutor</x-slot>
                        <x-slot name="subtitle">HP-2000 • DeWalt</x-slot>
                        <x-slot name="description">Taladro percutor profesional con 1200W, velocidad variable y sistema antivibración.</x-slot>
                        <x-slot name="price"><x-ui.price>$89.99</x-ui.price></x-slot>
                        <x-slot name="previousPrice">$124.99</x-slot>
                        <x-slot name="stockText">Stock: 42</x-slot>
                        <x-slot name="stockBadge"><x-ui.badge variant="ok">Stock OK</x-ui.badge></x-slot>
                        <x-slot name="footer">
                            <x-ui.button class="w-full">Ver más</x-ui.button>
                        </x-slot>
                    </x-ui.product-card>
                    <x-ui.product-card>
                        <x-slot name="image">
                            <img src="https://via.placeholder.com/400x240" alt="Lija Eléctrica" class="w-full" />
                        </x-slot>
                        <x-slot name="title">Lija Eléctrica</x-slot>
                        <x-slot name="subtitle">LE-150 • Bosch</x-slot>
                        <x-slot name="description">Lijadora orbital con sistema de extracción de polvo, 350W y velocidad ajustable.</x-slot>
                        <x-slot name="price"><x-ui.price>$34.50</x-ui.price></x-slot>
                        <x-slot name="previousPrice">$49.99</x-slot>
                        <x-slot name="stockText">Stock: 8</x-slot>
                        <x-slot name="stockBadge"><x-ui.badge variant="low">Bajo</x-ui.badge></x-slot>
                        <x-slot name="footer">
                            <x-ui.button class="w-full">Ver más</x-ui.button>
                        </x-slot>
                    </x-ui.product-card>
                    <x-ui.product-card>
                        <x-slot name="image">
                            <img src="https://via.placeholder.com/400x240" alt="Sierra Circular" class="w-full" />
                        </x-slot>
                        <x-slot name="title">Sierra Circular</x-slot>
                        <x-slot name="subtitle">SC-7 • Makita</x-slot>
                        <x-slot name="description">Sierra circular de 7 1/4" con motor de 15A, guía láser y sistema de seguridad.</x-slot>
                        <x-slot name="price"><x-ui.price>$159.00</x-ui.price></x-slot>
                        <x-slot name="previousPrice">$189.99</x-slot>
                        <x-slot name="stockText">Stock: 0</x-slot>
                        <x-slot name="stockBadge"><x-ui.badge variant="out">Sin stock</x-ui.badge></x-slot>
                        <x-slot name="footer">
                            <x-ui.button class="w-full">Ver más</x-ui.button>
                        </x-slot>
                    </x-ui.product-card>
                    <x-ui.product-card>
                        <x-slot name="image">
                            <img src="https://via.placeholder.com/400x240" alt="Cinta Métrica" class="w-full" />
                        </x-slot>
                        <x-slot name="title">Cinta Métrica 5m</x-slot>
                        <x-slot name="subtitle">CM-5 • Stanley</x-slot>
                        <x-slot name="description">Cinta métrica de 5 metros con sistema de freno, gancho magnético y cinta de nylon.</x-slot>
                        <x-slot name="price"><x-ui.price>$8.75</x-ui.price></x-slot>
                        <x-slot name="previousPrice">$12.50</x-slot>
                        <x-slot name="stockText">Stock: 120</x-slot>
                        <x-slot name="stockBadge"><x-ui.badge variant="ok">OK</x-ui.badge></x-slot>
                        <x-slot name="footer">
                            <x-ui.button class="w-full">Ver más</x-ui.button>
                        </x-slot>
                    </x-ui.product-card>
                    <x-ui.product-card>
                        <x-slot name="image">
                            <img src="https://via.placeholder.com/400x240" alt="Martillo de Goma" class="w-full" />
                        </x-slot>
                        <x-slot name="title">Martillo de Goma</x-slot>
                        <x-slot name="subtitle">MG-2 • Stanley</x-slot>
                        <x-slot name="description">Martillo de goma con mango ergonómico, ideal para trabajos de carpintería y ensamblaje.</x-slot>
                        <x-slot name="price"><x-ui.price>$12.30</x-ui.price></x-slot>
                        <x-slot name="previousPrice">$16.75</x-slot>
                        <x-slot name="stockText">Stock: 15</x-slot>
                        <x-slot name="stockBadge"><x-ui.badge variant="low">Bajo</x-ui.badge></x-slot>
                        <x-slot name="footer">
                            <x-ui.button class="w-full">Ver más</x-ui.button>
                        </x-slot>
                    </x-ui.product-card>
                    <x-ui.product-card>
                        <x-slot name="image">
                            <img src="https://via.placeholder.com/400x240" alt="Destornillador Eléctrico" class="w-full" />
                        </x-slot>
                        <x-slot name="title">Destornillador Eléctrico</x-slot>
                        <x-slot name="subtitle">DE-200 • Black+Decker</x-slot>
                        <x-slot name="description">Destornillador eléctrico recargable con 20 accesorios, torque ajustable y luz LED.</x-slot>
                        <x-slot name="price"><x-ui.price>$45.99</x-ui.price></x-slot>
                        <x-slot name="previousPrice">$59.99</x-slot>
                        <x-slot name="stockText">Stock: 28</x-slot>
                        <x-slot name="stockBadge"><x-ui.badge variant="ok">OK</x-ui.badge></x-slot>
                        <x-slot name="footer">
                            <x-ui.button class="w-full">Ver más</x-ui.button>
                        </x-slot>
                    </x-ui.product-card>
                    <x-ui.product-card>
                        <x-slot name="image">
                            <img src="https://via.placeholder.com/400x240" alt="Nivel Láser" class="w-full" />
                        </x-slot>
                        <x-slot name="title">Nivel Láser</x-slot>
                        <x-slot name="subtitle">NL-100 • Bosch</x-slot>
                        <x-slot name="description">Nivel láser auto-nivelante con alcance de 30 metros, ideal para instalaciones y carpintería.</x-slot>
                        <x-slot name="price"><x-ui.price>$67.50</x-ui.price></x-slot>
                        <x-slot name="previousPrice">$89.99</x-slot>
                        <x-slot name="stockText">Stock: 3</x-slot>
                        <x-slot name="stockBadge"><x-ui.badge>Descontinuado</x-ui.badge></x-slot>
                        <x-slot name="footer">
                            <x-ui.button class="w-full">Ver más</x-ui.button>
                        </x-slot>
                    </x-ui.product-card>
                    <x-ui.product-card>
                        <x-slot name="image">
                            <img src="https://via.placeholder.com/400x240" alt="Compresor de Aire" class="w-full" />
                        </x-slot>
                        <x-slot name="title">Compresor de Aire</x-slot>
                        <x-slot name="subtitle">CA-50 • Campbell</x-slot>
                        <x-slot name="description">Compresor de aire portátil, 50 litros, 2 HP, ideal para talleres y trabajos de pintura.</x-slot>
                        <x-slot name="price"><x-ui.price>$199.00</x-ui.price></x-slot>
                        <x-slot name="previousPrice">$249.99</x-slot>
                        <x-slot name="stockText">Stock: 6</x-slot>
                        <x-slot name="stockBadge"><x-ui.badge variant="ok">OK</x-ui.badge></x-slot>
                        <x-slot name="footer">
                            <x-ui.button class="w-full">Ver más</x-ui.button>
                        </x-slot>
                    </x-ui.product-card>
                </div>
            </div>

            <div id="page-inventory" class="page-content hidden">
                <div class="mb-lg">
                    <h2 class="text-2xl font-semibold text-ink">Gestión de Inventario</h2>
                    <p class="text-ink-soft">Revisa y controla el stock con animaciones de cambio de pestañas.</p>
                </div>
                <x-ui.card>
                    <x-slot name="body">
                        <div class="space-y-md">
                            <p class="text-ink-soft">Sección de inventario en progreso.</p>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-md">
                                <div class="bg-surface rounded-lg p-md border border-line shadow-card">
                                    <p class="text-xs text-muted uppercase tracking-wide">Stock total</p>
                                    <p class="text-2xl font-bold text-ink">4,810</p>
                                </div>
                                <div class="bg-surface rounded-lg p-md border border-line shadow-card">
                                    <p class="text-xs text-muted uppercase tracking-wide">Productos críticos</p>
                                    <p class="text-2xl font-bold text-warning">14</p>
                                </div>
                            </div>
                        </div>
                    </x-slot>
                </x-ui.card>
            </div>

            <div id="page-sales" class="page-content hidden">
                <div class="mb-lg">
                    <h2 class="text-2xl font-semibold text-ink">Ventas</h2>
                    <p class="text-ink-soft">Vista de ventas y comportamiento del cliente.</p>
                </div>
                <x-ui.card>
                    <x-slot name="body">
                        <div class="grid gap-md">
                            <div class="bg-surface rounded-lg p-md border border-line shadow-card">
                                <p class="text-xs text-muted uppercase tracking-wide">Total ventas</p>
                                <p class="text-2xl font-bold text-ink">$14,925</p>
                            </div>
                            <div class="bg-surface rounded-lg p-md border border-line shadow-card">
                                <p class="text-xs text-muted uppercase tracking-wide">Ticket promedio</p>
                                <p class="text-2xl font-bold text-ink">$74.50</p>
                            </div>
                        </div>
                    </x-slot>
                </x-ui.card>
            </div>
        </main>
        </div>
    </div>

    <script>
        const pageTitle = document.getElementById('pageTitle');
        const pageContents = {
            dashboard: document.getElementById('page-dashboard'),
            products: document.getElementById('page-products'),
            inventory: document.getElementById('page-inventory'),
            sales: document.getElementById('page-sales'),
        };
        const pageNames = {
            dashboard: 'Panel de Control',
            products: 'Catálogo de Productos',
            inventory: 'Gestión de Inventario',
            sales: 'Ventas',
        };

        function showTab(page) {
            Object.entries(pageContents).forEach(([key, section]) => {
                if (!section) return;
                if (key === page) {
                    // Mostrar y forzar reflow antes de activar la clase para transicionar
                    section.classList.remove('hidden');
                    void section.offsetWidth;
                    requestAnimationFrame(() => section.classList.add('active'));
                } else {
                    // Si está activo, quitar la clase de entrada y esperar a la transición de salida
                    if (section.classList.contains('active')) {
                        section.classList.remove('active');
                        const onEnd = (e) => {
                            if (e && e.target !== section) return;
                            section.classList.add('hidden');
                            section.removeEventListener('transitionend', onEnd);
                        };
                        section.addEventListener('transitionend', onEnd);
                        // Fallback por si transitionend no se dispara
                        setTimeout(() => {
                            if (!section.classList.contains('hidden')) {
                                section.classList.add('hidden');
                                section.removeEventListener('transitionend', onEnd);
                            }
                        }, 300);
                    } else {
                        section.classList.add('hidden');
                    }
                }
            });
        }

        document.querySelectorAll('.sidebar-link').forEach(link => {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                const page = this.dataset.page;
                if (!page || !pageContents[page]) return;

                document.querySelectorAll('.sidebar-link').forEach(l => l.classList.remove('active'));
                this.classList.add('active');
                pageTitle.textContent = pageNames[page] || page;
                showTab(page);

                if (window.innerWidth < 768) {
                    document.getElementById('sidebar').classList.remove('sidebar-collapsed');
                }
            });
        });
    </script>
</body>
</html>
