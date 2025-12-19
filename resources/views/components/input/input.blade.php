@props([
    'label' => '',
    'name' => '',
    'required' => false,
    'tip' => '',
    'id' => '',
    'type' => '',
    'value' => '',
    'readonly' => '',
    'disabled' => '',
    'oninput' => '',
    'hide' => false,
    'class' => '',
    'min' => 0,
    'parent_class' => '',
    'placeholder' => '',
    'step' => '',
])
<div class="{{ $hide ? 'hidden' : '' }} {{ $parent_class }}">
    <label class="block mb-2 text-sm font-semibold text-black dark:text-white" for="{{ $id }}">
        {{ $label }} {!! $required ? '<span class="text-red-600">*</span>' : '' !!}
    </label>
    <input type="{{ $type }}" id="{{ $id }}" {{ $readonly ? 'readonly' : '' }}
        {{ $disabled ? 'disabled' : '' }} {!! $oninput ? "oninput=\"$oninput\"" : '' !!} {{ $required ? 'required' : '' }}
        class="bg-gray-50 px-4 border border-gray-300 text-gray-900 text-sm rounded-md focus:ring-primary focus:border-primary block w-full p-2 {{ $class }}"
        name="{{ $name }}" value="{{ $value }}" min="{{ $min }}"
        placeholder="{{ $placeholder }}" step="{{ $step }}" />
    @if ($tip)
        <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">{{ $tip }}</p>
    @endif
    @error($name)
        <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
    @enderror
</div>
