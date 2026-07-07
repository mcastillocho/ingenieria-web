@extends('layout.base')

@section('title', 'Historial de Ventas — Ferretería Abad')
@section('pageTitle', 'Historial de Ventas')

@section('content')
<div class="flex flex-col gap-lg">
    {{-- Tarjetas de Métricas --}}
    <div class="grid gap-md sm:grid-cols-2">
        <x-ui.card variant="stats">
            <div class="px-md py-md flex items-center gap-md">
                <div class="w-12 h-12 rounded-full bg-accent-soft text-accent flex items-center justify-center">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline>
                    </svg>
                </div>
                <div>
                    <p class="text-xs text-muted uppercase tracking-wide font-medium">Total Ventas</p>
                    <p class="text-2xl font-bold text-ink">{{ $totalSalesCount }}</p>
                </div>
            </div>
        </x-ui.card>
        
        <x-ui.card variant="stats">
            <div class="px-md py-md flex items-center gap-md">
                <div class="w-12 h-12 rounded-full bg-stock-ok/20 text-stock-ok flex items-center justify-center">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="12" y1="1" x2="12" y2="23"></line><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path>
                    </svg>
                </div>
                <div>
                    <p class="text-xs text-muted uppercase tracking-wide font-medium">Ingresos (S/)</p>
                    <p class="text-2xl font-bold text-ink">S/ {{ number_format($totalRevenue, 2) }}</p>
                </div>
            </div>
        </x-ui.card>
    </div>

    {{-- Formulario de Filtros --}}
    <x-ui.card>
        <form action="{{ route('ventas.historial') }}" method="GET" class="px-md py-md flex flex-col md:flex-row md:items-end gap-md">
            <div class="flex-1 flex gap-md">
                <div class="flex-1">
                    <label class="block text-xs font-medium text-ink-soft mb-1">Fecha Desde</label>
                    <input type="date" name="start_date" value="{{ $startDate }}" class="w-full text-sm rounded-md border border-line bg-white px-md py-sm focus:border-accent focus:ring-2 focus:outline-none">
                </div>
                <div class="flex-1">
                    <label class="block text-xs font-medium text-ink-soft mb-1">Fecha Hasta</label>
                    <input type="date" name="end_date" value="{{ $endDate }}" class="w-full text-sm rounded-md border border-line bg-white px-md py-sm focus:border-accent focus:ring-2 focus:outline-none">
                </div>
            </div>
            <div class="flex gap-sm shrink-0">
                <a href="{{ route('ventas.historial') }}" class="px-lg py-sm rounded-md text-sm font-medium bg-canvas-alt text-ink-soft hover:bg-line transition-colors text-center min-w-[100px]">Limpiar</a>
                <button type="submit" class="px-lg py-sm rounded-md text-sm font-medium bg-accent text-white hover:bg-accent-hover transition-colors min-w-[100px]">Filtrar</button>
            </div>
        </form>
    </x-ui.card>

    {{-- Tabla de Ventas --}}
    <x-ui.card>
        <x-ui.data-table>
            <x-slot name="header">
                <tr>
                    <th class="px-md py-sm font-semibold">Ticket #</th>
                    <th class="px-md py-sm font-semibold">Fecha y Hora</th>
                    <th class="px-md py-sm font-semibold">Cliente</th>
                    <th class="px-md py-sm font-semibold">Vendedor</th>
                    <th class="px-md py-sm font-semibold text-right">Total</th>
                    <th class="px-md py-sm font-semibold text-right">Acciones</th>
                </tr>
            </x-slot>
                @forelse($sales as $sale)
                    <tr class="hover:bg-canvas-alt transition-colors">
                        <td class="px-md py-sm font-mono font-medium text-ink">
                            {{ str_pad($sale->id, 6, '0', STR_PAD_LEFT) }}
                        </td>
                        <td class="px-md py-sm">
                            {{ $sale->created_at->format('d/m/Y H:i') }}
                        </td>
                        <td class="px-md py-sm">
                            {{ $sale->client->name ?? 'Cliente General' }} {{ $sale->client->lastname ?? '' }}
                        </td>
                        <td class="px-md py-sm">
                            {{ $sale->worker->name ?? 'Sistema' }}
                        </td>
                        <td class="px-md py-sm text-right font-bold text-accent">
                            S/ {{ number_format($sale->total_amount, 2) }}
                        </td>
                        <td class="px-md py-sm text-right">
                            <button type="button" 
                                    class="btn-view-details text-accent hover:text-accent-hover font-medium text-sm transition-colors"
                                    data-sale="{{ json_encode([
                                        'id' => str_pad($sale->id, 6, '0', STR_PAD_LEFT),
                                        'client' => ($sale->client->name ?? 'Cliente General') . ' ' . ($sale->client->lastname ?? ''),
                                        'date' => $sale->created_at->format('d/m/Y H:i'),
                                        'total' => 'S/ ' . number_format($sale->total_amount, 2),
                                        'details' => $sale->saleDetails->map(fn($detail) => [
                                            'quantity' => $detail->quantity,
                                            'name' => ($detail->batch->product->name ?? 'Producto') . ' (' . ($detail->batch->supplier->name ?? 'Sin Prov.') . ')',
                                            'subtotal' => 'S/ ' . number_format($detail->quantity * $detail->unit_price, 2)
                                        ])
                                    ]) }}">
                                Ver Detalles
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-md py-lg text-center text-muted">
                            No se encontraron ventas registradas.
                        </td>
                    </tr>
                @endforelse
        </x-ui.data-table>

        {{-- Paginación --}}
        @if($sales->hasPages())
            <div class="mt-md border-t border-line pt-md">
                {{ $sales->withQueryString()->links() }}
            </div>
        @endif
    </x-ui.card>
