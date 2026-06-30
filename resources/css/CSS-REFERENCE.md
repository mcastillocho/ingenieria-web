# 📚 Guía de Referencia — Ferretería Abad (Tailwind v4 + Blade)

> Documentación de estilos, tokens de diseño y arquitectura del proyecto.
> Tipografía: **Inter** (headings + body) — Google Fonts
> Paleta: **Slate (neutro) + Blue (acento funcional)** | Panel interno, sin dark mode
> Stack: **Tailwind CSS v4** (tokens vía `@theme` en CSS) + **componentes Blade** (Laravel)
> Enfoque: legibilidad, densidad de información y reducción de fatiga visual sobre estética de marketing.

---

## 📁 Estructura de Archivos

```
resources/
├── css/
│   └── app.css                  # @theme con tokens + @layer base
│
└── views/
    └── components/
        ├── layout/
        │   ├── navbar.blade.php
        │   ├── sidebar.blade.php
        │   └── topbar.blade.php
        │
        ├── ui/
        │   ├── button.blade.php       # variant: primary | secondary | ghost | danger
        │   ├── card.blade.php         # variant: default | flat | highlighted
        │   ├── product-card.blade.php
        │   ├── price.blade.php        # variant: regular | sale | inactive
        │   ├── badge.blade.php        # variant: ok | low | out | neutral
        │   ├── data-table.blade.php
        │   ├── chart-panel.blade.php
        │   ├── alert.blade.php        # variant: info | success | warning | danger
        │   └── modal.blade.php
        │
        └── forms/
            ├── input.blade.php
            └── select.blade.php
```

> **Regla de crecimiento:** si un componente Blade supera ~80-100 líneas o acumula
> demasiadas variantes condicionales, dividirlo en sub-componentes (ej. `card-header`,
> `card-body`, `card-footer` como slots nombrados en vez de un solo archivo).

---

## 🎨 Tokens de Diseño (`resources/css/app.css` → `@theme`)

> En Tailwind v4 los tokens se declaran en CSS con `@theme`, no en `tailwind.config.js`.
> El namespace del nombre (`--color-*`, `--font-*`, `--spacing-*`, `--radius-*`, `--shadow-*`)
> determina qué utilities genera Tailwind automáticamente (`bg-canvas`, `p-lg`, `rounded-xl`...).

### Tipografía

```css
--font-heading: "Inter", sans-serif;
--font-body: "Inter", sans-serif;
```

> Clases generadas: `font-heading`, `font-body`.
> Una sola familia tipográfica para todo el panel: en un entorno de trabajo, mezclar
> dos fuentes solo añade ruido visual sin aportar jerarquía real (la jerarquía aquí
> se logra con peso y tamaño, no con cambio de tipografía).

> **Nomenclatura:** se mantiene la metáfora por **rol visual** (papel/tinta/líneas)
> en vez de nombres ligados al namespace (`--color-bg-primary` → redundante con
> `bg-bg-primary`), ya que un mismo color puede usarse en `bg-`, `text-` o `border-`
> según el contexto.

### Colores Base

| Token (`@theme`)        | Valor    | Clase Tailwind generada | Uso                                  |
| ------------------------ | -------- | -------------------------- | --------------------------------------- |
| `--color-canvas`        | #f8fafc  | `bg-canvas`                | Fondo general de la app                |
| `--color-canvas-alt`    | #f1f5f9  | `bg-canvas-alt`            | Sidebar, zonas secundarias              |
| `--color-surface`       | #ffffff  | `bg-surface`               | Cards, tablas, modales, navbar          |
| `--color-line`          | #e2e8f0  | `border-line`              | Inputs, divisores, bordes de tabla      |
| `--color-line-strong`   | #cbd5e1  | `border-line-strong`       | Bordes con más énfasis (cards activas)  |

### Colores de Texto

| Token                  | Valor    | Clase generada     | Uso                             |
| ------------------------ | -------- | --------------------- | -------------------------------- |
| `--color-ink`          | #0f172a  | `text-ink`            | Headings, texto principal       |
| `--color-ink-soft`     | #475569  | `text-ink-soft`       | Párrafos, descripciones         |
| `--color-muted`        | #94a3b8  | `text-muted`          | Labels, placeholders, metadatos |
| `--color-on-accent`    | #ffffff  | `text-on-accent`      | Texto sobre botones de acento   |

### Acento — Blue

