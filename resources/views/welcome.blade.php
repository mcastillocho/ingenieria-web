@extends('layout.base')

@section('title', 'Page Test — Base')
@section('pageTitle', 'Ferretería Abad')

@section('content')
    <div class="space-y-lg">
        <section class="grid gap-lg lg:grid-cols-3">
            <x-ui.card variant="stats">
                <x-slot name="title">Productos</x-slot>
                <x-slot name="body">
                    <p class="text-2xl font-bold text-ink">1,284</p>
                    <p class="text-xs text-muted mt-sm">+12 esta semana</p>
                </x-slot>
            </x-ui.card>

            <x-ui.card variant="stats">
                <x-slot name="title">Ventas hoy</x-slot>
                <x-slot name="body">
                    <p class="text-2xl font-bold text-ink">$4,320</p>
                    <p class="text-xs text-muted mt-sm">+8.2% vs ayer</p>
                </x-slot>
            </x-ui.card>

            <x-ui.card variant="stats">
                <x-slot name="title">Stock crítico</x-slot>
                <x-slot name="body">
                    <p class="text-2xl font-bold text-warning">7</p>
                    <p class="text-xs text-muted mt-sm">3 sin stock</p>
                </x-slot>
            </x-ui.card>
        </section>

        <section class="grid gap-lg">
            <x-ui.card variant="flat">
                <x-slot name="title">Alertas del sistema</x-slot>
                <x-slot name="body">
                    <div class="space-y-sm">
                        <x-ui.alert>Información: el sistema se actualizó correctamente.</x-ui.alert>
                        <x-ui.alert variant="success">Éxito: los datos de inventario se sincronizaron.</x-ui.alert>
                        <x-ui.alert variant="warning">Advertencia: quedan pocos items en stock.</x-ui.alert>
                        <x-ui.alert variant="danger">Error: no se pudo completar la operación.</x-ui.alert>
                    </div>
                </x-slot>
            </x-ui.card>
        </section>

        <section class="grid gap-lg lg:grid-cols-3">
            <x-ui.card variant="default" class="lg:col-span-2">
                <x-slot name="title">Ventas últimos 7 días</x-slot>
                <x-slot name="body">
                    <div class="grid gap-sm">
                        <div class="h-48 rounded-lg bg-canvas-alt border border-line flex items-center justify-center text-muted">
                            Gráfico de ventas (placeholder)
                        </div>
                        <div class="flex flex-wrap gap-sm">
                            <x-ui.button variant="ghost" size="sm">Hoy</x-ui.button>
                            <x-ui.button variant="ghost" size="sm">Semana</x-ui.button>
                            <x-ui.button variant="ghost" size="sm">Mes</x-ui.button>
                        </div>
                    </div>
                </x-slot>
            </x-ui.card>

            <x-ui.card variant="highlighted">
                <x-slot name="title">Producto destacado</x-slot>
                <x-slot name="body">
                    <div class="space-y-md">
                        <div>
                            <p class="text-xs text-muted uppercase tracking-wide">Taladro Percutor</p>
                            <h3 class="text-ink font-semibold text-lg">HP-2000</h3>
                            <p class="text-ink-soft text-sm">Motor 1200W con sistema antivibración.</p>
                        </div>
                        <div class="flex items-center justify-between gap-sm">
                            <div class="space-y-1">
                                <x-ui.price>$89.99</x-ui.price>
                                <x-ui.price variant="previous">$124.99</x-ui.price>
                            </div>
                            <x-ui.badge variant="ok">Stock OK</x-ui.badge>
                        </div>
                    </div>
                </x-slot>
            </x-ui.card>
        </section>

        <section class="grid gap-lg lg:grid-cols-2">
            <x-ui.card variant="flat">
                <x-slot name="title">Indicadores clave</x-slot>
                <x-slot name="body">
                    <div class="grid gap-sm">
                        <div class="bg-canvas-alt rounded-lg p-md border border-line">
                            <p class="text-xs text-muted uppercase tracking-wide">Pedidos activos</p>
                            <p class="text-2xl font-bold text-ink">32</p>
                        </div>
                        <div class="bg-canvas-alt rounded-lg p-md border border-line">
                            <p class="text-xs text-muted uppercase tracking-wide">Ticket promedio</p>
                            <p class="text-2xl font-bold text-ink">$74.50</p>
                        </div>
                    </div>
                </x-slot>
            </x-ui.card>

            <x-ui.card variant="flat">
                <x-slot name="title">Últimos productos</x-slot>
                <x-slot name="body">
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
                            <td class="px-md py-sm"><x-ui.badge variant="ok">OK</x-ui.badge></td>
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
                </x-slot>
            </x-ui.card>
        </section>

        <section class="grid gap-lg">
            <x-ui.card variant="flat">
                <x-slot name="title">Alertas del sistema</x-slot>
                <x-slot name="body">
                    <div class="space-y-sm">
                        <x-ui.alert>Información: el sistema se actualizó correctamente.</x-ui.alert>
                        <x-ui.alert variant="success">Éxito: los datos de inventario se sincronizaron.</x-ui.alert>
                        <x-ui.alert variant="warning">Advertencia: quedan pocos items en stock.</x-ui.alert>
                        <x-ui.alert variant="danger">Error: no se pudo completar la operación.</x-ui.alert>
                    </div>
                </x-slot>
            </x-ui.card>
        </section>

        <section class="grid gap-lg xl:grid-cols-3">
            <x-ui.product-card>
                <x-slot name="image">
                    <img src="https://via.placeholder.com/400x240" alt="Taladro" class="w-full" />
                </x-slot>
                <x-slot name="title">Taladro Percutor</x-slot>
                <x-slot name="subtitle">HP-2000 • DeWalt</x-slot>
                <x-slot name="description">Taladro profesional con 1200W y sistema antivibración.</x-slot>
                <x-slot name="price"><x-ui.price>$89.99</x-ui.price></x-slot>
                <x-slot name="previousPrice">$124.99</x-slot>
                <x-slot name="stockText">Stock: 42</x-slot>
                <x-slot name="stockBadge"><x-ui.badge variant="ok">Stock OK</x-ui.badge></x-slot>
                <x-slot name="footer"><x-ui.button class="w-full">Agregar</x-ui.button></x-slot>
            </x-ui.product-card>

            <x-ui.product-card>
                <x-slot name="image">
                    <img src="https://via.placeholder.com/400x240" alt="Sierra" class="w-full" />
                </x-slot>
                <x-slot name="title">Sierra Circular</x-slot>
                <x-slot name="subtitle">SC-7 • Makita</x-slot>
                <x-slot name="description">Sierra circular con guía láser y freno de seguridad.</x-slot>
                <x-slot name="price"><x-ui.price>$159.00</x-ui.price></x-slot>
                <x-slot name="previousPrice">$189.99</x-slot>
                <x-slot name="stockText">Stock: 0</x-slot>
                <x-slot name="stockBadge"><x-ui.badge variant="out">Sin stock</x-ui.badge></x-slot>
                <x-slot name="footer"><x-ui.button class="w-full">Ver más</x-ui.button></x-slot>
            </x-ui.product-card>

            <x-ui.product-card>
                <x-slot name="image">
                    <img src="https://via.placeholder.com/400x240" alt="Cinta Métrica" class="w-full" />
                </x-slot>
                <x-slot name="title">Cinta Métrica</x-slot>
                <x-slot name="subtitle">CM-5 • Stanley</x-slot>
                <x-slot name="description">Cinta métrica de 5 metros con gancho magnético y freno rápido.</x-slot>
                <x-slot name="price"><x-ui.price>$8.75</x-ui.price></x-slot>
                <x-slot name="previousPrice">$12.50</x-slot>
                <x-slot name="stockText">Stock: 120</x-slot>
                <x-slot name="stockBadge"><x-ui.badge variant="ok">OK</x-ui.badge></x-slot>
                <x-slot name="footer"><x-ui.button class="w-full">Ver más</x-ui.button></x-slot>
            </x-ui.product-card>
        </section>
    </div>
@endsection
