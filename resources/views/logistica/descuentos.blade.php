@extends('layout.base')

@section('title', 'Cupones de Descuento — Ferretería Abad')
@section('pageTitle', 'Descuentos')

@section('content')
<div class="flex flex-col gap-lg">

    {{-- ── Encabezado ── --}}
    <div class="flex items-center justify-between gap-md flex-wrap">
        <div>
            <h1 class="text-xl font-bold text-ink">Cupones de Descuento</h1>
            <p class="text-sm text-ink-soft mt-xs">Administra los cupones y descuentos automáticos o manuales del sistema.</p>
        </div>
        <x-ui.button id="btn-nuevo-descuento" variant="primary" type="button">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                 stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/>
            </svg>
            Nuevo cupón
        </x-ui.button>
    </div>

    {{-- ── Notificaciones ── --}}
    @if(session('success'))
        <x-ui.alert variant="success">{{ session('success') }}</x-ui.alert>
    @endif

    @if($errors->any())
        <div class="space-y-xs">
            @foreach($errors->all() as $error)
                <x-ui.alert variant="danger">{{ $error }}</x-ui.alert>
            @endforeach
        </div>
    @endif

    {{-- ── Métricas ── --}}
    @php
        $total = $discounts->count();
        $active = $discounts->filter(fn($d) => $d->expiration_date->isFuture())->count();
        $expired = $total - $active;
    @endphp
    <div class="grid gap-md" style="grid-template-columns: repeat(auto-fit, minmax(160px, 1fr));">
        <x-ui.card variant="stats">
            <div class="px-md py-md flex flex-col gap-xs">
                <span class="text-xs text-muted uppercase tracking-wide font-medium">Total Cupones</span>
                <span class="text-2xl font-bold text-ink">{{ $total }}</span>
            </div>
        </x-ui.card>
        <x-ui.card variant="stats">
            <div class="px-md py-md flex flex-col gap-xs">
                <span class="text-xs text-muted uppercase tracking-wide font-medium">Vigentes</span>
                <span class="text-2xl font-bold text-stock-ok">{{ $active }}</span>
            </div>
        </x-ui.card>
        <x-ui.card variant="stats">
            <div class="px-md py-md flex flex-col gap-xs">
                <span class="text-xs text-muted uppercase tracking-wide font-medium">Vencidos / Agotados</span>
                <span class="text-2xl font-bold text-stock-out">{{ $expired }}</span>
            </div>
        </x-ui.card>
    </div>

    {{-- ── Tabla de Descuentos ── --}}
    <x-ui.card>
        <x-ui.data-table>
            <x-slot name="header">
                <tr>
                    <th class="px-md py-sm">Código</th>
                    <th class="px-md py-sm">Uso</th>
                    <th class="px-md py-sm">Tipo</th>
                    <th class="px-md py-sm text-right">Valor</th>
                    <th class="px-md py-sm text-right">Mín. Compra</th>
                    <th class="px-md py-sm text-right">Límite Usos</th>
                    <th class="px-md py-sm">Fecha Expiración</th>
                    <th class="px-md py-sm text-center">Estado</th>
                    <th class="px-md py-sm text-center">Acciones</th>
                </tr>
            </x-slot>

            @forelse($discounts as $discount)
                @php
                    $isExpired = $discount->expiration_date->isPast();
                    $badgeVariant = $isExpired ? 'out' : 'ok';
                    $badgeLabel = $isExpired ? 'Vencido' : 'Vigente';
                @endphp
                <tr
                    class="discount-row"
                    data-id="{{ $discount->id }}"
                    data-code="{{ $discount->code }}"
                    data-type-use="{{ $discount->type_use }}"
                    data-type-discount="{{ $discount->type_discount }}"
                    data-amount="{{ $discount->amount }}"
                    data-minimum-amount="{{ $discount->minimum_amount }}"
                    data-maximum-amount="{{ $discount->maximum_amount }}"
                    data-expiration-date="{{ $discount->expiration_date->format('Y-m-d\TH:i') }}"
                    data-use-limit="{{ $discount->use_limit }}"
                    data-type-limit="{{ $discount->type_limit }}"
                >
                    <td class="px-md py-sm font-semibold text-accent">{{ $discount->code }}</td>
                    <td class="px-md py-sm text-sm text-ink-soft">
                        <span class="px-sm py-xs rounded-md bg-canvas text-xs font-medium">
                            {{ $discount->type_use === 'AUTOMATIC' ? 'Automático' : 'Manual' }}
                        </span>
                    </td>
                    <td class="px-md py-sm text-sm text-ink-soft">
                        {{ $discount->type_discount === 'PERCENTAGE' ? 'Porcentaje (%)' : 'Fijo (S/)' }}
                    </td>
                    <td class="px-md py-sm text-right font-medium text-ink">
                        {{ $discount->type_discount === 'PERCENTAGE' ? $discount->amount . '%' : 'S/ ' . number_format($discount->amount, 2) }}
                    </td>
                    <td class="px-md py-sm text-right text-sm text-ink-soft">
                        S/ {{ number_format($discount->minimum_amount, 2) }}
                    </td>
                    <td class="px-md py-sm text-right text-sm text-ink-soft">
                        {{ $discount->use_limit }} ({{ $discount->type_limit === 'FOR_SALE' ? 'Por Venta' : ($discount->type_limit === 'FOR_PRODUCT' ? 'Por Prod' : 'Ilimitado') }})
                    </td>
                    <td class="px-md py-sm text-sm text-ink-soft">
                        {{ $discount->expiration_date->format('d/m/Y H:i') }}
                    </td>
                    <td class="px-md py-sm text-center">
                        <x-ui.badge variant="{{ $badgeVariant }}">{{ $badgeLabel }}</x-ui.badge>
                    </td>
                    <td class="px-md py-sm text-center">
                        <button
                            type="button"
                            class="btn-editar-descuento text-accent hover:text-accent-hover text-sm font-medium transition-colors"
                        >
                            Editar
                        </button>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="9" class="px-md py-lg text-center text-muted text-sm">
                        No hay cupones de descuento registrados todavía.
                    </td>
                </tr>
            @endforelse
        </x-ui.data-table>
    </x-ui.card>

