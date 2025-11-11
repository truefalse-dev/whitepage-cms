<div class="w-full max-w-xs">
    <label for="dropdown" class="block text-sm font-medium text-gray-700 mb-2">
        {{ $filter->getLabel() }}
    </label>
    <select
        id="dropdown"
        x-model="formData.{{ $filter->getName() }}"
        x-on:change="update('{{ $filter->getName() }}', $event.target.value)"
        class="block w-full px-3 py-2 text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
    >
        @foreach($filter->getOptions() as $option)
            <option value="{{ $option['id'] }}">{{ $option['label'] }}</option>
        @endforeach
    </select>
</div>
