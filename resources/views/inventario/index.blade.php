@extends('layout.base')

@section('title', 'Inventario — Ferretería Abad')
@section('pageTitle', 'Inventario de Lotes')

@section('content')
<div class="flex flex-col gap-lg">

    {{-- ── Encabezado ── --}}
    <div class="flex items-center justify-between gap-md flex-wrap">
        <div>
            <h1 class="text-xl font-bold text-ink">Lotes de inventario</h1>
            <p class="text-sm text-ink-soft mt-xs">Gestiona los lotes de productos por proveedor.</p>
        </div>
        <x-ui.button id="btn-nuevo-lote" variant="primary" type="button">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                 stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/>
            </svg>
            Nuevo lote
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
    <div class="grid gap-md" style="grid-template-columns: repeat(auto-fit, minmax(160px, 1fr));">
        @php
            $total  = $batches->count();
            $ok     = $batches->filter(fn($b) => $b->current_stock > 10)->count();
            $low    = $batches->filter(fn($b) => $b->current_stock >= 1 && $b->current_stock <= 10)->count();
            $out    = $batches->filter(fn($b) => $b->current_stock === 0)->count();
        @endphp

        <x-ui.card variant="stats">
            <div class="px-md py-md flex flex-col gap-xs">
                <span class="text-xs text-muted uppercase tracking-wide font-medium">Total lotes</span>
                <span class="text-2xl font-bold text-ink">{{ $total }}</span>
            </div>
        </x-ui.card>

        <x-ui.card variant="stats">
            <div class="px-md py-md flex flex-col gap-xs">
                <span class="text-xs text-muted uppercase tracking-wide font-medium">Stock OK</span>
                <span class="text-2xl font-bold text-stock-ok">{{ $ok }}</span>
            </div>
        </x-ui.card>

        <x-ui.card variant="stats">
            <div class="px-md py-md flex flex-col gap-xs">
                <span class="text-xs text-muted uppercase tracking-wide font-medium">Stock bajo</span>
                <span class="text-2xl font-bold text-stock-low">{{ $low }}</span>
            </div>
        </x-ui.card>

        <x-ui.card variant="stats">
            <div class="px-md py-md flex flex-col gap-xs">
                <span class="text-xs text-muted uppercase tracking-wide font-medium">Sin stock</span>
                <span class="text-2xl font-bold text-stock-out">{{ $out }}</span>
            </div>
        </x-ui.card>
    </div>

    {{-- ── Tabla de lotes ── --}}
    <x-ui.data-table>
        <x-slot name="header">
            <tr>
                <th class="px-md py-sm">#</th>
                <th class="px-md py-sm">Producto</th>
                <th class="px-md py-sm">Categoría</th>
                <th class="px-md py-sm">Proveedor</th>
                <th class="px-md py-sm text-right">Stock inicial</th>
                <th class="px-md py-sm text-right">Stock actual</th>
                <th class="px-md py-sm text-right">Precio compra</th>
                <th class="px-md py-sm text-center">Estado</th>
                <th class="px-md py-sm text-center">Acciones</th>
            </tr>
        </x-slot>

        @forelse($batches as $batch)
            @php
                $stockVariant = match(true) {
                    $batch->current_stock > 10 => 'ok',
                    $batch->current_stock >= 1  => 'low',
                    default                     => 'out',
                };
                $stockLabel = match($stockVariant) {
                    'ok'  => 'OK',
                    'low' => 'Bajo',
                    'out' => 'Agotado',
                };
            @endphp
            <tr
                class="batch-row"
                data-id="{{ $batch->id }}"
                data-product="{{ $batch->product_id }}"
                data-supplier="{{ $batch->supplier_id }}"
                data-initial="{{ $batch->initial_stock }}"
                data-current="{{ $batch->current_stock }}"
                data-price="{{ $batch->purchase_price }}"
            >
                <td class="px-md py-sm text-muted text-xs font-mono">{{ $batch->id }}</td>
                <td class="px-md py-sm font-medium text-ink">{{ $batch->product->name ?? '—' }}</td>
                <td class="px-md py-sm text-ink-soft text-sm">{{ $batch->product->category->name ?? '—' }}</td>
                <td class="px-md py-sm text-ink-soft text-sm">{{ $batch->supplier->name ?? '—' }}</td>
                <td class="px-md py-sm text-right text-ink">{{ number_format($batch->initial_stock) }}</td>
                <td class="px-md py-sm text-right font-semibold text-ink">{{ number_format($batch->current_stock) }}</td>
                <td class="px-md py-sm text-right text-ink">S/ {{ number_format($batch->purchase_price, 2) }}</td>
                <td class="px-md py-sm text-center">
                    <x-ui.badge variant="{{ $stockVariant }}">{{ $stockLabel }}</x-ui.badge>
                </td>
                <td class="px-md py-sm text-center">
                    <button
                        type="button"
                        class="btn-editar-lote text-accent hover:text-accent-hover text-sm font-medium transition-colors"
                        aria-label="Editar lote {{ $batch->id }}"
                    >
                        Editar
                    </button>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="9" class="px-md py-lg text-center text-muted text-sm">
                    No hay lotes registrados todavía.
                </td>
            </tr>
        @endforelse
    </x-ui.data-table>

