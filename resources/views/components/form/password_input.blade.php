@php($class = 'border-gray-300')

@if($error)
    @php($class = 'border-red-300')
@endif

<div class="max-w-xl mx-auto mt-5">
    <label for="{{ $field->getName() }}" class="block mb-3 text-sm font-medium text-gray-700">{{ $field->getLabel() }}</label>
    <div x-data="{
        password: '',
        show: false,
        generate() {
            const chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()_+{}:<>?';
            let pass = '';
            for (let i = 0; i < 12; i++) {
            pass += chars.charAt(Math.floor(Math.random() * chars.length));
            }
            this.password = pass;
            this.show = true; // показати пароль одразу після генерації (опційно)
            },
            toggleShow() {
            this.show = !this.show;
            }
        }"
    >
        <div class="relative">
            <input
                :type="show ? 'text' : 'password'"
                id="{{ $field->getName() }}"
                name="{{ $field->getName() }}"
                x-model="password"
                class="w-full shadow-sm bg-white border border-gray-300 rounded-lg py-2 px-4 pr-20 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                autocomplete="new-password"
                placeholder="{{ $field->getLabel() }}"
            />

            <!-- Кнопка показу/сховання -->
            <button
                type="button"
                @click="toggleShow"
                class="absolute right-10 top-1/4 text-gray-500 hover:text-blue-600 focus:outline-none"
                :aria-label="show ? 'Сховати пароль' : 'Показати пароль'"
                tabindex="-1"
            >
                <!-- Іконка ока -->
                <template x-if="!show">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                    </svg>
                </template>
                <!-- Іконка ока зі штрихом -->
                <template x-if="show">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a9.965 9.965 0 012.504-4.362M9.88 9.876a3 3 0 104.242 4.243m2.122-2.122A3 3 0 009.88 9.876m4.242 4.243L21 21M3 3l18 18" />
                    </svg>
                </template>
            </button>

            <!-- Кнопка генерації -->
            <button
                type="button"
                @click="generate"
                class="absolute right-2 top-1/4 text-gray-500 hover:text-blue-600 focus:outline-none"
                aria-label="Згенерувати пароль"
                tabindex="-1"
            >
                <!-- Іконка рандому (стрілочка круга) -->
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M4 4v5h.582M20 20v-5h-.581M5.5 9.5a7 7 0 0113 0M18.5 14.5a7 7 0 01-13 0"/>
                </svg>
            </button>
        </div>
        @if($error)
            <span class="text-red-400">{{ $error }}</span>
        @endif
    </div>
</div>
