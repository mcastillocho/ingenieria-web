@extends('layout.base')

@section('title', 'Nueva Venta — Ferretería Abad')
@section('pageTitle', 'Punto de Venta')

@section('content')
<div class="flex flex-col lg:flex-row gap-lg min-h-[calc(100vh-120px)] lg:min-h-0 lg:h-[calc(100vh-120px)]">
    
    {{-- ── Panel Izquierdo: Catálogo de Productos ── --}}
    <div class="flex-1 flex flex-col min-h-0 bg-surface rounded-xl border border-line shadow-sm overflow-visible lg:overflow-hidden relative z-20">
        
        {{-- Buscador y filtros --}}
        <div class="p-md border-b border-line bg-canvas-alt flex items-center gap-md">
            <div class="relative flex-1">
                <svg class="absolute left-3 top-1/2 -translate-y-1/2 text-muted" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                </svg>
                <input type="text" id="pos-search" placeholder="Buscar productos por nombre o categoría..." 
                       class="w-full pl-10 pr-4 py-sm rounded-md border border-line bg-white text-ink text-sm focus:border-accent focus:ring-2 focus:ring-accent-ring focus:outline-none transition-shadow" autocomplete="off">
                {{-- Dropdown de resultados móviles --}}
                <ul id="pos-search-results" class="hidden absolute top-full left-0 w-full bg-white border border-line rounded-md mt-1 shadow-lg z-50 max-h-60 overflow-y-auto divide-y divide-line lg:hidden">
                </ul>
            </div>
        </div>

        {{-- Grilla de Productos (Solo Desktop) --}}
        <div class="flex-1 overflow-y-auto p-md hidden lg:block">
            <div class="grid gap-md" style="grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));" id="pos-products-grid">
                @forelse($batches as $batch)
                    <div class="pos-product-card cursor-pointer h-full transition-transform hover:-translate-y-1"
                         data-id="{{ $batch->id }}"
                         data-name="{{ $batch->product->name }} ({{ $batch->supplier->name ?? 'Sin Prov.' }})"
                         data-price="{{ $batch->product->sale_price }}"
                         data-stock="{{ $batch->current_stock }}">
                        
                        <x-ui.product-card>
                            <x-slot name="title">{{ $batch->product->name }}</x-slot>
                            <x-slot name="subtitle">{{ $batch->supplier->name ?? 'Sin Proveedor' }}</x-slot>
                            <x-slot name="description">{{ $batch->product->productCategory->name ?? 'Varios' }}</x-slot>
                            <x-slot name="price"><x-ui.price>S/ {{ number_format($batch->product->sale_price, 2) }}</x-ui.price></x-slot>
                            <x-slot name="stockText">Stock: {{ $batch->current_stock }}</x-slot>
                            <x-slot name="stockBadge"><x-ui.badge variant="ok">Disponible</x-ui.badge></x-slot>
                            <x-slot name="footer">
                                <x-ui.button class="w-full pointer-events-none" variant="secondary" size="sm">Agregar al carrito</x-ui.button>
                            </x-slot>
                        </x-ui.product-card>

                    </div>
                @empty
                    <div class="col-span-full py-xl text-center text-muted">
                        No hay lotes con stock disponible.
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    {{-- ── Panel Derecho: Carrito de Compras ── --}}
    <div class="w-full lg:w-96 flex flex-col min-h-0 bg-surface rounded-xl border border-line shadow-sm overflow-hidden flex-shrink-0 z-10" style="min-height: 400px;">
        
        {{-- Cliente --}}
        <div class="p-md border-b border-line bg-canvas-alt">
            <h2 class="font-bold text-ink mb-xs">Datos de la Venta</h2>
            <div class="flex flex-col gap-xs">
                <select id="client-select" class="w-full text-sm rounded-md border border-line bg-white px-md py-sm text-ink focus:border-accent focus:ring-2 focus:outline-none">
                    <option value="">Venta Libre (Cliente General)</option>
                    @foreach($clients as $client)
                        <option value="{{ $client->id }}">{{ $client->name }} {{ $client->lastname }} ({{ $client->document_number }})</option>
                    @endforeach
                    <option value="NEW">+ Crear nuevo cliente...</option>
                </select>
                <div id="new-client-wrapper" style="display:none;" class="mt-xs">
                    <input type="text" id="new-client-name" placeholder="Nombre del nuevo cliente" class="w-full text-sm rounded-md border border-line bg-white px-md py-sm text-ink focus:border-accent focus:ring-2 focus:outline-none">
                </div>
            </div>
        </div>

        {{-- Lista de Items --}}
        <div class="flex-1 overflow-y-auto p-md" id="cart-items-container">
            {{-- Los items del carrito se renderizan aquí con JS --}}
            <div id="empty-cart-msg" class="h-full flex flex-col items-center justify-center text-muted opacity-60">
                <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="mb-sm">
                    <circle cx="9" cy="21" r="1"></circle><circle cx="20" cy="21" r="1"></circle>
                    <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path>
                </svg>
                <span class="text-sm">El carrito está vacío</span>
            </div>
        </div>

        {{-- Totales y Confirmar --}}
        <div class="p-md border-t border-line bg-canvas-alt space-y-sm">
            <div class="flex justify-between text-sm text-ink-soft">
                <span>Subtotal</span>
                <span id="pos-subtotal">S/ 0.00</span>
            </div>
            <div class="flex justify-between text-sm text-ink-soft">
                <span>IGV (18%)</span>
                <span id="pos-igv">S/ 0.00</span>
            </div>
            <div class="pt-sm border-t border-line border-dashed flex justify-between items-end">
                <span class="font-bold text-ink">Total a Cobrar</span>
                <span id="pos-total" class="text-2xl font-bold text-accent">S/ 0.00</span>
            </div>
            <x-ui.button id="btn-confirm-sale" variant="primary" class="w-full mt-md py-sm text-base" type="button" disabled>
                Confirmar Venta
            </x-ui.button>
        </div>
    </div>
