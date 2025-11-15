@php
    use WhitePage\Facades\WhitePage;
    use WhitePage\Components\AbstractMethod;
@endphp

<div x-data="tableComponent"
     data-url="{{ href(WhitePage::BACKEND_ROOT_PREFIX, $section, AbstractMethod::TABLE_METHOD)  }}" class="relative">
    <div class="mt-6 relative">

        <template x-for="filter in filters" :key="filter.name">
            <template x-if="filter.type === 'select'">
                <div class="w-full max-w-xs">
                    <label :for="filter.name" class="block text-sm font-medium text-gray-700 mb-2"
                           x-text="filter.label"></label>
                    <select
                        :id="filter.name"
                        x-model="formData[filter.name]"
                        x-on:change="formData.page = 1; update(filter.name, $event.target.value);"
                        class="block w-full px-3 py-2 text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                    >
                        <template x-for="option in filter.options">
                            <option :value="option.id" x-text="option.label"></option>
                        </template>
                    </select>
                </div>
            </template>
        </template>

        <button
            x-show="selectedItems.length > 0 && !isInitial"
            @click="deleteSelectedItems"
            :disabled="isLoading"
            class="mt-3 px-3 py-1 text-sm bg-red-600 text-white rounded-md hover:bg-red-700 disabled:bg-red-300 disabled:cursor-not-allowed z-20 relative opacity-0"
            :class="{ 'opacity-100': selectedItems.length > 0 }"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 scale-95"
            x-transition:enter-end="opacity-100 scale-100"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-end="opacity-0 scale-95"
        >
            Видалити вибране (<span x-text="selectedItems.length"></span>)
        </button>

        <!-- Items Grid Table -->
        <div class="mt-2 relative">
            <!-- Loading Overlay -->
            <div
                x-show="isLoading"
                class="absolute w-full h-full flex items-center justify-center bg-gray-100 bg-opacity-85 z-10"
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100"
                x-transition:leave="transition ease-in duration-200"
                x-transition:leave-end="opacity-0"
            >
                <div class="flex items-center space-x-2">
                    <svg class="animate-spin h-4 w-4 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none"
                         viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor"
                              d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <span class="text-sm text-gray-700">Loading...</span>
                </div>
            </div>

            <div
                class="bg-white border border-gray-200 rounded-md shadow-sm overflow-x-auto"
                :class="{ 'min-h-[100px]': items.length > 1 || isLoading }"
            >
                <!-- Header -->
                <div
                    class="grid grid-cols-5 gap-4 px-4 py-2 bg-gray-50 border-b border-gray-200 text-sm font-medium text-gray-700 items-center"
                    :style="`grid-template-columns: 30px repeat(${columnsCount}, minmax(0, 1fr));`"
                >
                    <div class="flex items-center justify-center">
                        <input
                            type="checkbox"
                            x-model="selectAll"
                            x-on:change="selectAll ? selectedItems = items.map(item => item.id) : selectedItems = []"
                            :indeterminate="selectedItems.length > 0 && selectedItems.length < items.length"
                            class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"
                        >
                    </div>
                    <template x-for="title, key in columns">
                        <div x-text="title"></div>
                    </template>
                </div>
                <!-- Rows -->
                <template x-for="item in items" :key="item.id">
                    <a
                        x-bind="{ draggable: isDraggable ? true : false }"
                        x-on:dragstart="dragStart($event, item.id)"
                        x-on:dragend="dragEnd($event)"
                        x-on:dragover.prevent="dragOver($event, item.id)"
                        x-on:drop="drop($event, item.id)"
                        :data-id="item.id"
                        :href="item.href"
                        x-ref="row"
                        :class="dragOverId === item.id ? 'highlight-border-top' : ''"
                        class="block"
                    >
                        <div
                            class="grid grid-cols-5 gap-4 px-4 py-2 border-b border-gray-200 text-sm text-gray-900 items-center"
                            :style="`grid-template-columns: 30px repeat(${columnsCount}, minmax(0, 1fr));`"
                        >
                            <div class="flex items-center justify-center">
                                <input
                                    type="checkbox"
                                    :value="item.id"
                                    x-model="selectedItems"
                                    x-on:change="updateSelectAll"
                                    class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"
                                >
                            </div>

                            <template x-for="title, key in columns">
                                <div x-text="item[key]"></div>
                            </template>
                        </div>
                    </a>
                </template>
                <!-- No Results -->
                <div x-show="items.length === 0 && !isInitial"
                     class="grid grid-cols-5 gap-4 px-4 py-4 text-center text-sm text-gray-500">
                    <div class="col-span-5">Товари не знайдено</div>
                </div>
            </div>
        </div>


        <div class="flex items-center justify-between mt-6">
            <div class="flex space-x-1">
                <!-- Page Numbers -->
                <template x-for="(page, index) in pages()" :key="index">
                    <button
                        class="px-3 py-1 rounded text-sm"
                        :class="{ 'bg-indigo-600 text-white': index + 1 === formData.page }"
                        x-on:click="update('page', index + 1)"
                    >
                        <span x-text="index + 1"></span>
                    </button>
                </template>
            </div>
            <div class="flex items-center space-x-2">
                <!-- limit -->
                <label for="limit" class="text-sm text-gray-700">Ліміт:</label>
                <select
                    x-model="formData.limit"
                    x-on:change="formData.page = 1; update('limit', $event.target.value)"
                    class="px-2 py-1 text-sm text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                >
                    <option value="5">5</option>
                    <option value="10">10</option>
                    <option value="15">15</option>
                    <option value="20">20</option>
                </select>
            </div>
        </div>
    </div>
