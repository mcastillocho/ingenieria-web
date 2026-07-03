@extends('layout.base')

@section('title', 'Productos — Ferretería Abad')
@section('pageTitle', 'Productos')

@section('content')
<div class="flex flex-col gap-lg">

    {{-- ── Encabezado ── --}}
    <div class="flex items-center justify-between gap-md flex-wrap">
        <div>
            <h1 class="text-xl font-bold text-ink">Productos</h1>
            <p class="text-sm text-ink-soft mt-xs">Gestiona los productos del inventario y su información.</p>
        </div>
        <x-ui.button id="btn-nuevo-producto" variant="primary" type="button">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                 stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/>
            </svg>
            Nuevo producto
        </x-ui.button>
    </div>

    {{-- ── Flash messages ── --}}
    @if(session('success'))
        <x-ui.alert variant="success">{{ session('success') }}</x-ui.alert>
    @endif
    @if($errors->any())
        <x-ui.alert variant="danger">
            <ul class="list-disc list-inside space-y-1">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </x-ui.alert>
    @endif

    {{-- ── Tarjetas de resumen ── --}}
    @php
        $totalProducts = $products->count();
        $okStock       = $products->filter(fn($p) => $p->batches_sum_current_stock > 10)->count();
        $lowStock      = $products->filter(fn($p) => $p->batches_sum_current_stock > 0 && $p->batches_sum_current_stock <= 10)->count();
        $outOfStock    = $products->filter(fn($p) => $p->batches_sum_current_stock == 0)->count();
    @endphp

    <div class="grid gap-md" style="grid-template-columns: repeat(auto-fit, minmax(160px, 1fr));">
        <x-ui.card variant="stats">
            <div class="px-md py-md flex flex-col gap-xs">
                <span class="text-xs text-muted uppercase tracking-wide font-medium">Total Productos</span>
                <span class="text-2xl font-bold text-ink">{{ $totalProducts }}</span>
            </div>
        </x-ui.card>
        <x-ui.card variant="stats">
            <div class="px-md py-md flex flex-col gap-xs">
                <span class="text-xs text-muted uppercase tracking-wide font-medium">Stock OK</span>
                <span class="text-2xl font-bold text-stock-ok">{{ $okStock }}</span>
            </div>
        </x-ui.card>
        <x-ui.card variant="stats">
            <div class="px-md py-md flex flex-col gap-xs">
                <span class="text-xs text-muted uppercase tracking-wide font-medium">Stock Bajo</span>
                <span class="text-2xl font-bold text-stock-low">{{ $lowStock }}</span>
            </div>
        </x-ui.card>
        <x-ui.card variant="stats">
            <div class="px-md py-md flex flex-col gap-xs">
                <span class="text-xs text-muted uppercase tracking-wide font-medium">Agotado</span>
                <span class="text-2xl font-bold text-stock-out">{{ $outOfStock }}</span>
            </div>
        </x-ui.card>
    </div>

    {{-- ── Tabla de productos ── --}}
    <x-ui.data-table>
        <x-slot name="header">
            <tr>
                <th class="px-md py-sm">#</th>
                <th class="px-md py-sm">Nombre del producto</th>
                <th class="px-md py-sm">Categoría</th>
                <th class="px-md py-sm text-right">Precio Venta</th>
                <th class="px-md py-sm text-right">Stock Total</th>
                <th class="px-md py-sm text-center">Estado</th>
                <th class="px-md py-sm text-center">Acciones</th>
            </tr>
        </x-slot>

        @forelse($products as $product)
            @php
                $stockTotal = $product->batches_sum_current_stock ?? 0;
                $stockVariant = match(true) {
                    $stockTotal > 10 => 'ok',
                    $stockTotal >= 1 => 'low',
                    default          => 'out',
                };
                $stockLabel = match($stockVariant) {
                    'ok'  => 'OK',
                    'low' => 'Bajo',
                    'out' => 'Agotado',
                };
            @endphp
            <tr
                class="product-row"
                data-id="{{ $product->id }}"
                data-category="{{ $product->product_category_id }}"
                data-name="{{ $product->name }}"
                data-sale-price="{{ $product->sale_price }}"
                data-description="{{ $product->description }}"
            >
                <td class="px-md py-sm text-muted text-xs font-mono">{{ $product->id }}</td>
                <td class="px-md py-sm font-medium text-ink">{{ $product->name }}</td>
                <td class="px-md py-sm text-ink-soft text-sm">{{ $product->productCategory->name ?? '—' }}</td>
                <td class="px-md py-sm text-right text-ink">S/ {{ number_format($product->sale_price, 2) }}</td>
                <td class="px-md py-sm text-right font-semibold text-ink">{{ number_format($stockTotal) }}</td>
                <td class="px-md py-sm text-center">
                    <x-ui.badge variant="{{ $stockVariant }}">{{ $stockLabel }}</x-ui.badge>
                </td>
                <td class="px-md py-sm text-center">
                    <button
                        type="button"
                        class="btn-editar-producto text-accent hover:text-accent-hover text-sm font-medium transition-colors"
                        aria-label="Editar producto {{ $product->id }}"
                    >
                        Editar
                    </button>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="7" class="px-md py-lg text-center text-muted text-sm">
                    No hay productos registrados todavía.
                </td>
            </tr>
        @endforelse
    </x-ui.data-table>