</div>

{{-- ── Modal de Boleta ── --}}
<div id="receipt-modal" class="fixed inset-0 z-50 flex items-center justify-center p-md" style="display: none;">
    <div id="receipt-modal-backdrop" class="absolute inset-0 bg-ink/50 backdrop-blur-sm opacity-0 transition-opacity duration-200"></div>
    
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
                <h3 class="text-xl font-bold text-ink">Venta Confirmada</h3>
                <p class="text-sm text-ink-soft mt-1">Ticket #<span id="receipt-sale-id" class="font-mono font-bold"></span></p>
            </div>
            
            <div class="border-t border-b border-line py-sm mb-md text-sm shrink-0">
                <div class="flex justify-between mb-1">
                    <span class="text-muted">Cliente:</span>
                    <span class="font-medium text-ink truncate max-w-[180px]" id="receipt-client"></span>
                </div>
                <div class="flex justify-between mb-1">
                    <span class="text-muted">Fecha:</span>
                    <span class="font-medium text-ink">{{ date('d/m/Y H:i') }}</span>
                </div>
            </div>

            <div class="text-sm font-bold text-ink mb-2 shrink-0">Detalle:</div>
            
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
                Cerrar y Nueva Venta
            </button>
        </div>
    </div>
</div>

{{-- Metadata para JS --}}
<meta name="csrf-token" content="{{ csrf_token() }}">
<script>
    const storeUrl = "{{ route('ventas.store') }}";
