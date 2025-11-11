<div class="max-w-xl mx-auto mt-5">
    <div
        x-data="{ on: {{ old('is_active', $field->getValue() ?? false) ? 'true' : 'false' }} }"
        class="flex items-center gap-3 mt-8"
    >
        <input type="hidden" name="{{ $field->getName() }}" :value="on ? 1 : 0">
        <button type="button"
                @click="on = !on"
                :aria-pressed="on"
                :class="on ? 'bg-blue-600' : 'bg-gray-300'"
                class="relative w-10 h-6 rounded-full transition focus:outline-none"
        >
            <span :class="on ? 'translate-x-2' : 'translate-x-0'"
                  class="absolute left-1 top-1 w-4 h-4 rounded-full bg-white transition-transform"></span>
        </button>
        <span class="text-gray-700 text-sm" x-text="on ? 'Active' : 'Non active'"></span>
    </div>
</div>
