@extends('layout.base')

@section('title', 'Clientes — Ferretería Abad')
@section('pageTitle', 'Clientes')

@section('content')
<div class="flex flex-col gap-lg">

    {{-- ── Encabezado ── --}}
    <div class="flex items-center justify-between gap-md flex-wrap">
        <div>
            <h1 class="text-xl font-bold text-ink">Gestión de Clientes</h1>
            <p class="text-sm text-ink-soft mt-xs">Administra los clientes que compran en la ferretería.</p>
        </div>
        <x-ui.button id="btn-nuevo-cliente" variant="primary" type="button">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                 stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/>
            </svg>
            Nuevo cliente
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
        $total = $clients->count();
        $dni = $clients->filter(fn($c) => $c->document_type === 'DNI')->count();
        $ruc = $clients->filter(fn($c) => $c->document_type === 'RUC')->count();
    @endphp
    <div class="grid gap-md" style="grid-template-columns: repeat(auto-fit, minmax(160px, 1fr));">
        <x-ui.card variant="stats">
            <div class="px-md py-md flex flex-col gap-xs">
                <span class="text-xs text-muted uppercase tracking-wide font-medium">Total Clientes</span>
                <span class="text-2xl font-bold text-ink">{{ $total }}</span>
            </div>
        </x-ui.card>
        <x-ui.card variant="stats">
            <div class="px-md py-md flex flex-col gap-xs">
                <span class="text-xs text-muted uppercase tracking-wide font-medium">Clientes DNI</span>
                <span class="text-2xl font-bold text-accent">{{ $dni }}</span>
            </div>
        </x-ui.card>
        <x-ui.card variant="stats">
            <div class="px-md py-md flex flex-col gap-xs">
                <span class="text-xs text-muted uppercase tracking-wide font-medium">Empresas RUC</span>
                <span class="text-2xl font-bold text-stock-ok">{{ $ruc }}</span>
            </div>
        </x-ui.card>
    </div>

    {{-- ── Tabla de Clientes ── --}}
    <x-ui.card>
        <x-ui.data-table>
            <x-slot name="header">
                <tr>
                    <th class="px-md py-sm">Tipo Doc.</th>
                    <th class="px-md py-sm">N° Documento</th>
                    <th class="px-md py-sm">Nombres y Apellidos</th>
                    <th class="px-md py-sm">Correo Electrónico</th>
                    <th class="px-md py-sm">Teléfono</th>
                    <th class="px-md py-sm text-center">Acciones</th>
                </tr>
            </x-slot>

            @forelse($clients as $client)
                <tr
                    class="client-row"
                    data-id="{{ $client->id }}"
                    data-document-type="{{ $client->document_type }}"
                    data-document-number="{{ $client->document_number }}"
                    data-name="{{ $client->name }}"
                    data-lastname="{{ $client->lastname }}"
                    data-email="{{ $client->email }}"
                    data-phone="{{ $client->phone }}"
                >
                    <td class="px-md py-sm text-sm text-ink-soft">
                        <span class="px-sm py-xs rounded-md bg-canvas text-xs font-semibold">
                            {{ $client->document_type }}
                        </span>
                    </td>
                    <td class="px-md py-sm font-semibold text-ink">{{ $client->document_number }}</td>
                    <td class="px-md py-sm text-sm font-medium text-ink">
                        {{ $client->name }} {{ $client->lastname }}
                    </td>
                    <td class="px-md py-sm text-sm text-ink-soft">
                        {{ $client->email ?? '—' }}
                    </td>
                    <td class="px-md py-sm text-sm text-ink-soft">
                        {{ $client->phone ?? '—' }}
                    </td>
                    <td class="px-md py-sm text-center">
                        @if($client->document_number === '00000000')
                            <span class="text-xs text-muted font-normal italic">Cliente General (Protegido)</span>
                        @else
                            <button
                                type="button"
                                class="btn-editar-cliente text-accent hover:text-accent-hover text-sm font-medium transition-colors"
                            >
                                Editar
                            </button>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="px-md py-lg text-center text-muted text-sm">
                        No hay clientes registrados todavía.
                    </td>
                </tr>
            @endforelse
        </x-ui.data-table>
    </x-ui.card>

</div>

{{-- ══════════════════════════════════════════════════════════════
     Panel lateral — Crear / Editar cliente
     ══════════════════════════════════════════════════════════════ --}}

{{-- Overlay --}}
<div
    id="client-panel-overlay"
    class="fixed inset-0 z-40"
    style="display:none; background: rgba(15,23,42,0.45); backdrop-filter: blur(2px); opacity:0; transition: opacity 0.25s ease;"
></div>

{{-- Panel --}}
<aside
    id="client-panel"
    class="fixed top-0 right-0 h-full z-50 bg-surface border-l border-line flex flex-col"
    style="width: 420px; max-width: 100vw; transform: translateX(100%); transition: transform 0.28s cubic-bezier(0.4,0,0.2,1); box-shadow: var(--shadow-elevated);"
