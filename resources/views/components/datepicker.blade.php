
@props(['id', 'name', 'value' => '', 'label']);

<div class="relative">
    <!-- Floating label and input field -->
    <label for="{{ $id }}" class="absolute text-sm text-gray-400 left-3 top-1/2 transform -translate-y-1/2 transition-all">
        {{ $label }}
    </label>
    <input
        type="text"
        id="{{ $id }}"
        name="{{ $name }}"
        value="{{ old($name, $value) }}"
        placeholder="dd/mm/yyyy h:m"
        class="timePicker w-full rounded-md border border-gray-300 px-3 py-2 shadow-sm text-sm placeholder-transparent focus:border-blue-500 focus:ring-1 focus:ring-blue-500 focus:outline-none"
    />
</div>