</div>

{{-- ══════════════════════════════════════════════════════════════
     Panel lateral — Crear / Editar producto
══════════════════════════════════════════════════════════════ --}}

{{-- Overlay --}}
<div
    id="product-panel-overlay"
    class="fixed inset-0 z-40"
    style="display:none; background: rgba(15,23,42,0.45); backdrop-filter: blur(2px); opacity:0; transition: opacity 0.25s ease;"
></div>

{{-- Panel --}}
<aside
    id="product-panel"
    aria-label="Formulario de producto"
    class="fixed top-0 right-0 h-full z-50 bg-surface border-l border-line flex flex-col"
    style="width: 420px; max-width: 100vw; transform: translateX(100%); transition: transform 0.28s cubic-bezier(0.4,0,0.2,1); box-shadow: var(--shadow-elevated);"
>
    {{-- Header --}}
    <div class="flex items-center justify-between px-lg py-md border-b border-line bg-canvas-alt flex-shrink-0">
        <h2 id="product-panel-title" class="font-semibold text-ink text-base">Nuevo producto</h2>
        <button id="product-panel-close" type="button"
                class="text-muted hover:text-ink transition-colors p-xs rounded-md hover:bg-line"
                aria-label="Cerrar panel">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                 stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/>
            </svg>
        </button>
    </div>

    {{-- Formulario --}}
    <div class="flex-1 overflow-y-auto px-lg py-lg">
        <form id="product-form" method="POST"
              action="{{ route('productos.store') }}"
              data-store-url="{{ route('productos.store') }}"
              class="flex flex-col gap-md">
            @csrf
            <input type="hidden" name="_method" id="product-form-method" value="POST">

            {{-- Categoría con toggle --}}
            <div id="mode-cat-existing">
                <div class="flex items-center justify-between mb-xs">
                    <label for="product_category_id" class="block text-sm font-medium text-ink">Categoría</label>
                    <button type="button" id="btn-switch-new-cat"
                            class="text-xs text-accent hover:text-accent-hover font-medium transition-colors">
                        + Nueva categoría
                    </button>
                </div>
                <select id="product_category_id" name="product_category_id" required
                        class="w-full rounded-md border border-line bg-white px-md py-sm text-ink
                               focus:border-accent focus:ring-2 focus:ring-accent-ring focus:outline-none">
                    <option value="">— Selecciona categoría —</option>
                    @foreach($categories as $cid => $cname)
                        <option value="{{ $cid }}">{{ $cname }}</option>
                    @endforeach
                </select>
            </div>

            <div id="mode-cat-new" style="display:none;" class="flex flex-col gap-md">
                <div class="flex items-center justify-between">
                    <span class="text-sm font-semibold text-ink">Nueva categoría</span>
                    <button type="button" id="btn-switch-existing-cat"
                            class="text-xs text-muted hover:text-ink-soft font-medium transition-colors">
                        ← Usar existente
                    </button>
                </div>
                <x-forms.input
                    label="Nombre de la categoría"
                    name="new_category_name"
                    placeholder="Ej: Equipos de Seguridad"
                />
                <div class="flex items-center gap-sm pt-xs">
                    <div class="flex-1 border-t border-accent" style="opacity:0.35;"></div>
                    <span class="text-xs text-accent font-medium" style="opacity:0.6;">Nueva categoría</span>
                    <div class="flex-1 border-t border-accent" style="opacity:0.35;"></div>
                </div>
            </div>

            <x-forms.input
                label="Nombre del producto"
                name="name"
                placeholder="Ej: Taladro Percutor 800W"
                required
            />

            <x-forms.input
                label="Precio de venta (S/)"
                name="sale_price"
                type="number"
                placeholder="Ej: 199.90"
                required
            />

            <div class="w-full space-y-2">
                <label for="description" class="block text-sm font-medium text-ink">
                    Descripción <span class="text-muted font-normal">(opcional)</span>
                </label>
                <textarea id="description" name="description"
                          rows="3" placeholder="Descripción breve del producto…"
                          class="w-full rounded-md border border-line bg-white px-md py-sm text-ink text-sm
                                 focus:border-accent focus:ring-2 focus:ring-accent-ring focus:outline-none
                                 resize-none"></textarea>
            </div>

        </form>
    </div>

    {{-- Footer --}}
    <div class="flex gap-sm px-lg py-md border-t border-line bg-canvas-alt flex-shrink-0">
        <x-ui.button id="product-cancel-btn" variant="secondary" type="button" class="flex-1">
            Cancelar
        </x-ui.button>
        <x-ui.button id="product-submit-btn" variant="primary" type="button" class="flex-1">
            Guardar producto
        </x-ui.button>
    </div>
