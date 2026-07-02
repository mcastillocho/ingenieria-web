# Componentes Blade — Guía de uso

Esta guía describe los componentes Blade disponibles en el proyecto y cómo usarlos para construir pantallas con consistencia. Incluye ejemplos de implementación y una sección específica para el layout base.

---

## 1. Cómo usar esta guía

- Los componentes viven en `resources/views/components/`.
- Usa `x-namespace.component-name` para renderizarlos en tus vistas.
- El layout base se encuentra en `resources/views/layout/base.blade.php` y contiene el shell principal de la app.
- Para páginas completas, extiende `layout.base` y define las secciones que necesites.

---

## 2. Layout base

El layout base es el shell principal de la aplicación.

Archivo: `resources/views/layout/base.blade.php`

### Secciones disponibles

- `@section('title', '...')` — título de página.
- `@section('pageTitle', '...')` — título mostrado en el topbar.
- `@section('appVersion', '...')` — versión mostrada en el topbar.
- `@section('extraHead')` — contenido adicional en el `<head>`.
- `@section('content')` — contenido principal de la página.

### Estructura básica

```blade
@extends('layout.base')

@section('title', 'Mi página')
@section('pageTitle', 'Panel de Control')
@section('appVersion', 'v0.1.0')

@section('content')
    <!-- tu contenido aquí -->
@endsection
```

### Componentes usados en el layout base

- `x-layout.topbar` — header superior pegajoso con botón de toggle y slots para izquierda y derecha.
- `x-layout.sidebar` — contenedor lateral con area de navegación y footer.
- `x-layout.sidebar-item` — item del menú lateral.

### Uso recomendado de `x-layout.sidebar-item`

El componente `sidebar-item` acepta dos formas de icono:

- `iconPath` — ruta `d="..."` para renderizar un SVG con formato estándar.
- `icon` — fallback para HTML o emoji directo.

Ejemplo con `iconPath`:

```blade
<x-layout.sidebar-item
    label="Dashboard"
    href="#"
    active="true"
    iconPath="M3 12l2-2 7-7 7 7M13 5v6h6"
/>
```

Ejemplo con `icon`:

```blade
<x-layout.sidebar-item label="Sidebar" href="#" icon="▣" />
```

Esto mantiene el markup consistente y evita repetir el wrapper SVG.

---

## 3. Componentes de layout

### `x-layout.topbar`

Props:
- `showToggle` (boolean, default `true`) — muestra el botón de toggle del sidebar.

Slots:
- `left` — contenido del lado izquierdo.
- `right` — contenido del lado derecho.

Ejemplo:

```blade
<x-layout.topbar>
    <x-slot name="left">
        <div class="flex items-center gap-md">
            <span class="text-ink font-semibold text-sm">Mi app</span>
        </div>
    </x-slot>

    <x-slot name="right">
        <button class="text-ink-soft">Acción</button>
    </x-slot>
</x-layout.topbar>
```

### `x-layout.sidebar`

Props:
- `width` — puede pasar un ancho personalizado como `220px`.

Slots:
- `footer` — pie del sidebar.
- `slot` — items de navegación.

Ejemplo:

```blade
<x-layout.sidebar width="220px" id="sidebar">
    <x-layout.sidebar-item label="Dashboard" href="#" iconPath="M3 12l2-2 7-7 7 7M13 5v6h6" />

    <x-slot name="footer">
        <div class="text-sm text-ink-soft">Usuario Demo</div>
    </x-slot>
</x-layout.sidebar>
```

### `x-layout.sidebar-item`

Props:
- `label` — texto.
- `href` — enlace.
- `active` — estado activo.
- `iconPath` — path SVG para icono.
- `icon` — icono HTML/emoji de reserva.
- `submenu` — habilita renderizado de submenu.
- `id` — id opcional.

Ejemplo de submenu:

```blade
<x-layout.sidebar-item label="Submenú" href="#" submenu="true" iconPath="M6 9l6 6 6-6" >
    <x-layout.sidebar-item label="Opción A" href="#" />
    <x-layout.sidebar-item label="Opción B" href="#" />
</x-layout.sidebar-item>
```

---

## 4. Componentes UI disponibles

### `x-ui.button`

Props:
- `variant`: `primary`, `secondary`, `ghost`, `danger`.
- `size`: `sm`, `md`, `lg`.
- `href`: si se pasa, renderiza un `<a>` en lugar de `<button>`.

Ejemplo:

