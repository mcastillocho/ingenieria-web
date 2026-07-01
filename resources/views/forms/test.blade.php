<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Forms UI — Abad</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body { min-height: 100vh; }
        .page-shell { min-height: 100vh; padding: 2rem 1.5rem; background: var(--color-canvas); }
        .page-header { max-width: 1120px; margin: 0 auto 2rem; }
        .section { margin-bottom: 2.5rem; }
        .section-grid { display: grid; gap: 1rem; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); }
        input.touched:invalid, select.touched:invalid {
            border-color: var(--color-danger);
            box-shadow: 0 0 0 1px rgba(185, 28, 28, 0.18);
        }
    </style>
</head>
<body class="bg-canvas text-ink font-body">
    <div class="page-shell">
        <header class="page-header">
            <p class="text-xs uppercase tracking-wide text-muted">Forms UI</p>
            <h1 class="text-3xl font-semibold text-ink mt-2">Validación de componentes de formulario</h1>
            <p class="text-ink-soft mt-2 max-w-2xl">Prueba los componentes de formulario actuales y sus variantes de uso más comunes.</p>
        </header>

        <form id="formsTest" novalidate class="space-y-10">
            <section class="section">
                <div class="flex items-center justify-between gap-4 mb-4">
                    <div>
                        <h2 class="text-xl font-semibold text-ink">Inputs</h2>
                        <p class="text-sm text-muted mt-1">Inputs con label, placeholder, tipo y validación básica.</p>
                    </div>
                </div>
                <div class="section-grid">
                    <x-forms.input label="Nombre" name="name" placeholder="Ingresa tu nombre" />
                    <x-forms.input label="Correo electrónico" name="email" type="email" placeholder="usuario@ejemplo.com" />
                    <x-forms.input label="Contraseña" name="password" type="password" placeholder="Escribe una contraseña" />
                    <x-forms.input label="Cantidad" name="quantity" type="number" value="1" />
                </div>
            </section>

            <section class="section">
                <div class="flex items-center justify-between gap-4 mb-4">
                    <div>
                        <h2 class="text-xl font-semibold text-ink">Inputs obligatorios</h2>
                        <p class="text-sm text-muted mt-1">Ejemplos de inputs con atributo `required`.</p>
                    </div>
                </div>
                <div class="section-grid">
                    <x-forms.input label="Nombre obligatorio" name="required_name" placeholder="Requerido" required />
                    <x-forms.input label="Email obligatorio" name="required_email" type="email" placeholder="Requerido" required />
                </div>
            </section>

            <section class="section">
                <div class="flex items-center justify-between gap-4 mb-4">
                    <div>
                        <h2 class="text-xl font-semibold text-ink">Selects</h2>
                        <p class="text-sm text-muted mt-1">Selects para elegir una opción con valor inicial y required.</p>
                    </div>
                </div>
                <div class="section-grid">
                    <x-forms.select label="Estado" name="status" :options="['' => 'Selecciona un estado', 'pending' => 'Pendiente', 'completed' => 'Completado', 'cancelled' => 'Cancelado']" value="completed" />
                    <x-forms.select label="Categoría" name="category" :options="['tools' => 'Herramientas', 'machines' => 'Máquinas', 'accessories' => 'Accesorios']" required />
                </div>
            </section>

            <section class="section">
                <div class="flex items-center justify-between gap-4 mb-4">
                    <div>
                        <h2 class="text-xl font-semibold text-ink">Variantes con ayuda</h2>
                        <p class="text-sm text-muted mt-1">Inputs y selects con clases adicionales para mostrar personalización.</p>
                    </div>
                </div>
                <div class="section-grid">
                    <x-forms.input label="Nombre con borde accent" name="styled_name" placeholder="Ejemplo" class="border-accent focus:border-accent focus:ring-accent-ring" />
                    <x-forms.select label="Select con ancho adaptado" name="styled_category" :options="['' => 'Selecciona', 'one' => 'Uno', 'two' => 'Dos']" class="max-w-sm" />
                </div>
            </section>
            </form>
        </div>
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                const form = document.getElementById('formsTest');
                if (!form) return;
                const fields = form.querySelectorAll('input[required], select[required]');
                fields.forEach(field => {
                    field.addEventListener('blur', () => {
                        field.classList.add('touched');
                    });
                    field.addEventListener('change', () => {
                        field.classList.add('touched');
                    });
                });
            });
        </script>
    </body>
</html>
