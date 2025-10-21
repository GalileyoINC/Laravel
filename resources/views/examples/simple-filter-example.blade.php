@extends('layouts.app')

@section('content')
{{-- Example using Blade directives for cleaner code --}}

<x-simple-table-filter :title="'Products'">
    <table class="table table-striped table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
            <tr class="filters">
                <td><input type="text" class="form-control filter-input" @dataColumn(0) placeholder="ID"></td>
                <td><input type="text" class="form-control filter-input" @dataColumn(1) placeholder="Name"></td>
                <td>
                    <select class="form-control filter-select" @dataColumn(2)>
                        <option value="">All</option>
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                    </select>
                </td>
                <td>
                    <button type="button" class="btn btn-sm btn-warning clear-filters-btn">
                        <i class="fas fa-times"></i> Clear
                    </button>
                </td>
            </tr>
        </thead>
        <tbody>
            @foreach($products ?? [] as $product)
                <tr class="data-row">
                    <td @dataColumn(0)>{{ $product->id }}</td>
                    <td @dataColumn(1)>{{ $product->name }}</td>
                    <td @dataColumn(2) @dataValue($product->status)>
                        <span class="label label-{{ $product->status == 'active' ? 'success' : 'default' }}">
                            {{ ucfirst($product->status) }}
                        </span>
                    </td>
                    <td @dataColumn(3)>
                        <a href="#" class="btn btn-sm btn-primary">Edit</a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</x-simple-table-filter>
@endsection