</div>

{{-- ══════════════════════════════════════════════════════════════
     Panel lateral — Crear / Editar descuento
     ══════════════════════════════════════════════════════════════ --}}

{{-- Overlay --}}
<div
    id="discount-panel-overlay"
    class="fixed inset-0 z-40"
    style="display:none; background: rgba(15,23,42,0.45); backdrop-filter: blur(2px); opacity:0; transition: opacity 0.25s ease;"
></div>

{{-- Panel --}}
<aside
    id="discount-panel"
    class="fixed top-0 right-0 h-full z-50 bg-surface border-l border-line flex flex-col"
    style="width: 420px; max-width: 100vw; transform: translateX(100%); transition: transform 0.28s cubic-bezier(0.4,0,0.2,1); box-shadow: var(--shadow-elevated);"
>
    {{-- Header --}}
    <div class="flex items-center justify-between px-lg py-md border-b border-line bg-canvas-alt flex-shrink-0">
        <h2 id="discount-panel-title" class="font-semibold text-ink text-base">Nuevo cupón</h2>
        <button id="discount-panel-close" type="button"
                class="text-muted hover:text-ink transition-colors p-xs rounded-md hover:bg-line">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                 stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/>
            </svg>
        </button>
    </div>

    {{-- Formulario --}}
    <div class="flex-1 overflow-y-auto px-lg py-lg">
        <form id="discount-form" method="POST"
              action="{{ route('descuentos.store') }}"
              class="flex flex-col gap-md">
            @csrf
            <input type="hidden" name="_method" id="discount-form-method" value="POST">

            <x-forms.input
                label="Código del cupón"
                name="code"
                placeholder="Ej: VERANO2026"
                required="true"
            />

            <div class="grid gap-sm grid-cols-2">
                <div class="space-y-2">
                    <label for="type_use" class="block text-sm font-medium text-ink">Modo de aplicación</label>
                    <select id="type_use" name="type_use" required
                            class="w-full rounded-md border border-line bg-white px-md py-sm text-ink text-sm focus:border-accent focus:ring-2 focus:ring-accent-ring focus:outline-none">
                        <option value="MANUAL">Manual (Ingresar Código)</option>
                        <option value="AUTOMATIC">Automático (Al comprar)</option>
                    </select>
                </div>

                <div class="space-y-2">
                    <label for="type_discount" class="block text-sm font-medium text-ink">Tipo de Descuento</label>
                    <select id="type_discount" name="type_discount" required
                            class="w-full rounded-md border border-line bg-white px-md py-sm text-ink text-sm focus:border-accent focus:ring-2 focus:ring-accent-ring focus:outline-none">
                        <option value="PERCENTAGE">Porcentaje (%)</option>
                        <option value="AMOUNT">Monto Fijo (S/)</option>
                    </select>
                </div>
            </div>

            <x-forms.input
                label="Valor del descuento"
                name="amount"
                type="number"
                placeholder="Ej: 10 (para 10% o S/10)"
                required="true"
            />

            <div class="grid gap-sm grid-cols-2">
                <x-forms.input
                    label="Monto compra mín. (S/)"
                    name="minimum_amount"
                    type="number"
                    value="0"
                    placeholder="Ej: 50"
                    required="true"
                />

                <x-forms.input
                    label="Tope desc. máx. (S/)"
                    name="maximum_amount"
                    type="number"
                    value="0"
                    placeholder="Ej: 20 (0 para ilimitado)"
                    required="true"
                />
            </div>

            <div class="space-y-2">
                <label for="expiration_date" class="block text-sm font-medium text-ink">Fecha de Expiración</label>
                <input id="expiration_date" name="expiration_date" type="datetime-local" required
                       class="w-full rounded-md border border-line bg-white px-md py-sm text-ink text-sm focus:border-accent focus:ring-2 focus:ring-accent-ring focus:outline-none" />
            </div>

            <div class="grid gap-sm grid-cols-2">
                <x-forms.input
                    label="Límite usos"
                    name="use_limit"
                    type="number"
                    value="9999"
                    placeholder="Ej: 100"
                    required="true"
                />

                <div class="space-y-2">
                    <label for="type_limit" class="block text-sm font-medium text-ink">Tipo de límite</label>
                    <select id="type_limit" name="type_limit" required
                            class="w-full rounded-md border border-line bg-white px-md py-sm text-ink text-sm focus:border-accent focus:ring-2 focus:ring-accent-ring focus:outline-none">
                        <option value="FOR_SALE">Por Venta Entera</option>
                        <option value="FOR_PRODUCT">Por Producto Unitario</option>
                        <option value="UNLIMITED">Ilimitado</option>
                    </select>
                </div>
            </div>

        </form>
    </div>

    {{-- Footer --}}
    <div class="flex gap-sm px-lg py-md border-t border-line bg-canvas-alt flex-shrink-0">
        <x-ui.button id="discount-cancel-btn" variant="secondary" type="button" class="flex-1">
            Cancelar
        </x-ui.button>
        <x-ui.button id="discount-submit-btn" variant="primary" type="button" class="flex-1">
            Guardar cupón
        </x-ui.button>
    </div>
