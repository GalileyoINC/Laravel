@extends('layouts.app')

@section('content')
@php
use App\Helpers\TableFilterHelper;
@endphp

<x-table-filter 
    :title="'News'" 
    :data="$news"
    :columns="[
        TableFilterHelper::textColumn('ID', 'ID'),
        TableFilterHelper::textColumn('Name'),
        TableFilterHelper::textColumn('Title'),
        TableFilterHelper::selectColumn('Status', ['1' => 'Published', '0' => 'Draft']),
        TableFilterHelper::textColumn('Created At'),
        TableFilterHelper::clearButtonColumn(),
    ]"
>
    <x-slot name="headerActions">
        <a href="{{ route('news.create') }}" class="btn btn-success">
            <i class="fas fa-plus"></i> Create News
        </a>
    </x-slot>

    @forelse($news as $item)
        <tr class="data-row">
            <td @dataColumn(0)>{{ $item->id }}</td>
            <td @dataColumn(1)>{{ $item->name }}</td>
            <td @dataColumn(2)>{{ $item->title ?? 'N/A' }}</td>
            <td @dataColumn(3) @dataValue($item->status == 1 ? '1' : '0')>
                @if($item->status == 1)
                    <span class="badge bg-success">Published</span>
                @else
                    <span class="badge bg-default">Draft</span>
                @endif
            </td>
            <td @dataColumn(4)>{{ $item->created_at->format('M d, Y') }}</td>
            <td @dataColumn(5)>
                <div class="btn-group">
                    <a href="{{ route('news.show', $item) }}" class="btn btn-sm btn-info" title="View">
                        <i class="fas fa-eye"></i>
                    </a>
                    <a href="{{ route('news.edit', $item) }}" class="btn btn-sm btn-primary" title="Edit">
                        <i class="fas fa-pencil-alt"></i>
                    </a>
                    <a href="{{ route('news.content', $item) }}" class="btn btn-sm btn-warning" title="Content">
                        <i class="fas fa-file-alt"></i>
                    </a>
                    <form action="{{ route('news.destroy', $item) }}" method="POST" style="display:inline;" onsubmit="return confirm('Are you sure?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger" title="Delete">
                            <i class="fas fa-trash"></i>
                        </button>
                    </form>
                </div>
            </td>
        </tr>
    @empty
        <tr>
            <td colspan="6" class="text-center">No news found.</td>
        </tr>
    @endforelse
</x-table-filter>
@endsection
