@extends('layout.base')

@section('title', 'Mi Perfil — Ferretería Abad')
@section('pageTitle', 'Mi Perfil')

@section('content')
<div class="flex flex-col gap-lg">

    {{-- Notificaciones de Estado --}}
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

    <div class="grid gap-lg lg:grid-cols-3 items-start">
        
        {{-- Tarjeta 1: Información de Perfil (Solo Lectura) --}}
        <div class="lg:col-span-2">
            <x-ui.card>
                <x-slot name="title">Información del Trabajador</x-slot>
                <x-slot name="body">
                    <p class="text-xs text-muted mb-md">Esta es información sensible del sistema y no puede ser modificada directamente por motivos de seguridad.</p>
                    
                    <div class="grid gap-md sm:grid-cols-2">
                        <div class="space-y-xs">
                            <label class="block text-xs font-semibold text-ink-soft">Nombre</label>
                            <input type="text" value="{{ $worker->name }}" disabled 
                                   class="w-full rounded-md border border-line bg-canvas-alt px-md py-sm text-ink-soft cursor-not-allowed focus:outline-none text-sm" />
                        </div>

                        <div class="space-y-xs">
                            <label class="block text-xs font-semibold text-ink-soft">Apellidos</label>
                            <input type="text" value="{{ $worker->lastname }}" disabled 
                                   class="w-full rounded-md border border-line bg-canvas-alt px-md py-sm text-ink-soft cursor-not-allowed focus:outline-none text-sm" />
                        </div>

                        <div class="space-y-xs">
                            <label class="block text-xs font-semibold text-ink-soft">Tipo de Documento</label>
                            <input type="text" value="{{ $worker->document_type }}" disabled 
                                   class="w-full rounded-md border border-line bg-canvas-alt px-md py-sm text-ink-soft cursor-not-allowed focus:outline-none text-sm" />
                        </div>

                        <div class="space-y-xs">
                            <label class="block text-xs font-semibold text-ink-soft">N° de Documento</label>
                            <input type="text" value="{{ $worker->document_number }}" disabled 
                                   class="w-full rounded-md border border-line bg-canvas-alt px-md py-sm text-ink-soft cursor-not-allowed focus:outline-none text-sm" />
                        </div>

                        <div class="space-y-xs">
                            <label class="block text-xs font-semibold text-ink-soft">Correo Electrónico</label>
                            <input type="email" value="{{ $worker->email }}" disabled 
                                   class="w-full rounded-md border border-line bg-canvas-alt px-md py-sm text-ink-soft cursor-not-allowed focus:outline-none text-sm" />
                        </div>

                        <div class="space-y-xs">
                            <label class="block text-xs font-semibold text-ink-soft">Teléfono</label>
                            <input type="text" value="{{ $worker->phone }}" disabled 
                                   class="w-full rounded-md border border-line bg-canvas-alt px-md py-sm text-ink-soft cursor-not-allowed focus:outline-none text-sm" />
                        </div>

                        <div class="space-y-xs">
                            <label class="block text-xs font-semibold text-ink-soft">Usuario de Login</label>
                            <input type="text" value="{{ $credential->username }}" disabled 
                                   class="w-full rounded-md border border-line bg-canvas-alt px-md py-sm text-ink-soft cursor-not-allowed focus:outline-none text-sm font-semibold" />
                        </div>

                        <div class="space-y-xs">
                            <label class="block text-xs font-semibold text-ink-soft">Rol del Sistema</label>
                            <div class="relative">
                                <input type="text" value="{{ strtoupper($credential->role) }}" disabled 
                                       class="w-full rounded-md border border-line bg-canvas-alt pl-md pr-16 py-sm text-ink-soft cursor-not-allowed focus:outline-none text-sm font-semibold" />
                                <span class="absolute right-2 top-1/2 -translate-y-1/2">
                                    <x-ui.badge variant="{{ $credential->role === 'admin' ? 'ok' : 'neutral' }}">
                                        {{ $credential->role }}
                                    </x-ui.badge>
                                </span>
                            </div>
                        </div>
                    </div>
                </x-slot>
            </x-ui.card>
        </div>

        {{-- Tarjeta 2: Cambiar Contraseña --}}
        <div>
            <x-ui.card variant="stats">
                <x-slot name="title">Cambiar Contraseña</x-slot>
                <x-slot name="body">
                    <form action="{{ route('perfil.password') }}" method="POST" class="flex flex-col gap-md">
                        @csrf
                        
                        <x-forms.input 
                            label="Contraseña Actual" 
                            name="current_password" 
                            type="password" 
                            placeholder="••••••••" 
                            required="true"
                        />

                        <x-forms.input 
                            label="Nueva Contraseña" 
                            name="new_password" 
                            type="password" 
                            placeholder="Mínimo 8 caracteres" 
                            required="true"
                        />

                        <x-forms.input 
                            label="Confirmar Nueva Contraseña" 
                            name="new_password_confirmation" 
                            type="password" 
                            placeholder="Repite la nueva contraseña" 
                            required="true"
                        />

                        <div class="mt-xs">
                            <x-ui.button type="submit" class="w-full" variant="primary">
                                Actualizar Contraseña
                            </x-ui.button>
                        </div>
                    </form>
                </x-slot>
            </x-ui.card>
        </div>

    </div>

</div>
@endsection
