@props([
    'label' => '',
    'name' => '',
    'required' => false,
    'tip' => '',
    'id' => '',
    'type' => 'text',
    'value' => '',
    'readonly' => '',
    'disabled' => '',
    'hide' => false,
    'class' => '',
    'min' => 0,
    'parent_class' => '',
    'placeholder' => '',
    'input_group_text' => '',
    'input_group_position' => 'left',
    'step' => '',
])
<div class="{{ $hide ? 'hidden' : '' }} {{ $parent_class }}">
    <label for="{{ $id }}" class="block mb-2 text-sm font-semibold text-gray-900">
        {{ $label }} {!! $required ? '<span class="text-red-600">*</span>' : '' !!}
    </label>
    <div class="flex">
        @if ($input_group_position == 'left')
            <div class="flex">
                <span
                    class="inline-flex items-center px-3 text-sm text-gray-900 bg-gray-200 border rounded-e-0 border-gray-300 border-e-0 rounded-s-md">
                    {{ $input_group_text }}
                </span>
                <input type="{{ $type }}" id="{{ $id }}" {{ $readonly ? 'readonly' : '' }}
                    {{ $disabled ? 'disabled' : '' }} {{ $required ? 'required' : '' }}
                    class="rounded-none rounded-e-md bg-gray-50 border text-gray-900 block flex-1 min-w-0 w-full text-sm border-gray-300 focus:ring-primary focus:border-primary {{ $class }}"
                    name="{{ $name }}" value="{{ $value }}" min="{{ $min }}"
                    placeholder="{{ $placeholder }}" step="{{ $step }}">
            </div>
        @else
            <input type="{{ $type }}" id="{{ $id }}" {{ $readonly ? 'readonly' : '' }}
                {{ $disabled ? 'disabled' : '' }} {{ $required ? 'required' : '' }}
                class="rounded-none rounded-s-md bg-gray-50 border text-gray-900 block flex-1 min-w-0 w-full text-sm border-gray-300 focus:ring-primary focus:border-primary {{ $class }}"
                name="{{ $name }}" value="{{ $value }}" min="{{ $min }}"
                placeholder="{{ $placeholder }}" step="{{ $step }}">
            <span
                class="inline-flex items-center px-3 text-sm text-gray-900 bg-gray-200 border rounded-s-0 border-gray-300 border-s-0 rounded-e-md">
                {{ $input_group_text }}
            </span>
        @endif
    </div>
    @if ($tip)
        <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">{{ $tip }}</p>
    @endif
    @error($name)
        <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
    @enderror
</div>