</aside>

<script>
(function () {
    // ── Elementos ──────────────────────────────────────────────────────────
    const panel        = document.getElementById('discount-panel');
    const overlay      = document.getElementById('discount-panel-overlay');
    const panelTitle   = document.getElementById('discount-panel-title');
    const form         = document.getElementById('discount-form');
    const formMethod   = document.getElementById('discount-form-method');
    const btnNuevo     = document.getElementById('btn-nuevo-descuento');
    const btnClose     = document.getElementById('discount-panel-close');
    const btnCancel    = document.getElementById('discount-cancel-btn');
    const btnSubmit    = document.getElementById('discount-submit-btn');

    // Campos
    const inputCode       = document.getElementById('code');
    const inputTypeUse    = document.getElementById('type_use');
    const inputTypeDisc   = document.getElementById('type_discount');
    const inputAmount     = document.getElementById('amount');
    const inputMinAmount  = document.getElementById('minimum_amount');
    const inputMaxAmount  = document.getElementById('maximum_amount');
    const inputExpiration = document.getElementById('expiration_date');
    const inputUseLimit   = document.getElementById('use_limit');
    const inputTypeLimit  = document.getElementById('type_limit');

    const storeUrl = form.getAttribute('action');

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
        panelTitle.textContent = 'Nuevo cupón';
        btnSubmit.textContent  = 'Guardar cupón';
        form.setAttribute('action', storeUrl);
        formMethod.value       = 'POST';

        inputCode.value       = '';
        inputTypeUse.value    = 'MANUAL';
        inputTypeDisc.value   = 'PERCENTAGE';
        inputAmount.value     = '';
        inputMinAmount.value  = '0';
        inputMaxAmount.value  = '0';
        inputExpiration.value = '';
        inputUseLimit.value   = '9999';
        inputTypeLimit.value  = 'FOR_SALE';
    }

    // ── Cargar datos para edición ──────────────────────────────────────────
    function loadDiscount(row) {
        const id = row.dataset.id;
        panelTitle.textContent = 'Editar cupón #' + id;
        btnSubmit.textContent  = 'Actualizar cupón';
        form.setAttribute('action', storeUrl + '/' + id);
        formMethod.value       = 'PUT';

        inputCode.value       = row.dataset.code;
        inputTypeUse.value    = row.dataset.typeUse;
        inputTypeDisc.value   = row.dataset.typeDiscount;
        inputAmount.value     = row.dataset.amount;
        inputMinAmount.value  = row.dataset.minimumAmount;
        inputMaxAmount.value  = row.dataset.maximumAmount;
        inputExpiration.value = row.dataset.expirationDate;
        inputUseLimit.value   = row.dataset.useLimit;
        inputTypeLimit.value  = row.dataset.typeLimit;
    }

    // ── Listeners ─────────────────────────────────────────────────────────
    btnNuevo.addEventListener('click', () => { resetForm(); openPanel(); });
    btnClose.addEventListener('click', closePanel);
    btnCancel.addEventListener('click', closePanel);
    overlay.addEventListener('click', closePanel);

    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') closePanel();
    });

    document.querySelectorAll('.btn-editar-descuento').forEach(btn => {
        btn.addEventListener('click', () => {
            const row = btn.closest('.discount-row');
            loadDiscount(row);
            openPanel();
        });
    });

    btnSubmit.addEventListener('click', () => {
        if (form.reportValidity()) {
            form.submit();
        }
    });
})();
</script>
@endsection
