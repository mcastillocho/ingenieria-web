{{--
  Product card component

  Props:
    - variant: visual variant string, default `default`.

  Slots / named variables:
    - image: HTML fragment para la imagen o placeholder.
    - title: nombre del producto.
    - subtitle: texto complementario corto.
    - sku: código del producto.
    - description: texto de apoyo breve.
    - price: precio actual.
    - previousPrice: precio anterior tachado.
    - stockText: texto de inventario como "Stock: 42".
    - stockBadge: badge semántico de estado.
    - footer: acción final, como botón o enlace.
--}}
@props(['variant' => 'default'])

<div {{ $attributes->merge(['class' => 'product-card bg-surface border border-line rounded-lg shadow-card overflow-hidden']) }}>
    @isset($image)
    <div class="h-40 bg-canvas-alt border-b border-line flex items-center justify-center text-muted overflow-hidden">
        {{ $image }}
    </div>
    @endisset

    <div class="p-md">
        <div class="flex items-start justify-between gap-sm">
            <div>
                @isset($title)
                <h4 class="font-semibold text-ink">{{ $title }}</h4>
                @endisset
                @isset($subtitle)
                <p class="text-xs text-muted">{{ $subtitle }}</p>
                @endisset
                @isset($sku)
                <p class="text-xs uppercase tracking-wide text-muted mt-xs">{{ $sku }}</p>
                @endisset
            </div>
            @isset($stockBadge)
            <div>{{ $stockBadge }}</div>
            @endisset
        </div>

        @isset($description)
        <p class="text-sm text-ink-soft mt-sm truncate-2">{{ $description }}</p>
        @endisset

        @if(isset($price) || isset($previousPrice) || isset($stockText))
        <div class="mt-md flex items-center justify-between border-t border-line pt-md">
            <div class="flex items-center gap-sm">
                @isset($price)
                <span class="text-price font-bold text-lg">{{ $price }}</span>
                @endisset
                @isset($previousPrice)
                <span class="text-price-prev text-sm line-through ml-sm">{{ $previousPrice }}</span>
                @endisset
            </div>
            @isset($stockText)
            <span class="text-xs text-ink-soft">{{ $stockText }}</span>
            @endisset
        </div>
        @endif
    </div>

    @isset($footer)
    <div class="px-md py-sm border-t border-line bg-canvas-alt">{{ $footer }}</div>
    @endisset
</div>