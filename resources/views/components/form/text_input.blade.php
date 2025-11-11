@php($class = 'border-gray-300')

@if($error)
    @php($class = 'border-red-300')
@endif

<div class="max-w-xl mx-auto mt-5">
    <label for="{{ $field->getName() . $field->getId() }}" class="block mb-3 text-sm font-medium text-gray-700">{{ $field->getLabel() }}</label>
    <input
        type="text"
        id="{{ $field->getName() . $field->getId() }}"
        name="{{ $field->getName() }}"
        value="{{ $field->getValue() }}"
        class="block w-full shadow-sm bg-white border {{ $class }} text-gray-700 rounded-lg py-2 px-4 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
        placeholder="{{ $field->getLabel() }}"
        autocomplete="on"
    />
    @if($error)
        <span class="text-red-400">{{ $error }}</span>
    @endif
</div>
