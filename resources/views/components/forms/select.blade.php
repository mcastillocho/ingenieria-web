@props([
    'label' => null,
    'name',
    'options' => [],
    'value' => null,
    'required' => false,
])

<div {{ $attributes->merge(['class' => 'w-full space-y-2']) }}>
    @if ($label)
        <label for="{{ $name }}" class="block text-sm font-medium text-ink">{{ $label }}</label>
    @endif

    <select
        id="{{ $name }}"
        name="{{ $name }}"
        @if($required) required @endif
        class="w-full rounded-md border border-line bg-white px-md py-sm text-ink focus:border-accent focus:ring-2 focus:ring-accent-ring focus:outline-none"
    >
        @foreach ($options as $optionValue => $optionLabel)
            <option value="{{ $optionValue }}" @selected(old($name, $value) == $optionValue)>{{ $optionLabel }}</option>
        @endforeach
    </select>
</div>