</div>

{{-- ── Modal de Detalle de Boleta ── --}}
<div id="receipt-modal" class="fixed inset-0 z-50 flex items-center justify-center p-md" style="display: none;">
    <div id="receipt-modal-backdrop" class="absolute inset-0 bg-ink/50 backdrop-blur-sm opacity-0 transition-opacity duration-200 cursor-pointer"></div>
    
    <div id="receipt-modal-panel" class="relative bg-surface rounded-lg flex flex-col" style="
        opacity: 0;
        transform: scale(0.95) translateY(-8px);
        transition: opacity 0.2s ease, transform 0.2s ease;
        width: calc(100% - 48px);
        max-width: 400px;
        max-height: calc(100vh - 48px);
        box-shadow: var(--shadow-elevated);
    ">
        <div class="flex-1 overflow-y-auto p-lg flex flex-col min-h-0">
            <div class="text-center mb-md shrink-0">
                <div class="w-12 h-12 rounded-full bg-stock-ok/20 text-stock-ok flex items-center justify-center mx-auto mb-sm">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline>
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-ink">Detalle de Venta</h3>
                <p class="text-sm text-ink-soft mt-1">Ticket #<span id="receipt-sale-id" class="font-mono font-bold"></span></p>
            </div>
            
            <div class="border-t border-b border-line py-sm mb-md text-sm shrink-0">
                <div class="flex justify-between mb-1">
                    <span class="text-muted">Cliente:</span>
                    <span class="font-medium text-ink truncate max-w-[180px]" id="receipt-client"></span>
                </div>
                <div class="flex justify-between mb-1">
                    <span class="text-muted">Fecha:</span>
                    <span class="font-medium text-ink" id="receipt-date"></span>
                </div>
            </div>

            <div class="text-sm font-bold text-ink mb-2 shrink-0">Artículos:</div>
            
            <div id="receipt-items" class="text-sm flex-1 overflow-y-auto mb-md space-y-1 min-h-[50px]">
                <!-- Items rendered via JS -->
            </div>

            <div class="border-t border-dashed border-line pt-sm mb-lg shrink-0">
                <div class="flex justify-between text-lg font-bold text-ink">
                    <span>Total:</span>
                    <span id="receipt-total"></span>
                </div>
            </div>

            <button id="btn-close-receipt" type="button" class="w-full shrink-0 bg-accent hover:bg-accent-hover text-white py-sm rounded-md font-medium transition-colors focus:outline-none focus:ring-2 focus:ring-accent-ring">
                Cerrar Detalles
            </button>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const modal = document.getElementById('receipt-modal');
    const backdrop = document.getElementById('receipt-modal-backdrop');
    const panel = document.getElementById('receipt-modal-panel');
    const btnClose = document.getElementById('btn-close-receipt');

    function openModal() {
        modal.style.removeProperty('display');
        void modal.offsetWidth; // force reflow
        backdrop.style.opacity = '1';
        panel.style.opacity = '1';
        panel.style.transform = 'scale(1) translateY(0)';
        document.body.style.overflow = 'hidden';
        btnClose.focus();
    }

    function closeModal() {
        backdrop.style.opacity = '0';
        panel.style.opacity = '0';
        panel.style.transform = 'scale(0.95) translateY(-8px)';
        document.body.style.overflow = '';
        setTimeout(() => {
            modal.style.display = 'none';
        }, 200);
    }

    btnClose.addEventListener('click', closeModal);
    backdrop.addEventListener('click', closeModal);
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape' && modal.style.display !== 'none') closeModal();
    });

    document.querySelectorAll('.btn-view-details').forEach(btn => {
        btn.addEventListener('click', (e) => {
            const saleData = JSON.parse(e.currentTarget.dataset.sale);
            
            document.getElementById('receipt-sale-id').textContent = saleData.id;
            document.getElementById('receipt-client').textContent = saleData.client;
            document.getElementById('receipt-date').textContent = saleData.date;
            document.getElementById('receipt-total').textContent = saleData.total;
            
            const receiptItems = document.getElementById('receipt-items');
            receiptItems.innerHTML = '';
            
            saleData.details.forEach(item => {
                const div = document.createElement('div');
                div.className = 'flex justify-between text-ink-soft';
                div.innerHTML = `<span class="truncate pr-2">${item.quantity}x ${item.name}</span><span class="whitespace-nowrap font-medium text-ink">${item.subtotal}</span>`;
                receiptItems.appendChild(div);
            });
            
            openModal();
        });
    });
});
</script>
@endsection