</div>

{{-- ══════════════════════════════════════════════════════════════
     Panel lateral — Crear / Editar lote
══════════════════════════════════════════════════════════════ --}}

{{-- Overlay --}}
<div
    id="panel-overlay"
    class="fixed inset-0 z-40"
    style="display:none; background: rgba(15,23,42,0.45); backdrop-filter: blur(2px); opacity:0; transition: opacity 0.25s ease;"
></div>

{{-- Panel --}}
<aside
    id="batch-panel"
    aria-label="Formulario de lote"
    class="fixed top-0 right-0 h-full z-50 bg-surface border-l border-line flex flex-col"
    style="width: 420px; max-width: 100vw; transform: translateX(100%); transition: transform 0.28s cubic-bezier(0.4,0,0.2,1); box-shadow: var(--shadow-elevated);"
>
    {{-- Header del panel --}}
    <div class="flex items-center justify-between px-lg py-md border-b border-line bg-canvas-alt flex-shrink-0">
        <h2 id="panel-title" class="font-semibold text-ink text-base">Nuevo lote</h2>
        <button id="panel-close-btn" type="button"
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
        <form id="batch-form" method="POST"
              action="{{ route('inventario.store') }}"
              data-store-url="{{ route('inventario.store') }}"
              class="flex flex-col gap-md">
            @csrf
            <input type="hidden" name="_method" id="form-method" value="POST">
            <input type="hidden" name="batch_id"  id="form-batch-id" value="">

            {{-- ── Selector de producto (modo: existente) ── --}}
            <div id="mode-existing">
                <div class="flex items-center justify-between mb-xs">
                    <label for="product_id" class="block text-sm font-medium text-ink">Producto</label>
                    <button type="button" id="btn-switch-new"
                            class="text-xs text-accent hover:text-accent-hover font-medium transition-colors">
                        + Crear nuevo producto
                    </button>
                </div>
                <select id="product_id" name="product_id" required
                        class="w-full rounded-md border border-line bg-white px-md py-sm text-ink
                               focus:border-accent focus:ring-2 focus:ring-accent-ring focus:outline-none">
                    <option value="">— Selecciona un producto —</option>
                    @foreach($products as $pid => $pname)
                        <option value="{{ $pid }}">{{ $pname }}</option>
                    @endforeach
                </select>
            </div>

            {{-- ── Mini-formulario de nuevo producto (modo: nuevo) ── --}}
            <div id="mode-new" style="display:none;" class="flex flex-col gap-md"
                 style="border-left: 3px solid var(--color-accent); padding-left: var(--spacing-md);">

                <div class="flex items-center justify-between">
                    <span class="text-sm font-semibold text-ink">Nuevo producto</span>
                    <button type="button" id="btn-switch-existing"
                            class="text-xs text-muted hover:text-ink-soft font-medium transition-colors">
                        ← Usar existente
                    </button>
                </div>

                {{-- Categoría con toggle --}}
                <div id="mode-cat-existing">
                    <div class="flex items-center justify-between mb-xs">
                        <label for="new_product_category_id" class="block text-sm font-medium text-ink">Categoría</label>
                        <button type="button" id="btn-switch-new-cat"
                                class="text-xs text-accent hover:text-accent-hover font-medium transition-colors">
                            + Nueva categoría
                        </button>
                    </div>
                    <select id="new_product_category_id" name="new_product_category_id"
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
                    name="new_product_name"
                    placeholder="Ej: Taladro Percutor 800W"
                />

                <x-forms.input
                    label="Precio de venta (S/)"
                    name="new_product_sale_price"
                    type="number"
                    placeholder="Ej: 199.90"
                />

                <div class="w-full space-y-2">
                    <label for="new_product_description" class="block text-sm font-medium text-ink">
                        Descripción <span class="text-muted font-normal">(opcional)</span>
                    </label>
                    <textarea id="new_product_description" name="new_product_description"
                              rows="2" placeholder="Descripción breve del producto…"
                              class="w-full rounded-md border border-line bg-white px-md py-sm text-ink text-sm
                                     focus:border-accent focus:ring-2 focus:ring-accent-ring focus:outline-none
                                     resize-none"></textarea>
                </div>
                <div class="flex items-center gap-sm pt-xs">
                    <div class="flex-1 border-t border-accent" style="opacity:0.35;"></div>
                    <span class="text-xs text-accent font-medium" style="opacity:0.6;">Nuevo producto</span>
                    <div class="flex-1 border-t border-accent" style="opacity:0.35;"></div>
                </div>
            </div>

            {{-- ── Separador visual entre producto y lote ── --}}
            <hr class="border-line">

            {{-- ── Selector de proveedor (modo: existente) ── --}}
            <div id="mode-supplier-existing">
                <div class="flex items-center justify-between mb-xs">
                    <label for="supplier_id" class="block text-sm font-medium text-ink">Proveedor</label>
                    <button type="button" id="btn-switch-new-supplier"
                            class="text-xs text-accent hover:text-accent-hover font-medium transition-colors">
                        + Crear nuevo proveedor
                    </button>
                </div>
                <select id="supplier_id" name="supplier_id" required
                        class="w-full rounded-md border border-line bg-white px-md py-sm text-ink
                               focus:border-accent focus:ring-2 focus:ring-accent-ring focus:outline-none">
                    <option value="">— Selecciona un proveedor —</option>
                    @foreach($suppliers as $sid => $sname)
                        <option value="{{ $sid }}">{{ $sname }}</option>
                    @endforeach
                </select>
            </div>

            {{-- ── Mini-formulario de nuevo proveedor (modo: nuevo) ── --}}
            <div id="mode-supplier-new" style="display:none;" class="flex flex-col gap-md">

                <div class="flex items-center justify-between">
                    <span class="text-sm font-semibold text-ink">Nuevo proveedor</span>
                    <button type="button" id="btn-switch-existing-supplier"
                            class="text-xs text-muted hover:text-ink-soft font-medium transition-colors">
                        ← Usar existente
                    </button>
                </div>

                <x-forms.input
                    label="Nombre / Razón social"
                    name="new_supplier_name"
                    placeholder="Ej: Distribuidora Ferremax SAC"
                />

                <div class="flex gap-sm">
                    <div class="w-full space-y-2" style="flex: 0 0 140px;">
                        <label for="new_supplier_document_type" class="block text-sm font-medium text-ink">Tipo doc.</label>
                        <select id="new_supplier_document_type" name="new_supplier_document_type"
                                class="w-full rounded-md border border-line bg-white px-md py-sm text-ink
                                       focus:border-accent focus:ring-2 focus:ring-accent-ring focus:outline-none">
                            <option value="RUC">RUC</option>
                            <option value="DNI">DNI</option>
                            <option value="CE">CE</option>
                            <option value="PASSPORT">Pasaporte</option>
                            <option value="OTHER">Otro</option>
                        </select>
                    </div>
                    <x-forms.input
                        label="N° documento"
                        name="new_supplier_document_number"
                        placeholder="Ej: 20501234567"
                    />
                </div>

                <x-forms.input
                    label="Email"
                    name="new_supplier_email"
                    type="email"
                    placeholder="ventas@proveedor.pe (opcional)"
                />

                <x-forms.input
                    label="Teléfono"
                    name="new_supplier_phone"
                    placeholder="Ej: 994123456 (opcional)"
                />
                <div class="flex items-center gap-sm pt-xs">
                    <div class="flex-1 border-t border-accent" style="opacity:0.35;"></div>
                    <span class="text-xs text-accent font-medium" style="opacity:0.6;">Nuevo proveedor</span>
                    <div class="flex-1 border-t border-accent" style="opacity:0.35;"></div>
                </div>

            </div>

            <x-forms.input
                label="Stock inicial"
                name="initial_stock"
                type="number"
                placeholder="Ej: 50"
                required
            />

            <div id="current-stock-wrapper" style="display:none;">
                <x-forms.input
                    label="Stock actual"
                    name="current_stock"
                    type="number"
                    placeholder="Ej: 35"
                    required
                />
            </div>

            <x-forms.input
                label="Precio de compra (S/)"
                name="purchase_price"
                type="number"
                placeholder="Ej: 89.99"
                required
            />

            <p id="hint-create" class="text-xs text-muted">
                Al crear un lote, el stock actual se establece igual al stock inicial.
                Puedes ajustarlo al editar.
            </p>
        </form>
    </div>

    {{-- Footer del panel --}}
    <div class="flex gap-sm px-lg py-md border-t border-line bg-canvas-alt flex-shrink-0">
        <x-ui.button id="panel-cancel-btn" variant="secondary" type="button" class="flex-1">
            Cancelar
        </x-ui.button>
        <x-ui.button id="panel-submit-btn" variant="primary" type="button" class="flex-1">
            Guardar lote
        </x-ui.button>
    </div>
