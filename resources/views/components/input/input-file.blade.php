@props([
    'id' => '',
    'name' => '',
    'label' => '',
    'multiple' => false,
    'required' => false,
    'class' => '',
    'parent_class' => '',
    'hide' => false,
])

<div class="{{ $hide ? 'hidden' : '' }} {{ $parent_class }}">
    @if ($label)
        <label class="block mb-2 text-sm font-medium text-gray-900" for="{{ $id ?: $name }}">
            {{ $label }} {!! $required ? '<span class="text-red-500">*</span>' : '' !!}
        </label>
    @endif
    <input type="file" id="{{ $id ?: $name }}" name="{{ $name }}" {{ $multiple ? 'multiple' : '' }}
        {{ $required ? 'required' : '' }}
        class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 focus:outline-none {{ $class }}" />

    @error($name)
        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
    @enderror
</div>
