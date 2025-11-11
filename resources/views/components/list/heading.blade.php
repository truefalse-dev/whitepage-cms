<div class="flex justify-end">
    <a
        href="{{ href(\WhitePage\Facades\WhitePage::CMS_ROOT_PREFIX, app('section')->getName(), 'store') }}"
        type="button"
        class="inline-flex items-center px-6 py-2 bg-green-600 hover:bg-green-700 text-white font-semibold rounded-lg shadow transition focus:outline-none focus:ring-2 focus:ring-green-400 focus:ring-offset-2"
    >
        <svg class="w-5 h-5 mr-2 -ml-1" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"
             xmlns="http://www.w3.org/2000/svg">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"></path>
        </svg>
        Create
    </a>
</div>
