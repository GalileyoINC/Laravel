@extends('layouts.app')

@section('title', 'Podcasts - Admin')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">Podcasts</h3>
                    <div class="panel-heading-actions">
                        <a href="{{ route('podcast.create') }}" class="btn btn-success">
                            <i class="fas fa-plus"></i> Create New
                        </a>
                    </div>
                </div>
                <div class="panel-body">
                    @php
                    use App\Helpers\TableFilterHelper;
                    @endphp

                    <div class="mb-3">
                        <a href="{{ route('podcast.export', request()->query()) }}" class="btn btn-success">
                            <i class="fas fa-download"></i> Export CSV
                        </a>
                    </div>

                    <x-table-filter 
                        :title="'Podcasts'" 
                        :data="$podcasts"
                        :columns="[
                            TableFilterHelper::textColumn('ID', 'ID', 'grid__id'),
                            TableFilterHelper::noFilterColumn('Image', 'action-column-1'),
                            TableFilterHelper::textColumn('Title'),
                            TableFilterHelper::textColumn('URL'),
                            TableFilterHelper::textColumn('Created At'),
                            TableFilterHelper::selectColumn('Type', ['audio' => 'Audio', 'video' => 'Video']),
                            TableFilterHelper::clearButtonColumn('Actions', 'action-column-1'),
                        ]"
                    >
                        @forelse($podcasts as $podcast)
                            <tr class="data-row">
                                <td @dataColumn(0)>{{ $podcast->id }}</td>
                                <td @dataColumn(1)>
                                    @if($podcast->image)
                                        <img src="{{ Storage::url($podcast->image) }}" alt="{{ $podcast->title }}" style="width: 128px;" class="img-thumbnail">
                                    @else
                                        <div class="no-image-placeholder" style="width: 128px; height: 72px; background-color: #f8f9fa; border: 1px solid #dee2e6; display: flex; align-items: center; justify-content: center; color: #6c757d;">
                                            No Image
                                        </div>
                                    @endif
                                </td>
                                <td @dataColumn(2)>{{ $podcast->title }}</td>
                                <td @dataColumn(3)>
                                    <a href="{{ $podcast->url }}" target="_blank" class="text-break">
                                        {{ Str::limit($podcast->url, 50) }}
                                    </a>
                                </td>
                                <td @dataColumn(4)>{{ $podcast->created_at->format('M d, Y') }}</td>
                                <td @dataColumn(5) @dataValue((string) $podcast->type)>
                                    @if($podcast->type == 1)
                                        <span class="badge bg-info">Audio</span>
                                    @elseif($podcast->type == 2)
                                        <span class="badge bg-success">Video</span>
                                    @else
                                        <span class="badge bg-default">Type {{ $podcast->type }}</span>
                                    @endif
                                </td>
                                <td @dataColumn(6)>
                                    <div class="btn-group">
                                        <a href="{{ route('podcast.edit', $podcast) }}" class="btn btn-sm btn-success" title="Edit">
                                            <i class="fas fa-pen-fancy fa-fw"></i>
                                        </a>
                                        <form action="{{ route('podcast.destroy', $podcast) }}" method="POST" style="display:inline;" onsubmit="return confirm('Are you sure you want to delete this podcast?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" title="Delete">
                                                <i class="fas fa-trash fa-fw"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center">No podcasts found.</td>
                            </tr>
                        @endforelse
                    </x-table-filter>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.panel-default {
    border: 1px solid #ddd;
}
.panel-heading {
    background-color: #f5f5f5;
    border-bottom: 1px solid #ddd;
    padding: 10px 15px;
    display: flex;
    justify-content: space-between;
    align-items: center;
}
.panel-heading-actions {
    display: flex;
    gap: 10px;
}
.panel-body {
    padding: 15px;
}
.grid__id {
    width: 60px;
}
.action-column-1 {
    width: 150px;
}
.label {
    display: inline-block;
    padding: 0.25em 0.6em;
    font-size: 75%;
    font-weight: 700;
    line-height: 1;
    text-align: center;
    white-space: nowrap;
    vertical-align: baseline;
    border-radius: 0.25rem;
}
.label-info {
    background-color: #17a2b8;
    color: #fff;
}
.label-success {
    background-color: #28a745;
    color: #fff;
}
.label-default {
    background-color: #6c757d;
    color: #fff;
}
.img-thumbnail {
    padding: 0.25rem;
    background-color: #fff;
    border: 1px solid #dee2e6;
    border-radius: 0.25rem;
    max-width: 100%;
    height: auto;
}
.text-break {
    word-break: break-all;
}
.table-responsive {
    overflow-x: auto;
}
.table {
    width: 100%;
    margin-bottom: 1rem;
    background-color: transparent;
}
.table th,
.table td {
    padding: 0.75rem;
    vertical-align: top;
    border-top: 1px solid #dee2e6;
}
.table thead th {
    vertical-align: bottom;
    border-bottom: 2px solid #dee2e6;
}
.table-striped tbody tr:nth-of-type(odd) {
    background-color: rgba(0, 0, 0, 0.05);
}
.btn-group {
    display: inline-flex;
    vertical-align: middle;
}
.btn-group .btn {
    position: relative;
    flex: 1 1 auto;
}
.btn-group .btn + .btn {
    margin-left: -1px;
}
.btn-group .btn:first-child {
    margin-left: 0;
}
.btn-xs {
    padding: 0.125rem 0.25rem;
    font-size: 0.75rem;
    line-height: 1.5;
    border-radius: 0.2rem;
}
.btn-success {
    color: #fff;
    background-color: #28a745;
    border-color: #28a745;
}
.btn-primary {
    color: #fff;
    background-color: #007bff;
    border-color: #007bff;
}
.btn-default {
    color: #333;
    background-color: #fff;
    border-color: #ccc;
}
.form-control {
    display: block;
    width: 100%;
    padding: 0.375rem 0.75rem;
    font-size: 1rem;
    line-height: 1.5;
    color: #495057;
    background-color: #fff;
    background-clip: padding-box;
    border: 1px solid #ced4da;
    border-radius: 0.25rem;
}
.form-inline .form-group {
    display: flex;
    flex: 0 0 auto;
    flex-flow: row wrap;
    align-items: center;
    margin-bottom: 0;
}
.form-inline .form-control {
    display: inline-block;
    width: auto;
    vertical-align: middle;
}
a {
    color: #007bff;
    text-decoration: none;
}
a:hover {
    color: #0056b3;
    text-decoration: underline;
}
</style>
@endsection
