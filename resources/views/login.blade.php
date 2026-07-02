<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Ferretería Abad</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body {
            -webkit-font-smoothing: antialiased;
        }
    </style>
</head>

<body class="bg-canvas text-ink font-body min-h-screen flex items-center justify-center px-sm">
    <x-ui.card class="mx-auto">
        <x-slot name="title">Iniciar Sesión</x-slot>
        <x-slot name="body">
            @if(session('error'))
                <div id="flash-alert" class="mb-md">
                    <x-ui.alert variant="danger">{{ session('error') }}</x-ui.alert>
                </div>
            @endif

            @if(session('success'))
                <div id="flash-alert" class="mb-md">
                    <x-ui.alert variant="success">{{ session('success') }}</x-ui.alert>
                </div>
            @endif

            <form method="POST" action="{{ url('/login') }}" class="space-y-md">
                @csrf

                <x-forms.input name="username" label="Usuario" required="true" />
                <x-forms.input name="password" type="password" label="Contraseña" required="true" />

                <div class="mt-md text-right">
                    <x-ui.button type="submit">Entrar</x-ui.button>
                </div>
            </form>
        </x-slot>
    </x-ui.card>
    <script>
        // hide flash alerts after 4 seconds
        document.addEventListener('DOMContentLoaded', function() {
            const alert = document.getElementById('flash-alert');
            if (alert) {
                setTimeout(() => {
                    alert.style.transition = 'opacity 0.3s ease';
                    alert.style.opacity = '0';
                    setTimeout(() => alert.remove(), 350);
                }, 4000);
            }
        });
    </script>
</body>

</html>