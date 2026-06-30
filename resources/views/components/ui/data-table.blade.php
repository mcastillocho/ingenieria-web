@props(['header' => null])

<div {{ $attributes->merge(['class' => 'data-table bg-surface border border-line rounded-lg shadow-card overflow-hidden w-full']) }}>
    <div class="overflow-x-auto w-full">
        <table class="w-full min-w-full divide-y divide-line text-sm">
            @if ($header)
                <thead class="bg-canvas-alt text-left text-xs uppercase text-muted tracking-wide">
                    {{ $header }}
                </thead>
            @endif
            <tbody class="divide-y divide-line text-ink-soft">
                {{ $slot }}
            </tbody>
        </table>
    </div>
</div>