>
    {{-- Header --}}
    <div class="flex items-center justify-between px-lg py-md border-b border-line bg-canvas-alt flex-shrink-0">
        <h2 id="client-panel-title" class="font-semibold text-ink text-base">Nuevo cliente</h2>
        <button id="client-panel-close" type="button"
                class="text-muted hover:text-ink transition-colors p-xs rounded-md hover:bg-line">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                 stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/>
            </svg>
        </button>
    </div>

    {{-- Formulario --}}
    <div class="flex-1 overflow-y-auto px-lg py-lg">
        <form id="client-form" method="POST"
              action="{{ route('clientes.store') }}"
              class="flex flex-col gap-md">
            @csrf
            <input type="hidden" name="_method" id="client-form-method" value="POST">

            <div class="space-y-2">
                <label for="document_type" class="block text-sm font-medium text-ink">Tipo de Documento</label>
                <select id="document_type" name="document_type" required
                        class="w-full rounded-md border border-line bg-white px-md py-sm text-ink text-sm focus:border-accent focus:ring-2 focus:ring-accent-ring focus:outline-none">
                    <option value="DNI">DNI (Persona Natural)</option>
                    <option value="RUC">RUC (Persona Jurídica)</option>
                    <option value="CE">CE (Carnet de Extranjería)</option>
                    <option value="PASSPORT">Pasaporte</option>
                    <option value="OTHER">Otro</option>
                </select>
            </div>

            <x-forms.input
                label="Número de documento"
                name="document_number"
                placeholder="Ej: 47654321 o 20601234567"
                required="true"
            />

            <x-forms.input
                label="Nombres / Razón Social"
                name="name"
                placeholder="Ej: Juan o Distribuidora Abad S.A.C."
                required="true"
            />

            <x-forms.input
                label="Apellidos"
                name="lastname"
                placeholder="Ej: Pérez (dejar vacío para RUC / empresas)"
            />

            <x-forms.input
                label="Correo electrónico"
                name="email"
                type="email"
                placeholder="Ej: juan.perez@correo.com"
            />

            <x-forms.input
                label="Teléfono"
                name="phone"
                placeholder="Ej: 987654321"
            />

        </form>
    </div>

    {{-- Footer --}}
    <div class="flex gap-sm px-lg py-md border-t border-line bg-canvas-alt flex-shrink-0">
        <x-ui.button id="client-cancel-btn" variant="secondary" type="button" class="flex-1">
            Cancelar
        </x-ui.button>
        <x-ui.button id="client-submit-btn" variant="primary" type="button" class="flex-1">
            Guardar cliente
        </x-ui.button>
    </div>
</aside>

<script>
(function () {
    // ── Elementos ──────────────────────────────────────────────────────────
    const panel        = document.getElementById('client-panel');
    const overlay      = document.getElementById('client-panel-overlay');
    const panelTitle   = document.getElementById('client-panel-title');
    const form         = document.getElementById('client-form');
    const formMethod   = document.getElementById('client-form-method');
    const btnNuevo     = document.getElementById('btn-nuevo-cliente');
    const btnClose     = document.getElementById('client-panel-close');
    const btnCancel    = document.getElementById('client-cancel-btn');
    const btnSubmit    = document.getElementById('client-submit-btn');

    // Campos
    const inputDocType = document.getElementById('document_type');
    const inputDocNum  = document.getElementById('document_number');
    const inputName    = document.getElementById('name');
    const inputLastname = document.getElementById('lastname');
    const inputEmail   = document.getElementById('email');
    const inputPhone   = document.getElementById('phone');

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
        panelTitle.textContent = 'Nuevo cliente';
        btnSubmit.textContent  = 'Guardar cliente';
        form.setAttribute('action', storeUrl);
        formMethod.value       = 'POST';

        inputDocType.value = 'DNI';
        inputDocNum.value  = '';
        inputDocNum.disabled = false;
        inputName.value    = '';
        inputLastname.value = '';
        inputEmail.value   = '';
        inputPhone.value   = '';
    }

    // ── Cargar datos para edición ──────────────────────────────────────────
    function loadClient(row) {
        const id = row.dataset.id;
        panelTitle.textContent = 'Editar cliente #' + id;
        btnSubmit.textContent  = 'Actualizar cliente';
        form.setAttribute('action', storeUrl + '/' + id);
        formMethod.value       = 'PUT';

        inputDocType.value = row.dataset.documentType;
        inputDocNum.value  = row.dataset.documentNumber;
        
        // El número de documento no debería cambiarse si es un identificador clave único
        // Pero lo permitimos editar por si se equivocaron al ingresarlo, excepto si es Cliente General (que no tiene botón editar)
        
        inputName.value     = row.dataset.name;
        inputLastname.value = row.dataset.lastname;
        inputEmail.value    = row.dataset.email || '';
        inputPhone.value    = row.dataset.phone || '';
    }

    // ── Listeners ─────────────────────────────────────────────────────────
    btnNuevo.addEventListener('click', () => { resetForm(); openPanel(); });
    btnClose.addEventListener('click', closePanel);
    btnCancel.addEventListener('click', closePanel);
    overlay.addEventListener('click', closePanel);

    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') closePanel();
    });

    document.querySelectorAll('.btn-editar-cliente').forEach(btn => {
        btn.addEventListener('click', () => {
            const row = btn.closest('.client-row');
            loadClient(row);
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
