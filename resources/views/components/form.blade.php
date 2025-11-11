@php
    use WhitePage\Facades\WhitePage;
    use WhitePage\Builders\FormBuilder;
    use WhitePage\Components\AbstractMethod;

//    /** @var FormBuilder $form */
//    /** @var $method */
//    $fields = $form->getFields();
//    $action = $form->getAction($form->getModelId());
//    $formId = $form->getId();
@endphp

<div
    x-data="formComponent"
    data-url="{{ href(WhitePage::SERVICE_ROOT_PREFIX, $section, AbstractMethod::FORM_METHOD, $id ?? null) }}"
    backward-url="{{ href(WhitePage::CMS_ROOT_PREFIX, $section, AbstractMethod::LIST_METHOD) }}"
    class="space-y-4"
>
    <template x-for="field in fields" :key="field.name">
        <div>
            <template x-if="field.type === 'input'">
                <div>
                    <label x-text="field.label" class="block text-sm font-medium text-gray-700"></label>
                    <input
                        x-model="formData[field.name]"
                        :type="'text'"
                        @input="errors[field.name] = null"
                        :class="{'border-red-500': errors[field.name]}"
                        class="block w-full px-3 py-2 text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                    >
                    <template x-if="errors[field.name]">
                        <p class="mt-1 text-sm text-red-600" x-text="errors[field.name][0]"></p>
                    </template>
                </div>
            </template>
            <template x-if="field.type === 'toggle'">
                <div class="flex items-center space-x-3">
                    <label :for="field.name" class="font-medium text-gray-700 cursor-pointer select-none"
                           x-text="field.label"></label>
                    <button
                        type="button"
                        :id="field.name"
                        role="switch"
                        :aria-checked="formData[field.name] ? 'true' : 'false'"
                        tabindex="0"
                        @click="formData[field.name] = formData[field.name] ? 0 : 1"
                        :class="formData[field.name] ? 'bg-blue-600' : 'bg-gray-300'"
                        class="relative inline-flex h-6 w-11 rounded-full transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                    >
      <span
          :class="formData[field.name] ? 'translate-x-6' : 'translate-x-1'"
          class="pointer-events-none inline-block h-4 w-4 transform rounded-full bg-white shadow ring-0 transition duration-200"
      ></span>
                    </button>
                </div>
            </template>
            <template x-if="field.type === 'select'">
                <div>
                    <label x-text="field.label" class="block text-sm font-medium text-gray-700"></label>
                    <select
                        x-model="formData[field.name]"
                        @change="errors[field.name] = null"
                        :class="{'border-red-500': errors[field.name]}"
                        class="block w-full px-3 py-2 text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                    >
                        <template x-for="option in field.options" :key="option.id">
                            <option value='' :value="option.id" x-text="option.label"></option>
                        </template>
                    </select>
                    <template x-if="errors[field.name]">
                        <p class="mt-1 text-sm text-red-600" x-text="errors[field.name][0]"></p>
                    </template>
                </div>
            </template>
        </div>
    </template>
    <button x-on:click="submitForm" class="px-4 py-2 bg-blue-500 text-white rounded-md">Submit</button>
</div>

{{--<form id="form{{ $formId }}"--}}
{{--      x-data="{ loading: false }"--}}
{{--      @submit.prevent="loading = true; setTimeout(()=>loading = false, 500)"--}}
{{--      class="w-full space-y-8" method="post" enctype="multipart/form-data" action="{{ $action }}">--}}
{{--    @csrf--}}
{{--    @foreach($fields as $field)--}}
{{--            @php($field->setId($formId))--}}
{{--            @if($field->getType() === 'input')--}}
{{--                @include('whitepage.components.form.text_input', [--}}
{{--                    'error' => $field->getError()--}}
{{--                ])--}}
{{--            @endif--}}
{{--            @if($field->getType() === 'select')--}}
{{--                @include('whitepage.components.form.select', [--}}
{{--                    'error' => $field->getError()--}}
{{--                ])--}}
{{--            @endif--}}
{{--            @if($field->getType() === 'password')--}}
{{--                @include('whitepage.components.form.password_input', [--}}
{{--                    'error' => $field->getError()--}}
{{--                ])--}}
{{--            @endif--}}
{{--            @if($field->getType() === 'toggle')--}}
{{--                @include('whitepage.components.form.toggle')--}}
{{--            @endif--}}
{{--    @endforeach--}}
{{--    <div class="max-w-xl mx-auto mt-5">--}}
{{--        <button--}}
{{--            type="submit"--}}
{{--            class="relative flex items-center justify-center bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-10 rounded-lg transition disabled:opacity-70 mt-6"--}}
{{--            :disabled="loading"--}}
{{--        >--}}
{{--            <svg x-show="loading"--}}
{{--                 class="animate-spin h-5 w-5 mr-3 text-white absolute left-4"--}}
{{--                 xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">--}}
{{--                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>--}}
{{--                <path class="opacity-75" fill="currentColor"--}}
{{--                      d="M4 12a8 8 0 018-8V0C5.372 0 0 5.372 0 12h4z"/>--}}
{{--            </svg>--}}
{{--            <span x-show="!loading">Save</span>--}}
{{--            <span x-show="loading">Saving ...</span>--}}
{{--        </button>--}}
{{--    </div>--}}
{{--</form>--}}
