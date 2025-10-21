@extends('layouts.app')

@section('title', 'Pages - Admin')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Pages</h1>
        <a href="{{ route('page.create') }}" class="btn btn-success">
            <i class="fas fa-plus"></i> Create Page
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            @foreach ($errors->all() as $error)
                {{ $error }}<br>
            @endforeach
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <div class="card shadow mb-4">
        <div class="card-body">
            @php
            use App\Helpers\TableFilterHelper;
            @endphp
            <x-table-filter 
                :title="'Pages'" 
                :data="$pages"
                :columns="[
                    TableFilterHelper::textColumn('ID', 'ID', 'grid__id'),
                    TableFilterHelper::textColumn('Name'),
                    TableFilterHelper::textColumn('Slug'),
                    TableFilterHelper::selectColumn('Status', ['1' => 'Published', '0' => 'Draft']),
                    TableFilterHelper::textColumn('Created At'),
                    TableFilterHelper::clearButtonColumn('Actions', 'action-column-1'),
                ]"
            >
                @forelse($pages as $page)
                    <tr class="data-row">
                        <td @dataColumn(0)>{{ $page->id }}</td>
                        <td @dataColumn(1)>{{ $page->name }}</td>
                        <td @dataColumn(2)>{{ $page->slug }}</td>
                        <td @dataColumn(3) @dataValue($page->status === 1 ? '1' : '0')>
                            @if($page->status === 1)
                                <span class="badge badge-success">Published</span>
                            @else
                                <span class="badge badge-warning">Draft</span>
                            @endif
                        </td>
                        <td @dataColumn(4)>{{ $page->created_at->format('M d, Y') }}</td>
                        <td @dataColumn(5)>
                            <div class="btn-group">
                                <a href="{{ route('page.edit', $page) }}" class="btn btn-sm btn-success">
                                    <i class="fas fa-pen-fancy fa-fw"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center">No pages found.</td>
                    </tr>
                @endforelse
            </x-table-filter>
        </div>
    </div>
</div>

<style>
.grid__id {
    width: 60px;
}
.action-column-1 {
    width: 100px;
}
.badge-success {
    background-color: #1cc88a;
}
.badge-warning {
    background-color: #f6c23e;
}
</style>

<script>
// Date range picker functionality
document.addEventListener('DOMContentLoaded', function() {
    const dateRangePicker = document.getElementById('dateRangePicker');
    if (dateRangePicker) {
        // You can integrate a proper date range picker library here
        dateRangePicker.addEventListener('focus', function() {
            this.type = 'date';
        });
    }
});
</script>
@endsection
