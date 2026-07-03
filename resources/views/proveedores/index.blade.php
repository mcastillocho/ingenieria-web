@extends('layout.base')

@section('title', 'Proveedores — Ferretería Abad')
@section('pageTitle', 'Proveedores')

@section('content')
<div class="flex flex-col gap-lg">

    {{-- ── Encabezado ── --}}
    <div class="flex items-center justify-between gap-md flex-wrap">
        <div>
            <h1 class="text-xl font-bold text-ink">Proveedores</h1>
            <p class="text-sm text-ink-soft mt-xs">Gestiona los proveedores registrados en el sistema.</p>
        </div>
        <x-ui.button id="btn-nuevo-proveedor" variant="primary" type="button">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                 stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/>
            </svg>
            Nuevo proveedor
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
        $totalSuppliers  = $suppliers->count();
        $withBatches     = $suppliers->filter(fn($s) => $s->batches_count > 0)->count();
        $withoutBatches  = $totalSuppliers - $withBatches;
        $docTypes        = $suppliers->groupBy('document_type')->map->count();
    @endphp

    <div class="grid gap-md" style="grid-template-columns: repeat(auto-fit, minmax(160px, 1fr));">
        <x-ui.card variant="stats">
            <div class="px-md py-md flex flex-col gap-xs">
                <span class="text-xs text-muted uppercase tracking-wide font-medium">Total</span>
                <span class="text-2xl font-bold text-ink">{{ $totalSuppliers }}</span>
            </div>
        </x-ui.card>
        <x-ui.card variant="stats">
            <div class="px-md py-md flex flex-col gap-xs">
                <span class="text-xs text-muted uppercase tracking-wide font-medium">Con lotes</span>
                <span class="text-2xl font-bold text-stock-ok">{{ $withBatches }}</span>
            </div>
        </x-ui.card>
        <x-ui.card variant="stats">
            <div class="px-md py-md flex flex-col gap-xs">
                <span class="text-xs text-muted uppercase tracking-wide font-medium">Sin lotes</span>
                <span class="text-2xl font-bold text-muted">{{ $withoutBatches }}</span>
            </div>
        </x-ui.card>
    </div>

    {{-- ── Tabla de proveedores ── --}}
    <x-ui.data-table>
        <x-slot name="header">
            <tr>
                <th class="px-md py-sm">#</th>
                <th class="px-md py-sm">Nombre / Razón social</th>
                <th class="px-md py-sm">Tipo doc.</th>
                <th class="px-md py-sm">N° documento</th>
                <th class="px-md py-sm">Email</th>
                <th class="px-md py-sm">Teléfono</th>
                <th class="px-md py-sm text-center">Lotes</th>
                <th class="px-md py-sm text-center">Acciones</th>
            </tr>
        </x-slot>

        @forelse($suppliers as $supplier)
            <tr
                class="supplier-row"
                data-id="{{ $supplier->id }}"
                data-name="{{ $supplier->name }}"
                data-document-type="{{ $supplier->document_type }}"
                data-document-number="{{ $supplier->document_number }}"
                data-email="{{ $supplier->email }}"
                data-phone="{{ $supplier->phone }}"
            >
                <td class="px-md py-sm text-muted text-xs font-mono">{{ $supplier->id }}</td>
                <td class="px-md py-sm font-medium text-ink">{{ $supplier->name ?? '—' }}</td>
                <td class="px-md py-sm">
                    <x-ui.badge variant="neutral">{{ $supplier->document_type }}</x-ui.badge>
                </td>
                <td class="px-md py-sm font-mono text-sm text-ink">{{ $supplier->document_number }}</td>
                <td class="px-md py-sm text-ink-soft text-sm">{{ $supplier->email ?? '—' }}</td>
                <td class="px-md py-sm text-ink-soft text-sm">{{ $supplier->phone ?? '—' }}</td>
                <td class="px-md py-sm text-center">
                    @if($supplier->batches_count > 0)
                        <x-ui.badge variant="ok">{{ $supplier->batches_count }}</x-ui.badge>
                    @else
                        <span class="text-muted text-xs">0</span>
                    @endif
                </td>
                <td class="px-md py-sm text-center">
                    <button
                        type="button"
                        class="btn-editar-proveedor text-accent hover:text-accent-hover text-sm font-medium transition-colors"
                        aria-label="Editar proveedor {{ $supplier->id }}"
                    >
                        Editar
                    </button>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="8" class="px-md py-lg text-center text-muted text-sm">
                    No hay proveedores registrados todavía.
                </td>
            </tr>
        @endforelse
    </x-ui.data-table>

</div>

{{-- ══════════════════════════════════════════════════════════════
     Panel lateral — Crear / Editar proveedor
══════════════════════════════════════════════════════════════ --}}

{{-- Overlay --}}
<div
    id="supplier-panel-overlay"
    class="fixed inset-0 z-40"
    style="display:none; background: rgba(15,23,42,0.45); backdrop-filter: blur(2px); opacity:0; transition: opacity 0.25s ease;"
