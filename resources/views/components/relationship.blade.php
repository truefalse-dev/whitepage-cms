@php
use WhitePage\Facades\WhitePage;
@endphp

<div x-data="modalFormComponent()">
    <div class="overflow-x-auto bg-white rounded-lg shadow">
        <table class="min-w-full divide-y divide-gray-200">
            <tbody class="divide-y divide-gray-200">
                @foreach($relationship as $item)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $item->email }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $item->name }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-right space-x-2">
                            <button
                                @click.stop="openForm('{{ href(WhitePage::BACKEND_ROOT_PREFIX, 'user', 'form', $item->id) }}')"
                                class="inline-block bg-blue-500 text-white px-3 py-1 rounded hover:bg-blue-600 text-xs"
                            >
                                Edit
                            </button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Контейнер для модального вікна, створюється динамічно -->
        <template x-if="isOpen">
            <div
                x-show="isOpen"
                x-transition.opacity
                class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50"
            >
                <div
                    @click.outside="closeForm()"
                    @keydown.escape.window="closeForm()"
                    x-show="isOpen"
                    x-transition
                    class="bg-white rounded shadow-lg max-w-lg w-full max-h-[80vh] overflow-auto p-6"
                    role="dialog"
                    aria-modal="true"
                    aria-labelledby="modal-title"
                    tabindex="0"
                >
                    <h2 id="modal-title" class="text-xl font-semibold mb-4" x-text="title"></h2>
                    <div x-ref="formContainer" class="space-y-4" x-html="formHtml"></div>
                    <button
                        @click="closeForm()"
                        class="absolute top-3 right-3 text-gray-500 hover:text-gray-700"
                        aria-label="Закрити"
                    >
                        ✕
                    </button>
                    <button
                        @click="closeForm()"
                        class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700"
                    >
                        Закрити
                    </button>
                </div>
            </div>
        </template>
    </div>
</div>