</div>

{{--@if($rows->count())--}}
{{--<form class="py-4" id="table" method="post" enctype="multipart/form-data" action="{{ $action }}">--}}
{{--    <input type="hidden" name="table-control" value="{{ inputs() }}">--}}
{{--        @include('whitepage.components.filters', compact('filters'))--}}
{{--        <!-- Таблиця на Tailwind CSS -->--}}
{{--        <div class="overflow-x-auto bg-white rounded-lg shadow">--}}
{{--            <button--}}
{{--                class="mb-4 px-4 py-2 bg-red-600 text-white rounded disabled:opacity-50"--}}
{{--                :disabled="selected.length === 0"--}}
{{--                @click="deleteSelected()"--}}
{{--            >--}}
{{--                Видалити вибране--}}
{{--            </button>--}}
{{--            <table class="min-w-full divide-y divide-gray-200">--}}
{{--                <thead class="bg-gray-100">--}}
{{--                <tr>--}}
{{--                    <th></th>--}}
{{--                @foreach($titles as $title)--}}
{{--                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ $title }}</th>--}}
{{--                    @endforeach--}}
{{--                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Action</th>--}}
{{--                </tr>--}}
{{--                </thead>--}}
{{--                <tbody class="divide-y divide-gray-200">--}}
{{--                @foreach($rows as $row)--}}
{{--                <tr>--}}
{{--                    <td>--}}
{{--                        <input--}}
{{--                            type="checkbox"--}}
{{--                            :value="item.id"--}}
{{--                            x-model="selected"--}}
{{--                            class="mr-3 w-4 h-4 text-blue-600 rounded border-gray-300"--}}
{{--                        />--}}
{{--                    </td>--}}
{{--                    @foreach($row->toArray() as $column => $value)--}}
{{--                    <td class="px-6 py-4 whitespace-nowrap">{{ $value }}</td>--}}
{{--                    @endforeach--}}
{{--                    <td class="px-6 py-4 whitespace-nowrap text-right space-x-2">--}}
{{--                        <a--}}
{{--                            class="inline-block bg-blue-500 text-white px-3 py-1 rounded hover:bg-blue-600 text-xs"--}}
{{--                            href="{{ href(\App\Facades\WhitePage::CMS_ROOT_PREFIX, app('section')->getName(), 'update', $row['id']) }}"--}}
{{--                        >Edit</a>--}}
{{--                    </td>--}}
{{--                </tr>--}}
{{--                @endforeach--}}
{{--                </tbody>--}}
{{--            </table>--}}
{{--        </div>--}}
{{--        <nav class="flex justify-center my-4" aria-label="Pagination">--}}
{{--            <select class="table-control border border-gray-300 rounded mx-3 px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" name="limit">--}}
{{--                @if($limit == 5)--}}
{{--                    <option value="5" selected="selected">5</option>--}}
{{--                @else--}}
{{--                    <option value="5">5</option>--}}
{{--                @endif--}}
{{--                @if($limit == 10)--}}
{{--                    <option value="10" selected="selected">10</option>--}}
{{--                @else--}}
{{--                    <option value="10">10</option>--}}
{{--                @endif--}}
{{--                @if($limit == 20)--}}
{{--                    <option value="20" selected="selected">20</option>--}}
{{--                @else--}}
{{--                    <option value="20">20</option>--}}
{{--                @endif--}}
{{--            </select>--}}
{{--            <ul class="inline-flex -space-x-px pt-2">--}}
{{--                @foreach($links as $page)--}}
{{--                <li>--}}
{{--                    <a href="javascript:" data-page="{{ $page }}" class="table-control px-3 py-2 leading-tight text-gray-700 border border-gray-300 hover:bg-blue-700 hover:text-white font-semibold">{{ $page }}</a>--}}
{{--                </li>--}}
{{--                @endforeach--}}
{{--            </ul>--}}
{{--        </nav>--}}
{{--</form>--}}
{{--@endif--}}
