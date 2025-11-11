@foreach($filters as $filter)
    @if($filter->getType() === 'select')
        @include('whitepage.components.filters.select')
    @endif
@endforeach
