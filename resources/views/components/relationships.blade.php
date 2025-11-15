@php
    use WhitePage\Facades\WhitePage;
    use WhitePage\Components\AbstractMethod;

    $section = app('section');
@endphp

@if(isset($relationships) && $relationships->count())
    <div class="max-w-xl mx-auto mt-5">
        @foreach($relationships as $relationship => $items)
            <div
                class="reloadable-component"
                x-ref="relationshipContainer"
                x-data="loadRelationshipComponent()"
                data-url="{{ href(WhitePage::BACKEND_ROOT_PREFIX, $section->getName(), AbstractMethod::RELATIONSHIP_METHOD, $section->getModelId(), $relationship) }}"
            >
                <div x-html="content"></div>
            </div>
        @endforeach
    </div>
@endif