</aside>

<script>
(function () {
    // ── Elementos ──────────────────────────────────────────────────────────
    const panel        = document.getElementById('product-panel');
    const overlay      = document.getElementById('product-panel-overlay');
    const panelTitle   = document.getElementById('product-panel-title');
    const form         = document.getElementById('product-form');
    const formMethod   = document.getElementById('product-form-method');
    const btnNuevo     = document.getElementById('btn-nuevo-producto');
    const btnClose     = document.getElementById('product-panel-close');
    const btnCancel    = document.getElementById('product-cancel-btn');
    const btnSubmit    = document.getElementById('product-submit-btn');

    // Campos
    const inputCatSelect = document.getElementById('product_category_id');
    const inputName      = document.getElementById('name');
    const inputSalePrice = document.getElementById('sale_price');
    const inputDesc      = document.getElementById('description');

    // Toggle Categoría
    const modeCatExisting    = document.getElementById('mode-cat-existing');
    const modeCatNew         = document.getElementById('mode-cat-new');
    const btnSwitchNewCat    = document.getElementById('btn-switch-new-cat');
    const btnSwitchExistCat  = document.getElementById('btn-switch-existing-cat');
    const inputNewCatName    = document.getElementById('new_category_name');
    let isNewCategoryMode = false;

    const storeUrl = form.getAttribute('data-store-url');

    function setNewCategoryMode(active) {
        isNewCategoryMode = active;
        if (active) {
            modeCatExisting.style.display = 'none';
            modeCatNew.style.display      = 'flex';
            inputCatSelect.removeAttribute('required');
            inputNewCatName.setAttribute('required', '');
        } else {
            modeCatExisting.style.display = 'block';
            modeCatNew.style.display      = 'none';
            inputNewCatName.removeAttribute('required');
            inputCatSelect.setAttribute('required', '');
        }
    }

    btnSwitchNewCat.addEventListener('click',   () => setNewCategoryMode(true));
    btnSwitchExistCat.addEventListener('click', () => setNewCategoryMode(false));

    // ── Abrir / cerrar ─────────────────────────────────────────────────────
    function openPanel() {
        overlay.style.display = 'block';
        void overlay.offsetWidth;
        overlay.style.opacity = '1';
        panel.style.transform = 'translateX(0)';
        document.body.style.overflow = 'hidden';
    }

    function closePanel() {
        overlay.style.opacity = '0';
        panel.style.transform = 'translateX(100%)';
        document.body.style.overflow = '';
        setTimeout(() => { overlay.style.display = 'none'; }, 260);
    }

    // ── Resetear a modo "crear" ────────────────────────────────────────────
    function resetForm() {
        panelTitle.textContent = 'Nuevo producto';
        btnSubmit.textContent  = 'Guardar producto';
        form.action        = storeUrl;
        formMethod.value   = 'POST';
        inputCatSelect.value = '';
        inputName.value      = '';
        inputSalePrice.value = '';
        inputDesc.value      = '';
        setNewCategoryMode(false);
        inputNewCatName.value = '';
        btnSwitchNewCat.style.display = 'inline';
    }

    // ── Cargar datos para edición ──────────────────────────────────────────
    function loadProduct(row) {
        const id = row.dataset.id;
        panelTitle.textContent = 'Editar producto #' + id;
        btnSubmit.textContent  = 'Actualizar producto';
        form.action        = storeUrl + '/' + id;
        formMethod.value   = 'PUT';
        inputCatSelect.value = row.dataset.category || '';
        inputName.value      = row.dataset.name     || '';
        inputSalePrice.value = row.dataset.salePrice|| '';
        inputDesc.value      = row.dataset.description || '';
        setNewCategoryMode(false);
        btnSwitchNewCat.style.display = 'none';
    }

    // ── Listeners ─────────────────────────────────────────────────────────
    btnNuevo.addEventListener('click', () => { resetForm(); openPanel(); });
    btnClose.addEventListener('click', closePanel);
    btnCancel.addEventListener('click', closePanel);
    overlay.addEventListener('click', closePanel);

    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') closePanel();
    });

    document.querySelectorAll('.btn-editar-producto').forEach(btn => {
        btn.addEventListener('click', () => {
            const row = btn.closest('.product-row');
            loadProduct(row);
            openPanel();
        });
    });

    btnSubmit.addEventListener('click', () => { form.submit(); });
})();
</script>

@endsection