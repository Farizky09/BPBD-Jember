@props([
    'label' => '',
    'name' => '',
    'id' => '',
    'rows' => 3,
    'required' => false,
    'readonly' => false,
    'disabled' => false,
    'placeholder' => '',
    'value' => '',
    'class' => '',
    'parent_class' => '',
    'hide' => false,
])

<div class="{{ $hide ? 'hidden' : '' }} {{ $parent_class }}">
    @if ($label)
        <label for="{{ $id ?: $name }}" class="block text-sm font-medium text-gray-700 mb-2">
            {{ $label }} {!! $required ? '<span class="text-red-600">*</span>' : '' !!}
        </label>
    @endif
    <textarea
        id="{{ $id ?: $name }}"
        name="{{ $name }}"
        rows="{{ $rows }}"
        placeholder="{{ $placeholder }}"
        {{ $readonly ? 'readonly' : '' }}
        {{ $disabled ? 'disabled' : '' }}
        {{ $required ? 'required' : '' }}
        class="w-full px-4 py-2.5 rounded-md border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all {{ $class }}"
    >{{ old($name, $value) }}</textarea>

    @error($name)
        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
    @enderror
</div>
