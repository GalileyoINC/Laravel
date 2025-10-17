<ul class="list-group">
    @foreach($categories[$idCategory] ?? [] as $category)
        <li class="list-group-item {{ $idActive == $category->id ? 'list-group-item-success' : '' }}">
            <a href="{{ route('web.subscription.index', ['idCategory' => $category->id]) }}">{{ $category->name }}</a>

            @if(!empty($categories[$category->id]))
                <br>
                <br>
                @include('web.subscription._categories', [
                    'categories' => $categories,
                    'idCategory' => $category->id,
                    'idActive' => $idActive,
                ])
            @endif
        </li>
    @endforeach
</ul>