| Token                  | Valor    | Clase generada       | Uso                                   |
| ------------------------ | -------- | ---------------------- | --------------------------------------- |
| `--color-accent`        | #2563eb  | `bg-accent` / `text-accent` | Acción primaria, links, foco de inputs |
| `--color-accent-hover`  | #1d4ed8  | `bg-accent-hover`     | Hover/pressed de botón primario        |
| `--color-accent-soft`   | #eff6ff  | `bg-accent-soft`      | Fondos sutiles, fila seleccionada       |
| `--color-accent-ring`   | #3b82f6  | `ring-accent-ring`    | Focus ring de inputs y botones          |

### Estados de Stock (uso semántico, no decorativo)

| Token                    | Valor    | Clase generada       | Uso                                |
| --------------------------- | -------- | ----------------------- | -------------------------------------- |
| `--color-stock-ok`        | #15803d  | `text-stock-ok`        | Texto del badge "stock OK"          |
| `--color-stock-ok-bg`     | #f0fdf4  | `bg-stock-ok-bg`       | Fondo del badge "stock OK"          |
| `--color-stock-low`       | #b45309  | `text-stock-low`       | Texto del badge "stock bajo"        |
| `--color-stock-low-bg`    | #fffbeb  | `bg-stock-low-bg`      | Fondo del badge "stock bajo"        |
| `--color-stock-out`       | #b91c1c  | `text-stock-out`       | Texto del badge "sin stock"         |
| `--color-stock-out-bg`    | #fef2f2  | `bg-stock-out-bg`      | Fondo del badge "sin stock"         |
| `--color-stock-neutral`   | #475569  | `text-stock-neutral`   | Texto del badge "descontinuado"     |
| `--color-stock-neutral-bg`| #f1f5f9  | `bg-stock-neutral-bg`  | Fondo del badge "descontinuado"     |

### Precios

| Token                  | Valor    | Clase generada      | Uso                              |
| ------------------------ | -------- | ---------------------- | ----------------------------------- |
| `--color-price`        | #0f172a  | `text-price`          | Precio regular                   |
| `--color-price-sale`   | #15803d  | `text-price-sale`     | Precio en oferta/descuento       |
| `--color-price-prev`   | #94a3b8  | `text-price-prev`     | Precio anterior (tachado)        |

### Estados generales (alertas, formularios)

| Token                    | Valor    | Clase generada      |
| --------------------------- | -------- | ---------------------- |
| `--color-success`        | #15803d  | `text-success`        |
| `--color-success-bg`     | #f0fdf4  | `bg-success-bg`       |
| `--color-warning`        | #b45309  | `text-warning`        |
| `--color-warning-bg`     | #fffbeb  | `bg-warning-bg`       |
| `--color-danger`         | #b91c1c  | `text-danger`         |
| `--color-danger-bg`      | #fef2f2  | `bg-danger-bg`        |
| `--color-info`           | #1d4ed8  | `text-info`           |
| `--color-info-bg`        | #eff6ff  | `bg-info-bg`          |

### Espaciado (escala 4px)

| Token            | Valor | Clase generada (ej. padding) |
| ------------------ | ----- | ------------------------------- |
| `--spacing-xs`    | 4px   | `p-xs`                          |
| `--spacing-sm`    | 8px   | `p-sm`                          |
| `--spacing-md`    | 16px  | `p-md`                          |
| `--spacing-lg`    | 24px  | `p-lg`                          |
| `--spacing-xl`    | 32px  | `p-xl`                          |

### Bordes y Sombras

| Token              | Valor                                  | Clase generada  | Uso                          |
| --------------------- | ----------------------------------------- | ------------------ | -------------------------------- |
| `--radius-md`        | 8px                                       | `rounded-md`      | Inputs, botones                  |
| `--radius-lg`        | 12px                                      | `rounded-lg`       | Cards, paneles                   |
| `--shadow-card`      | 0 1px 3px rgba(15,23,42,0.08)            | `shadow-card`      | Cards, tablas                    |
| `--shadow-elevated`  | 0 4px 12px rgba(15,23,42,0.10)           | `shadow-elevated`  | Modales, dropdowns               |

### Dimensiones de Layout

| Token              | Valor  | Uso                                                |
| --------------------- | ------ | ----------------------------------------------------- |
| `--spacing-navbar`   | 56px   | Altura de topbar (`h-navbar`)                         |
| `--spacing-sidebar`  | 240px  | Ancho de sidebar (`w-sidebar`)                        |
| `--container-max`    | 1440px | Ancho máximo de contenido principal                   |