</script>
<script>
document.addEventListener('DOMContentLoaded', () => {
    // ── Estado del Carrito ──
    const cart = new Map(); // productId => { name, price, quantity, maxStock }

    // ── Elementos DOM ──
    const searchInput = document.getElementById('pos-search');
    const productsGrid = document.getElementById('pos-products-grid');
    const productCards = document.querySelectorAll('.pos-product-card');
    const searchResults = document.getElementById('pos-search-results');
    const cartContainer = document.getElementById('cart-items-container');
    const emptyMsg = document.getElementById('empty-cart-msg');
    const spanSubtotal = document.getElementById('pos-subtotal');
    const spanIgv = document.getElementById('pos-igv');
    const spanTotal = document.getElementById('pos-total');
    const btnConfirm = document.getElementById('btn-confirm-sale');
    
    const clientSelect = document.getElementById('client-select');
    const newClientWrapper = document.getElementById('new-client-wrapper');
    const newClientName = document.getElementById('new-client-name');

    // ── Buscador ──
    searchInput.addEventListener('input', (e) => {
        const term = e.target.value.toLowerCase().trim();
        
        // Logica para desktop (Grid)
        productCards.forEach(card => {
            const text = card.textContent.toLowerCase();
            if (text.includes(term)) {
                card.style.display = 'block'; // Or whatever it was before (it's block by default for wrapping div)
            } else {
                card.style.display = 'none';
            }
        });

        // Logica para móvil (Dropdown)
        if (window.innerWidth < 1024) {
            searchResults.innerHTML = '';
            if (term.length === 0) {
                searchResults.classList.add('hidden');
                return;
            }

            let matches = 0;
            productCards.forEach(card => {
                const text = card.textContent.toLowerCase();
                if (text.includes(term) && matches < 10) { // Limit results to 10
                    matches++;
                    const id = card.dataset.id;
                    const name = card.dataset.name;
                    const price = card.dataset.price;
                    const stock = card.dataset.stock;
                    
                    const li = document.createElement('li');
                    li.className = 'p-sm hover:bg-canvas-alt cursor-pointer flex justify-between items-center';
                    li.innerHTML = `
                        <div class="flex-1 min-w-0 pr-2">
                            <div class="text-sm font-bold text-ink truncate">${name}</div>
                            <div class="text-xs text-muted">Stock: ${stock}</div>
                        </div>
                        <div class="text-accent font-bold text-sm shrink-0 whitespace-nowrap">
                            ${formatMoney(price)}
                        </div>
                    `;
                    
                    li.addEventListener('click', () => {
                        addToCart(id, name, price, stock);
                        searchInput.value = '';
                        searchInput.dispatchEvent(new Event('input')); // Trigger input event to clear dropdown
                    });
                    
                    searchResults.appendChild(li);
                }
            });
            
            if (matches > 0) {
                searchResults.classList.remove('hidden');
            } else {
                searchResults.classList.add('hidden');
            }
        }
    });

    // Ocultar dropdown al hacer click afuera
    document.addEventListener('click', (e) => {
        if (!searchInput.contains(e.target) && !searchResults.contains(e.target)) {
            searchResults.classList.add('hidden');
        }
    });

    // ── Toggle Nuevo Cliente ──
    clientSelect.addEventListener('change', (e) => {
        if (e.target.value === 'NEW') {
            newClientWrapper.style.display = 'block';
            newClientName.focus();
        } else {
            newClientWrapper.style.display = 'none';
        }
    });

    // ── Lógica del Carrito ──
    function formatMoney(amount) {
        return 'S/ ' + parseFloat(amount).toFixed(2);
    }

    function updateCartUI() {
        if (cart.size === 0) {
            cartContainer.innerHTML = '';
            cartContainer.appendChild(emptyMsg);
            spanSubtotal.textContent = formatMoney(0);
            spanIgv.textContent = formatMoney(0);
            spanTotal.textContent = formatMoney(0);
            btnConfirm.disabled = true;
            return;
        }

        cartContainer.innerHTML = '';
        let totalNet = 0;

        cart.forEach((item, id) => {
            const itemTotal = item.price * item.quantity;
            totalNet += itemTotal;

            const div = document.createElement('div');
            div.className = 'flex items-center justify-between p-sm border-b border-line last:border-0 bg-white rounded-md mb-xs shadow-sm';
            
            div.innerHTML = `
                <div class="flex-1 min-w-0 pr-sm">
                    <h4 class="text-sm font-bold text-ink truncate">${item.name}</h4>
                    <span class="text-xs text-muted">${formatMoney(item.price)} x ${item.quantity}</span>
                </div>
                <div class="flex items-center gap-xs">
                    <button type="button" class="btn-minus w-7 h-7 flex items-center justify-center bg-canvas-alt border border-line rounded-md text-ink hover:bg-line transition-colors" data-id="${id}">-</button>
                    <span class="text-sm font-semibold w-6 text-center">${item.quantity}</span>
                    <button type="button" class="btn-plus w-7 h-7 flex items-center justify-center bg-canvas-alt border border-line rounded-md text-ink hover:bg-line transition-colors" data-id="${id}">+</button>
                </div>
            `;
            cartContainer.appendChild(div);
        });

        // Cálculos (Asumiendo precios con IGV incluido, desglosamos)
        // O asumiendo precios netos y sumamos IGV. Elegiremos IGV incluido para simplificar (Total = Subtotal).
        // Peru IGV is 18%. So Net = Total / 1.18. Tax = Total - Net.
        const total = totalNet;
        const net = total / 1.18;
        const taxes = total - net;

        spanSubtotal.textContent = formatMoney(net);
        spanIgv.textContent = formatMoney(taxes);
        spanTotal.textContent = formatMoney(total);
        btnConfirm.disabled = false;
        
        // Asignar listeners a botones +/-
        document.querySelectorAll('.btn-minus').forEach(btn => {
            btn.addEventListener('click', (e) => updateQuantity(e.currentTarget.dataset.id, -1));
        });
        document.querySelectorAll('.btn-plus').forEach(btn => {
            btn.addEventListener('click', (e) => updateQuantity(e.currentTarget.dataset.id, 1));
        });
    }

    function addToCart(id, name, price, maxStock) {
        if (cart.has(id)) {
            updateQuantity(id, 1);
        } else {
            if (maxStock > 0) {
                cart.set(id, { name, price: parseFloat(price), quantity: 1, maxStock: parseInt(maxStock) });
                updateCartUI();
            } else {
                alert('Sin stock disponible');
            }
        }
    }

    function updateQuantity(id, change) {
        const item = cart.get(id);
        if (!item) return;

        const newQty = item.quantity + change;
        if (newQty <= 0) {
            cart.delete(id);
        } else if (newQty > item.maxStock) {
            alert('Stock máximo alcanzado');
        } else {
            item.quantity = newQty;
        }
        updateCartUI();
    }

    // Agregar desde el catálogo
    productCards.forEach(card => {
        card.addEventListener('click', () => {
            addToCart(
                card.dataset.id,
                card.dataset.name,
                card.dataset.price,
                card.dataset.stock
            );
        });
    });

    // ── Confirmar Venta ──
    btnConfirm.addEventListener('click', async () => {
        if (cart.size === 0) return;

        btnConfirm.disabled = true;
        btnConfirm.textContent = 'Procesando...';

        let total = 0;
        const items = [];
        cart.forEach((item, id) => {
            total += (item.price * item.quantity);
            items.push({
                batch_id: id,
                quantity: item.quantity,
                price: item.price
            });
        });

        const net = total / 1.18;
        const taxes = total - net;

        let clientId = clientSelect.value;
        let newName = '';
        if (clientId === 'NEW') {
            clientId = '';
            newName = newClientName.value.trim();
            if (!newName) {
                alert('Debe ingresar el nombre del nuevo cliente');
                btnConfirm.disabled = false;
                btnConfirm.textContent = 'Confirmar Venta';
                return;
            }
        }

        const payload = {
            client_id: clientId,
            new_client_name: newName,
            items: items,
            total_net: net.toFixed(2),
            total_taxes: taxes.toFixed(2),
            total_amount: total.toFixed(2)
        };

        try {
            const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            const response = await fetch(storeUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': token
                },
                body: JSON.stringify(payload)
            });

            const data = await response.json();

            if (response.ok) {
                // Llenar Modal
                document.getElementById('receipt-sale-id').textContent = String(data.sale_id).padStart(6, '0');
                document.getElementById('receipt-client').textContent = data.client_name;
                
                const receiptItems = document.getElementById('receipt-items');
                receiptItems.innerHTML = '';
                cart.forEach((item) => {
                    const div = document.createElement('div');
                    div.className = 'flex justify-between text-ink-soft';
                    div.innerHTML = `<span class="truncate pr-2">${item.quantity}x ${item.name}</span><span class="whitespace-nowrap font-medium text-ink">${formatMoney(item.price * item.quantity)}</span>`;
                    receiptItems.appendChild(div);
                });
                
                document.getElementById('receipt-total').textContent = formatMoney(total);
                
                // Mostrar Modal
                const modal = document.getElementById('receipt-modal');
                const backdrop = document.getElementById('receipt-modal-backdrop');
                const panel = document.getElementById('receipt-modal-panel');
                
                modal.style.removeProperty('display');
                void modal.offsetWidth; // force reflow
                backdrop.style.opacity = '1';
                panel.style.opacity = '1';
                panel.style.transform = 'scale(1) translateY(0)';
                document.body.style.overflow = 'hidden';
                
                const btnClose = document.getElementById('btn-close-receipt');
                btnClose.focus();
                
                btnClose.addEventListener('click', () => {
                    document.body.style.overflow = '';
                    window.location.reload();
                });
            } else {
                alert(data.message || 'Error al procesar la venta');
                btnConfirm.disabled = false;
                btnConfirm.textContent = 'Confirmar Venta';
            }
        } catch (error) {
            console.error(error);
            alert('Error de conexión');
            btnConfirm.disabled = false;
            btnConfirm.textContent = 'Confirmar Venta';
        }
    });

});
</script>
@endsection