</aside>


<script>
(function () {
    // ── Elementos base ─────────────────────────────────────────────────────
    const panel          = document.getElementById('batch-panel');
    const overlay        = document.getElementById('panel-overlay');
    const panelTitle     = document.getElementById('panel-title');
    const form           = document.getElementById('batch-form');
    const formMethod     = document.getElementById('form-method');
    const formBatchId    = document.getElementById('form-batch-id');
    const currentWrapper = document.getElementById('current-stock-wrapper');
    const hintCreate     = document.getElementById('hint-create');
    const btnNuevo       = document.getElementById('btn-nuevo-lote');
    const btnClose       = document.getElementById('panel-close-btn');
    const btnCancel      = document.getElementById('panel-cancel-btn');
    const btnSubmit      = document.getElementById('panel-submit-btn');

    // Campos — IDs generados por x-forms.* con el prop `name`
    const inputProduct   = document.getElementById('product_id');
    const inputSupplier  = document.getElementById('supplier_id');
    const inputInitial   = document.getElementById('initial_stock');
    const inputCurrent   = document.getElementById('current_stock');
    const inputPrice     = document.getElementById('purchase_price');

    // Modo nuevo producto
    const modeExisting       = document.getElementById('mode-existing');
    const modeNew            = document.getElementById('mode-new');
    const btnSwitchNew       = document.getElementById('btn-switch-new');
    const btnSwitchExisting  = document.getElementById('btn-switch-existing');
    const inputNewCategory   = document.getElementById('new_product_category_id');
    const inputNewName       = document.getElementById('new_product_name');
    const inputNewSalePrice  = document.getElementById('new_product_sale_price');

    const storeUrl = form.getAttribute('data-store-url');
    let isNewProductMode  = false;
    let isNewSupplierMode = false;

    // ── Toggle modo nuevo producto ─────────────────────────────────────────
    function setNewProductMode(active) {
        isNewProductMode = active;
        if (active) {
            modeExisting.style.display = 'none';
            modeNew.style.display      = 'flex';
            // Quitar required del select existente, poner en nuevos
            inputProduct.removeAttribute('required');
            inputNewCategory.setAttribute('required', '');
            inputNewName.setAttribute('required', '');
            inputNewSalePrice.setAttribute('required', '');
        } else {
            modeExisting.style.display = 'block';
            modeNew.style.display      = 'none';
            // Restaurar required al select existente
            inputProduct.setAttribute('required', '');
            inputNewCategory.removeAttribute('required');
            inputNewName.removeAttribute('required');
            inputNewSalePrice.removeAttribute('required');
        }
    }

    btnSwitchNew.addEventListener('click',      () => setNewProductMode(true));
    btnSwitchExisting.addEventListener('click', () => setNewProductMode(false));

    // ── Toggle modo nuevo proveedor ────────────────────────────────────────
    const modeSupplierExisting    = document.getElementById('mode-supplier-existing');
    const modeSupplierNew         = document.getElementById('mode-supplier-new');
    const btnSwitchNewSupplier    = document.getElementById('btn-switch-new-supplier');
    const btnSwitchExistSupplier  = document.getElementById('btn-switch-existing-supplier');
    const inputNewSupplierName    = document.getElementById('new_supplier_name');
    const inputNewSupplierDocNum  = document.getElementById('new_supplier_document_number');

    function setNewSupplierMode(active) {
        isNewSupplierMode = active;
        if (active) {
            modeSupplierExisting.style.display = 'none';
            modeSupplierNew.style.display      = 'flex';
            inputSupplier.removeAttribute('required');
            inputNewSupplierName.setAttribute('required', '');
            inputNewSupplierDocNum.setAttribute('required', '');
        } else {
            modeSupplierExisting.style.display = 'block';
            modeSupplierNew.style.display      = 'none';
            inputSupplier.setAttribute('required', '');
            inputNewSupplierName.removeAttribute('required');
            inputNewSupplierDocNum.removeAttribute('required');
        }
    }

    btnSwitchNewSupplier.addEventListener('click',   () => setNewSupplierMode(true));
    btnSwitchExistSupplier.addEventListener('click', () => setNewSupplierMode(false));

    // ── Toggle modo nueva categoría ────────────────────────────────────────
    const modeCatExisting    = document.getElementById('mode-cat-existing');
    const modeCatNew         = document.getElementById('mode-cat-new');
    const btnSwitchNewCat    = document.getElementById('btn-switch-new-cat');
    const btnSwitchExistCat  = document.getElementById('btn-switch-existing-cat');
    const inputCatSelect     = document.getElementById('new_product_category_id');
    const inputNewCatName    = document.getElementById('new_category_name');
    let isNewCategoryMode = false;

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
            // solo restaura required al select si estamos en modo nuevo producto
            if (isNewProductMode) inputCatSelect.setAttribute('required', '');
        }
    }

    btnSwitchNewCat.addEventListener('click',   () => setNewCategoryMode(true));
    btnSwitchExistCat.addEventListener('click', () => setNewCategoryMode(false));

    // ── Abrir / cerrar panel ───────────────────────────────────────────────
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
        panelTitle.textContent = 'Nuevo lote';
        btnSubmit.textContent  = 'Guardar lote';
        form.action    = storeUrl;
        formMethod.value   = 'POST';
        formBatchId.value  = '';
        inputProduct.value  = '';
        inputSupplier.value = '';
        inputInitial.value  = '';
        inputCurrent.value  = '';
        inputPrice.value    = '';
        currentWrapper.style.display = 'none';
        inputCurrent.removeAttribute('required');
        if (hintCreate) hintCreate.style.display = 'block';
        // Volver a modo existente (producto, proveedor y categoría)
        setNewProductMode(false);
        setNewSupplierMode(false);
        setNewCategoryMode(false);
        // Limpiar campos de nuevo producto
        inputNewCategory.value  = '';
        inputNewName.value      = '';
        inputNewSalePrice.value = '';
        const descTextarea = document.getElementById('new_product_description');
        if (descTextarea) descTextarea.value = '';
        // Limpiar campo de nueva categoría
        if (inputNewCatName) inputNewCatName.value = '';
        // Limpiar campos de nuevo proveedor
        inputNewSupplierName.value   = '';
        inputNewSupplierDocNum.value = '';
        const emailInput = document.getElementById('new_supplier_email');
        const phoneInput = document.getElementById('new_supplier_phone');
        if (emailInput) emailInput.value = '';
        if (phoneInput) phoneInput.value = '';
        // Mostrar botones de toggle en modo crear
        btnSwitchNew.style.display         = 'inline';
        btnSwitchNewSupplier.style.display = 'inline';
    }

    // ── Cargar datos para edición ──────────────────────────────────────────
    function loadBatch(row) {
        const id = row.dataset.id;
        panelTitle.textContent = 'Editar lote #' + id;
        btnSubmit.textContent  = 'Actualizar lote';
        form.action        = storeUrl + '/' + id;
        formMethod.value   = 'PUT';
        formBatchId.value  = id;
        inputProduct.value  = row.dataset.product;
        inputSupplier.value = row.dataset.supplier;
        inputInitial.value  = row.dataset.initial;
        inputCurrent.value  = row.dataset.current;
        inputPrice.value    = row.dataset.price;
        currentWrapper.style.display = 'block';
        inputCurrent.setAttribute('required', '');
        if (hintCreate) hintCreate.style.display = 'none';
        // En edición no permitir cambiar a nuevo producto ni proveedor
        setNewProductMode(false);
        setNewSupplierMode(false);
        btnSwitchNew.style.display         = 'none';
        btnSwitchNewSupplier.style.display = 'none';
    }

    // ── Listeners ─────────────────────────────────────────────────────────
    btnNuevo.addEventListener('click', () => { resetForm(); openPanel(); });
    btnClose.addEventListener('click', closePanel);
    btnCancel.addEventListener('click', closePanel);
    overlay.addEventListener('click', closePanel);

    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') closePanel();
    });

    document.querySelectorAll('.btn-editar-lote').forEach(btn => {
        btn.addEventListener('click', () => {
            const row = btn.closest('.batch-row');
            loadBatch(row);
            openPanel();
        });
    });

    btnSubmit.addEventListener('click', () => { form.submit(); });
})();
</script>

@endsection