---

## 🧱 Capas CSS (`app.css`)

Tailwind v4 organiza el CSS en **cascade layers** nativas (`base`, `components`, `utilities`),
que controlan prioridad sin depender del orden de escritura ni de especificidad de selectores.

| Capa            | Contenido en este proyecto                                              |
| ----------------- | --------------------------------------------------------------------------- |
| `base`           | Reset (`*`, `body`, `h1-h6`, `a`, `img`, `table`) — tipografía base, line-height para lectura densa |
| `components`     | **Solo** clases que JavaScript construye/togglea dinámicamente            |
| `utilities`      | Utilities custom puntuales (si surgen necesidades fuera de `@theme`)      |

### Regla para decidir `@layer components` vs. Blade + utilities

- Si la clase la aplica **Blade/PHP** al renderizar (`<x-ui.badge variant="low">`) →
  usar utilities de Tailwind directo en el componente, **sin** `@layer components`.
- Si la clase la construye **JavaScript en runtime** concatenando strings
  (ej. `` `toast-${type}` ``) o se togglea por JS sin pasar por Blade
  (ej. `classList.add('open')`) → necesita una clase fija en `@layer components`.

**Casos confirmados que van en `@layer components`:**
- `.toast` / `.toast-info` / `.toast-success` / `.toast-warning` / `.toast-danger`
  (JS arma el className dinámicamente al recibir respuestas AJAX de inventario).
- `.sidebar-link.active` si se toggla con JS vanilla en vez de Blade (con
  `request()->routeIs()` en Blade este caso desaparece).

**Todo lo demás** (botones, cards, badges, tablas, formularios, navbar, sidebar)
se implementa como componente Blade con utilities de Tailwind en el template.

---

## 🧩 Componentes Blade

### Layout

| Componente                  | Notas                                                              |
| ---------------------------- | -------------------------------------------------------------------- |
| `<x-ui.container>`          | Ancho máximo `container-max`, padding `px-lg`                       |
| `<x-layout.sidebar>`        | Fijo, `w-sidebar bg-canvas-alt border-r border-line`                |
| `<x-layout.topbar>`         | `h-navbar bg-surface border-b border-line`, sin sombra (flat)       |

### Botones (`ui/button.blade.php`)

| Variant       | Utilities                                                                |
| --------------- | ----------------------------------------------------------------------- |
| `primary`      | `bg-accent text-on-accent hover:bg-accent-hover`                       |
| `secondary`    | `border border-line-strong text-ink hover:bg-canvas-alt`               |
| `ghost`        | `text-ink-soft hover:bg-canvas-alt`                                    |
| `danger`       | `bg-danger text-on-accent hover:bg-danger-hover`                       |

Sin variante `cyan` ni `dark`: el panel no usa fondos oscuros ni acentos secundarios
de marca. Prop adicional `size="lg|sm"` se mantiene igual que en botones estándar.

### Cards (`ui/card.blade.php`) y Product Cards (`ui/product-card.blade.php`)

Slots nombrados (`icon`, `title`, `body`, `footer`). Variantes vía prop `variant`:
`default` (`bg-surface shadow-card`), `flat` (sin sombra, solo `border border-line`),
`highlighted` (`border-2 border-accent-ring`, para ítems seleccionados o en edición),
`stats` (`bg-surface border border-line shadow-card`, optimizado para KPI y tarjetas
de estadísticas).

`<x-ui.product-card>` añade slots específicos: `image`, `title`, `subtitle`, `sku`,
`description`, `price`, `previousPrice`, `stockText`, `stockBadge` y `footer`.

#### Product card — diseño y especificación

- Contenedor: `bg-surface border border-line rounded-lg shadow-card overflow-hidden`.
- Image wrapper: `h-40 bg-canvas-alt border-b border-line flex items-center justify-center text-muted overflow-hidden`.
- Body: `p-md` con layout interno `flex items-start justify-between gap-sm` en el
  header y `border-t border-line pt-md mt-md` en el footer de precios/stock.
- Texto:
  - Título: `font-semibold text-ink`
  - Subtítulo: `text-xs text-muted`
  - SKU: `text-xs uppercase tracking-wide text-muted mt-xs`
  - Descripción: `text-sm text-ink-soft mt-sm truncate-2`
  - Precio: `text-price font-bold text-lg`
  - Precio anterior: `text-price-prev text-sm line-through ml-sm`
  - Stock: `text-xs text-ink-soft`