></div>

{{-- Panel --}}
<aside
    id="supplier-panel"
    aria-label="Formulario de proveedor"
    class="fixed top-0 right-0 h-full z-50 bg-surface border-l border-line flex flex-col"
    style="width: 420px; max-width: 100vw; transform: translateX(100%); transition: transform 0.28s cubic-bezier(0.4,0,0.2,1); box-shadow: var(--shadow-elevated);"
>
    {{-- Header --}}
    <div class="flex items-center justify-between px-lg py-md border-b border-line bg-canvas-alt flex-shrink-0">
        <h2 id="supplier-panel-title" class="font-semibold text-ink text-base">Nuevo proveedor</h2>
        <button id="supplier-panel-close" type="button"
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
        <form id="supplier-form" method="POST"
              action="{{ route('proveedores.store') }}"
              data-store-url="{{ route('proveedores.store') }}"
              class="flex flex-col gap-md">
            @csrf
            <input type="hidden" name="_method" id="supplier-form-method" value="POST">

            <x-forms.input
                label="Nombre / Razón social"
                name="name"
                placeholder="Ej: Distribuidora Herramax SAC"
            />

            <div class="flex gap-sm">
                <div class="w-full space-y-2" style="flex: 0 0 150px;">
                    <label for="document_type" class="block text-sm font-medium text-ink">Tipo de documento</label>
                    <select id="document_type" name="document_type" required
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
                    name="document_number"
                    placeholder="Ej: 20501234567"
                    required
                />
            </div>

            <x-forms.input
                label="Email"
                name="email"
                type="email"
                placeholder="ventas@proveedor.pe (opcional)"
            />

            <x-forms.input
                label="Teléfono"
                name="phone"
                placeholder="Ej: 994123456 (opcional)"
            />

        </form>
    </div>

    {{-- Footer --}}
    <div class="flex gap-sm px-lg py-md border-t border-line bg-canvas-alt flex-shrink-0">
        <x-ui.button id="supplier-cancel-btn" variant="secondary" type="button" class="flex-1">
            Cancelar
        </x-ui.button>
        <x-ui.button id="supplier-submit-btn" variant="primary" type="button" class="flex-1">
            Guardar proveedor
        </x-ui.button>
    </div>
</aside>

<script>
(function () {
    // ── Elementos ──────────────────────────────────────────────────────────
    const panel        = document.getElementById('supplier-panel');
    const overlay      = document.getElementById('supplier-panel-overlay');
    const panelTitle   = document.getElementById('supplier-panel-title');
    const form         = document.getElementById('supplier-form');
    const formMethod   = document.getElementById('supplier-form-method');
    const btnNuevo     = document.getElementById('btn-nuevo-proveedor');
    const btnClose     = document.getElementById('supplier-panel-close');
    const btnCancel    = document.getElementById('supplier-cancel-btn');
    const btnSubmit    = document.getElementById('supplier-submit-btn');

    // Campos — IDs por nombre del campo (x-forms.input usa name como id)
    const inputName    = document.getElementById('name');
    const inputDocType = document.getElementById('document_type');
    const inputDocNum  = document.getElementById('document_number');
    const inputEmail   = document.getElementById('email');
    const inputPhone   = document.getElementById('phone');

    const storeUrl = form.getAttribute('data-store-url');

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
        panelTitle.textContent = 'Nuevo proveedor';
        btnSubmit.textContent  = 'Guardar proveedor';
        form.action        = storeUrl;
        formMethod.value   = 'POST';
        inputName.value    = '';
        inputDocType.value = 'RUC';
        inputDocNum.value  = '';
        inputEmail.value   = '';
        inputPhone.value   = '';
    }

    // ── Cargar datos para edición ──────────────────────────────────────────
    function loadSupplier(row) {
        const id = row.dataset.id;
        panelTitle.textContent = 'Editar proveedor #' + id;
        btnSubmit.textContent  = 'Actualizar proveedor';
        form.action        = storeUrl + '/' + id;
        formMethod.value   = 'PUT';
        inputName.value    = row.dataset.name    || '';
        inputDocType.value = row.dataset.documentType  || 'RUC';
        inputDocNum.value  = row.dataset.documentNumber || '';
        inputEmail.value   = row.dataset.email   || '';
        inputPhone.value   = row.dataset.phone   || '';
    }

    // ── Listeners ─────────────────────────────────────────────────────────
    btnNuevo.addEventListener('click', () => { resetForm(); openPanel(); });
    btnClose.addEventListener('click', closePanel);
    btnCancel.addEventListener('click', closePanel);
    overlay.addEventListener('click', closePanel);

    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') closePanel();
    });

    document.querySelectorAll('.btn-editar-proveedor').forEach(btn => {
        btn.addEventListener('click', () => {
            const row = btn.closest('.supplier-row');
            loadSupplier(row);
            openPanel();
        });
    });

    btnSubmit.addEventListener('click', () => { form.submit(); });
})();
</script>

@endsection
