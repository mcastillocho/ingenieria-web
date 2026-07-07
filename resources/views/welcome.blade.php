@extends('layout.base')

@section('title', 'Dashboard — Ferretería Abad')
@section('pageTitle', 'Ferretería Abad')

@section('extraHead')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
@endsection

@section('content')
    <div class="space-y-lg">
        {{-- Tarjetas de Métricas --}}
        <section class="grid gap-lg lg:grid-cols-3">
            <x-ui.card variant="stats">
                <x-slot name="title">Productos</x-slot>
                <x-slot name="body">
                    <p class="text-2xl font-bold text-ink">{{ number_format($totalProducts) }}</p>
                    <p class="text-xs text-muted mt-sm">+{{ $productsNewThisWeek }} esta semana</p>
                </x-slot>
            </x-ui.card>

            <x-ui.card variant="stats">
                <x-slot name="title">Ventas hoy</x-slot>
                <x-slot name="body">
                    <p class="text-2xl font-bold text-ink">S/ {{ number_format($todaySalesSum, 2) }}</p>
                    @if($percentageChange > 0)
                        <p class="text-xs text-stock-ok mt-sm">+{{ number_format($percentageChange, 1) }}% vs ayer</p>
                    @elseif($percentageChange < 0)
                        <p class="text-xs text-stock-out mt-sm">{{ number_format($percentageChange, 1) }}% vs ayer</p>
                    @else
                        <p class="text-xs text-muted mt-sm">0% vs ayer</p>
                    @endif
                </x-slot>
            </x-ui.card>

            <x-ui.card variant="stats">
                <x-slot name="title">Stock crítico</x-slot>
                <x-slot name="body">
                    <p class="text-2xl font-bold {{ $outOfStock > 0 ? 'text-stock-out' : 'text-stock-low' }}">{{ $criticalStock }}</p>
                    <p class="text-xs text-muted mt-sm">{{ $outOfStock }} sin stock, {{ $lowStock }} stock bajo</p>
                </x-slot>
            </x-ui.card>
        </section>

        {{-- Alertas del sistema --}}
        @if(!empty($alerts))
        <section class="grid gap-lg">
            <x-ui.card variant="flat">
                <x-slot name="title">Alertas del sistema</x-slot>
                <x-slot name="body">
                    <div class="space-y-sm">
                        @foreach($alerts as $alert)
                            <x-ui.alert :variant="$alert['variant']">{{ $alert['message'] }}</x-ui.alert>
                        @endforeach
                    </div>
                </x-slot>
            </x-ui.card>
        </section>
        @endif

        {{-- Gráfico de ventas y Producto destacado --}}
        <section class="grid gap-lg lg:grid-cols-3">
            <x-ui.card variant="default" class="lg:col-span-2">
                <x-slot name="title">Ventas últimos 7 días</x-slot>
                <x-slot name="body">
                    <div class="h-64 w-full relative">
                        <canvas id="salesChart"></canvas>
                    </div>
                </x-slot>
            </x-ui.card>

            @if($featuredProduct)
            <x-ui.card variant="highlighted">
                <x-slot name="title">Producto destacado</x-slot>
                <x-slot name="body">
                    <div class="space-y-md">
                        <div>
                            <p class="text-xs text-muted uppercase tracking-wide font-medium">{{ $featuredProduct->category_name }}</p>
                            <h3 class="text-ink font-semibold text-lg mt-xs">{{ $featuredProduct->name }}</h3>
                            <p class="text-ink-soft text-sm mt-xs line-clamp-3">{{ $featuredProduct->description ?? 'Sin descripción disponible.' }}</p>
                        </div>
                        <div class="flex items-center justify-between gap-sm pt-sm border-t border-line">
                            <div class="space-y-1">
                                <x-ui.price>S/ {{ number_format($featuredProduct->sale_price, 2) }}</x-ui.price>
                                <x-ui.price variant="previous">S/ {{ number_format($featuredProduct->sale_price * 1.25, 2) }}</x-ui.price>
                            </div>
                            @if($featuredProduct->current_stock == 0)
                                <x-ui.badge variant="out">Sin stock</x-ui.badge>
                            @elseif($featuredProduct->current_stock <= 10)
                                <x-ui.badge variant="low">Stock Bajo</x-ui.badge>
                            @else
                                <x-ui.badge variant="ok">Stock OK</x-ui.badge>
                            @endif
                        </div>
                    </div>
                </x-slot>
            </x-ui.card>
            @else
            <x-ui.card variant="highlighted">
                <x-slot name="title">Producto destacado</x-slot>
                <x-slot name="body">
                    <div class="py-lg text-center text-muted">
                        No hay productos registrados en el sistema.
                    </div>
                </x-slot>
            </x-ui.card>
            @endif
        </section>

        {{-- Indicadores clave y Últimos productos --}}
        <section class="grid gap-lg lg:grid-cols-2">
            <x-ui.card variant="flat">
                <x-slot name="title">Indicadores clave</x-slot>
                <x-slot name="body">
                    <div class="grid gap-sm">
                        <div class="bg-canvas-alt rounded-lg p-md border border-line">
                            <p class="text-xs text-muted uppercase tracking-wide font-semibold">Clientes registrados</p>
                            <p class="text-2xl font-bold text-ink mt-xs">{{ number_format($totalClients) }}</p>
                        </div>
                        <div class="bg-canvas-alt rounded-lg p-md border border-line">
                            <p class="text-xs text-muted uppercase tracking-wide font-semibold">Ticket promedio</p>
                            <p class="text-2xl font-bold text-ink mt-xs">S/ {{ number_format($averageTicket, 2) }}</p>
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
                                <th class="px-md py-sm font-semibold">Producto</th>
                                <th class="px-md py-sm font-semibold">Stock</th>
                                <th class="px-md py-sm font-semibold text-right">Estado</th>
                            </tr>
                        </x-slot>
                        @forelse($recentProducts as $prod)
                        <tr class="hover:bg-canvas-alt">
                            <td class="px-md py-sm font-medium text-ink truncate max-w-[180px]">{{ $prod['name'] }}</td>
                            <td class="px-md py-sm text-ink-soft">{{ $prod['stock'] }}</td>
                            <td class="px-md py-sm text-right"><x-ui.badge :variant="$prod['badge_variant']">{{ $prod['badge_text'] }}</x-ui.badge></td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="px-md py-md text-center text-muted">No hay productos registrados.</td>
                        </tr>
                        @endforelse
                    </x-ui.data-table>
                </x-slot>
            </x-ui.card>
        </section>

        {{-- Tarjetas de producto inferiores --}}
        @if($bottomProducts->isNotEmpty())
        <section class="grid gap-lg xl:grid-cols-3">
            @foreach($bottomProducts as $prod)
            <x-ui.product-card>
                <x-slot name="image">
                    <img src="{{ $prod['image_path'] }}" alt="{{ $prod['name'] }}" class="w-full h-full object-cover" />
                </x-slot>
                <x-slot name="title">{{ $prod['name'] }}</x-slot>
                <x-slot name="subtitle">{{ $prod['supplier'] }} • {{ $prod['category'] }}</x-slot>
                <x-slot name="description">{{ $prod['description'] }}</x-slot>
                <x-slot name="price"><x-ui.price>S/ {{ number_format((float) $prod['sale_price'], 2) }}</x-ui.price></x-slot>
                <x-slot name="previousPrice">S/ {{ number_format((float) $prod['previous_price'], 2) }}</x-slot>
                <x-slot name="stockText">Stock: {{ $prod['stock'] }}</x-slot>
                <x-slot name="stockBadge"><x-ui.badge :variant="$prod['badge_variant']">{{ $prod['badge_text'] }}</x-ui.badge></x-slot>
                <x-slot name="footer">
                    <x-ui.button class="w-full" href="/productos">Ver más</x-ui.button>
                </x-slot>
            </x-ui.product-card>
            @endforeach
        </section>
        @endif
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const ctx = document.getElementById('salesChart').getContext('2d');
            const salesData = @json($salesLast7Days);
            
            const labels = salesData.map(item => item.date);
            const data = salesData.map(item => item.total);
            
            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Ventas (S/)',
                        data: data,
                        backgroundColor: '#0ea5e9', // Sky 500
                        borderColor: '#0284c7', // Sky 600
                        borderWidth: 1,
                        borderRadius: 4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: {
                                color: 'rgba(200, 200, 200, 0.15)'
                            },
                            ticks: {
                                callback: function(value) {
                                    return 'S/ ' + value;
                                }
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            }
                        }
                    }
                }
            });
        });
    </script>
@endsection