- Badge de stock: usar `<x-ui.badge>` con variantes semánticas (`ok`, `low`, `out`, `neutral`).
- Footer: `px-md py-sm border-t border-line bg-canvas-alt`.
- Reglas de grid: en layout de catálogo, usar `items-start` y/o `self-start` para evitar
  que cards de altura variable se estiren verticalmente.

### Precios (`ui/price.blade.php`)

| Variant      | Utilities                                  |
| -------------- | --------------------------------------------- |
| `regular`     | `text-price font-semibold`                   |
| `sale`        | `text-price-sale font-semibold`              |
| `previous`    | `text-price-prev line-through text-sm`       |

### Badges de Stock (`ui/badge.blade.php`)

| Variant      | Utilities                                              |
| -------------- | ----------------------------------------------------------|
| `ok`          | `bg-stock-ok-bg text-stock-ok border border-green-200`    |
| `low`         | `bg-stock-low-bg text-stock-low border border-amber-200`  |
| `out`         | `bg-stock-out-bg text-stock-out border border-red-200`    |
| `neutral`     | `bg-stock-neutral-bg text-stock-neutral border border-line` |

### Tablas de Datos (`ui/data-table.blade.php`)

`bg-surface border border-line rounded-lg shadow-card`. Filas: `divide-y divide-line`,
hover de fila `hover:bg-canvas-alt` (o se aplica automáticamente con `.data-table tbody tr:hover`),
fila seleccionada `bg-accent-soft`. Encabezado fijo (`sticky top-0 bg-surface`) para tablas largas de inventario.

### Paneles de Gráficos (`ui/chart-panel.blade.php`)

`bg-surface border border-line rounded-lg shadow-card p-lg`, con slot `title` (
`text-ink-soft text-sm font-medium`) y slot `actions` (filtros de rango de fecha,
alineados a la derecha del header del panel).

### Alertas (`ui/alert.blade.php`) y Modal (`ui/modal.blade.php`)

Alert: prop `variant` (`info|success|warning|danger`), sin JS — puro Blade + utilities.

- Contenedor: `rounded-lg border px-md py-sm text-sm`.
- Variantes:
  - `info`: `bg-info-bg border-blue-200 text-info`
  - `success`: `bg-success-bg border-green-200 text-success`
  - `warning`: `bg-warning-bg border-amber-200 text-warning`
  - `danger`: `bg-danger-bg border-red-200 text-danger`

Estas alertas se usan para mensajes de estado/octave en el panel, y deben poder insertarse sin markup extra.
Modal: vía Alpine.js (incluido en Laravel), `x-show`/`x-transition`.

### Toast

Ver sección "Capas CSS" — único componente cuya clase vive en CSS (`@layer components`)
porque el trigger es JS vanilla con `type` dinámico (ej. confirmación de actualización
de stock vía AJAX).

---

## 🎓 Buenas Prácticas

### 1. Siempre usar tokens de `@theme`, nunca valores literales

```blade
{{-- ✅ --}}
<div class="bg-accent text-on-accent">

{{-- ❌ --}}
<div style="background:#2563eb; color:#fff">
```

### 2. Sin elementos de marketing

No usar hero sections, animaciones de entrada, degradados decorativos ni
`heading-gradient`. Las transiciones se limitan a estados funcionales (hover,
focus, loading) con duraciones cortas (`transition-colors duration-150`).

### 3. Badges de stock: solo color + texto, nunca solo color

```blade
{{-- ✅ el texto refuerza el significado, no depende solo del color --}}
<x-ui.badge variant="low">Stock bajo</x-ui.badge>

{{-- ❌ accesibilidad: un punto de color sin texto no es suficiente --}}
<span class="w-2 h-2 rounded-full bg-amber-500"></span>
```

### 4. Densidad de información en tablas

Padding vertical de celda `py-sm` (no `py-md`) para maximizar filas visibles sin
scroll en pantallas de trabajo estándar (1080p). Texto de celda `text-sm`, headers
`text-xs uppercase text-muted tracking-wide`.

### 5. JS dinámico → siempre `@layer components`, nunca utilities armadas en runtime

```js
// ✅ clase fija definida en CSS
toast.className = `toast toast-${type}`;

// ❌ Tailwind no puede generar esto si se arma en runtime
toast.className = `fixed bg-${type === 'success' ? 'success-bg' : 'danger-bg'} ...`;
```

---

**Última actualización:** 30 Junio 2026
**Versión:** 0.1.0 (panel admin — Ferretería Abad)
