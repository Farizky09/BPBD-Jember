@props([
    'id' => '',
    'label' => '',
    'name' => '',
    'required' => false,
    'class' => '',
    'parent_class' => '',
    'disabled' => false,
])

<div class="w-full {{ $parent_class }}">
    @if ($label)
        <label class="block text-sm mb-2 font-semibold" for="{{ $id }}">
            {{ $label }} {!! $required ? '<span class="text-red-500">*</span>' : '' !!}
        </label>
    @endif
    <div class="w-full">
        <select id="{{ $id }}" name="{{ $name }}" {{ $required ? 'required' : '' }}
            class="select2 w-full  {{ $class }}"
            {{ $disabled ? 'disabled' : '' }}>
            {{ $slot }}
        </select>
    </div>
</div>