```blade
<x-ui.button variant="primary">Guardar</x-ui.button>
<x-ui.button variant="secondary" size="sm">Cancelar</x-ui.button>
<x-ui.button href="/link">Link</x-ui.button>
```

### `x-ui.card`

Props:
- `variant`: `default`, `flat`, `highlighted`, `stats`.

Slots:
- `icon` — icono opcional en el header.
- `title` — título del card.
- `body` — contenido principal.
- `footer` — pie de tarjeta.

Ejemplo:

```blade
<x-ui.card variant="highlighted">
    <x-slot name="title">Resumen</x-slot>
    <x-slot name="body">
        <p class="text-ink-soft">Contenido de ejemplo.</p>
    </x-slot>
    <x-slot name="footer">
        <x-ui.button variant="ghost">Ver más</x-ui.button>
    </x-slot>
</x-ui.card>
```

### `x-ui.badge`

Props:
- `variant`: `ok`, `low`, `out`, `neutral`.

Ejemplo:

```blade
<x-ui.badge variant="ok">Stock OK</x-ui.badge>
```

### `x-ui.price`

Props:
- `variant`: `regular`, `sale`, `previous`.

Ejemplo:

```blade
<x-ui.price>$89.99</x-ui.price>
<x-ui.price variant="previous">$124.99</x-ui.price>
```

### `x-ui.alert`

Props:
- `variant`: `info`, `success`, `warning`, `danger`.

Ejemplo:

```blade
<x-ui.alert>Información importante.</x-ui.alert>
<x-ui.alert variant="danger">Error crítico.</x-ui.alert>
```

### `x-ui.data-table`

Props:
- `header` — slot opcional para la cabecera de la tabla.

Ejemplo:

```blade
<x-ui.data-table>
    <x-slot name="header">
        <tr>
            <th class="px-md py-sm">Producto</th>
            <th class="px-md py-sm">Stock</th>
        </tr>
    </x-slot>
    <tr>
        <td class="px-md py-sm">Taladro</td>
        <td class="px-md py-sm">56</td>
    </tr>
</x-ui.data-table>
```

### `x-ui.product-card`

Slots:
- `image`
- `title`
- `subtitle`
- `description`
- `price`
- `previousPrice`
- `stockText`
- `stockBadge`
- `footer`

Ejemplo:

```blade
<x-ui.product-card>
    <x-slot name="image">
        <img src="/img/producto.png" alt="Producto" class="w-full" />
    </x-slot>
    <x-slot name="title">Taladro</x-slot>
    <x-slot name="subtitle">HP-2000</x-slot>
    <x-slot name="description">Taladro profesional con 1200W.</x-slot>
    <x-slot name="price"><x-ui.price>$89.99</x-ui.price></x-slot>
    <x-slot name="previousPrice">$124.99</x-slot>
    <x-slot name="stockText">Stock: 42</x-slot>
    <x-slot name="stockBadge"><x-ui.badge variant="ok">OK</x-ui.badge></x-slot>
    <x-slot name="footer">
        <x-ui.button class="w-full">Agregar</x-ui.button>
    </x-slot>
</x-ui.product-card>
```

### Formularios

#### `x-forms.input`

Props:
- `label`
- `name`
- `type`
- `value`
- `placeholder`
- `required`

Ejemplo:

```blade
<x-forms.input label="Nombre" name="name" placeholder="Ingresa tu nombre" required />
```

#### `x-forms.select`

Props:
- `label`
- `name`
- `options`
- `value`
- `required`

Ejemplo:

```blade
<x-forms.select label="Categoría" name="category" :options="$categories" />
```

---

## 5. Ejemplo de página con layout base

```blade
@extends('layout.base')

@section('title', 'Dashboard')
@section('pageTitle', 'Panel de Control')
@section('appVersion', 'v0.1.0')

@section('content')
    <div class="space-y-lg">
        <x-ui.card>
            <x-slot name="title">Bienvenido</x-slot>
            <x-slot name="body">
                <p class="text-ink-soft">Esta página utiliza el layout base y los componentes UI.</p>
            </x-slot>
        </x-ui.card>
    </div>
@endsection
```

---

## 6. Buenas prácticas

- Usa siempre `layout.base` para páginas principales.
- Define solo los slots que necesites en cada componente.
- Prefiere `iconPath` en `sidebar-item` cuando uses SVGs de icono.
- Mantén los componentes UI limpios y sin lógica de presentación compleja.
- Reusa componentes existentes en lugar de crear HTML manualmente.
