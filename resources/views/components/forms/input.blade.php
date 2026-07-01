@props([
    'label' => null,
    'name',
    'type' => 'text',
    'value' => null,
    'placeholder' => null,
    'required' => false,
])

<div {{ $attributes->merge(['class' => 'w-full space-y-2']) }}>
    @if ($label)
        <label for="{{ $name }}" class="block text-sm font-medium text-ink">{{ $label }}</label>
    @endif

    <input
        id="{{ $name }}"
        name="{{ $name }}"
        type="{{ $type }}"
        value="{{ old($name, $value) }}"
        placeholder="{{ $placeholder }}"
        @if($required) required @endif
        class="w-full rounded-md border border-line bg-white px-md py-sm text-ink focus:border-accent focus:ring-2 focus:ring-accent-ring focus:outline-none"
    />
</div>
