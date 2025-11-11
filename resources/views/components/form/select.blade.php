@php($class = 'border-gray-300')

@if($error)
    @php($class = 'border-red-500')
@endif

<div class="max-w-xl mx-auto mt-5">
    <label for="{{ $field->getName() }}" class="block mb-3 text-sm font-medium text-gray-700">{{ $field->getLabel() }}</label>
    <div x-data="{
        open: false,
        selected: null,
        init() {
            const currentVal = '{{ old('selected_option') ?: ($field->getValue() ?? '') }}';
            if (currentVal) {
                const found = this.options.find(o => o.id == currentVal);
                if (found) this.selected = found;
            }
        },
        options: {{ Js::from($field->getOptions()) }},
        selectOption(option) {
            this.selected = option;
            this.open = false;
        }
    }" x-init="init()" @click.away="open = false" class="relative">

        <input id="{{ $field->getName() }}" type="hidden" name="{{ $field->getName() }}" :value="selected ? selected.id : ''">

        <!-- Кнопка відкриття селекту -->
        <button @click="open = !open" type="button"
                class="w-full bg-white border border-gray-300 rounded-md shadow-sm pl-3 pr-10 py-2 text-left cursor-pointer focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                aria-haspopup="listbox" aria-expanded="open" aria-labelledby="listbox-label">

            <span class="block truncate" x-text="selected ? selected.label : 'Non selected'"></span>
            <span class="absolute inset-y-0 right-0 flex items-center pr-2 pointer-events-none">
                <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                     fill="currentColor" aria-hidden="true">
                    <path fill-rule="evenodd"
                          d="M5.23 7.21a.75.75 0 011.06.02L10 10.94l3.71-3.71a.75.75 0 011.08 1.04l-4.25 4.25a.75.75 0 01-1.08 0L5.21 8.27a.75.75 0 01.02-1.06z"
                          clip-rule="evenodd"/>
                </svg>
            </span>
        </button>

        <!-- Список опцій -->
        <ul x-show="open" x-transition
            class="absolute z-10 mt-1 w-full bg-white shadow-lg max-h-60 rounded-md py-1 text-base ring-1 ring-black ring-opacity-5 overflow-auto focus:outline-none sm:text-sm"
            role="listbox" aria-labelledby="listbox-label">
            <template x-for="option in options" :key="option.id">
                <li @click="selectOption(option)"
                    :class="{'text-white bg-blue-600': selected && selected.id === option.id, 'text-gray-900': !(selected && selected.id === option.id)}"
                    class="cursor-pointer select-none relative py-2 pl-3 pr-9" role="option" tabindex="0">
                    <span x-text="option.label"
                          :class="{'font-semibold': selected && selected.id === option.id, 'font-normal': !(selected && selected.id === option.id)}"
                          class="block truncate"></span>
                    <span x-show="selected && selected.id === option.id"
                          class="text-white absolute inset-y-0 right-0 flex items-center pr-4">
                        <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                             stroke="currentColor" stroke-width="3">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                        </svg>
                    </span>
                </li>
            </template>
        </ul>
    </div>
    @if($error)
        <span class="text-red-400">{{ $error }}</span>
    @endif
</div>
