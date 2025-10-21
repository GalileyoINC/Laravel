@extends('layouts.app')

@section('title', 'IEX Webhooks - Admin')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">IEX Webhooks</h3>
                    <div class="box-tools float-end">
                        <a href="{{ route('iex.export-webhooks', request()->query()) }}" class="btn btn-success btn-sm">
                            <i class="fas fa-download"></i> Export CSV
                        </a>
                    </div>
                </div>
                <div class="panel-body">
                    <!-- Table -->
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped" id="webhooks-table">
                            <thead>
                                <tr>
                                    <th class="grid__id">ID</th>
                                    <th>IEX ID</th>
                                    <th>Event</th>
                                    <th>Set</th>
                                    <th>Name</th>
                                    <th>Created At</th>
                                    <th>Updated At</th>
                                    <th class="action-column-1">Actions</th>
                                </tr>
                                <tr class="filters">
                                    <td></td>
                                    <td>
                                        <input type="text" class="form-control form-control-sm filter-input" placeholder="IEX ID" data-column="1">
                                    </td>
                                    <td>
                                        <input type="text" class="form-control form-control-sm filter-input" placeholder="Event" data-column="2">
                                    </td>
                                    <td>
                                        <input type="text" class="form-control form-control-sm filter-input" placeholder="Set" data-column="3">
                                    </td>
                                    <td>
                                        <input type="text" class="form-control form-control-sm filter-input" placeholder="Name" data-column="4">
                                    </td>
                                    <td>
                                        <input type="date" class="form-control form-control-sm filter-input" data-column="5">
                                    </td>
                                    <td>
                                        <input type="date" class="form-control form-control-sm filter-input" data-column="6">
                                    </td>
                                    <td></td>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($webhooks as $webhook)
                                    <tr>
                                        <td>{{ $webhook->id }}</td>
                                        <td>{{ $webhook->iex_id }}</td>
                                        <td>
                                            <span class="badge bg-info">{{ $webhook->event }}</span>
                                        </td>
                                        <td>{{ $webhook->set }}</td>
                                        <td>{{ $webhook->name }}</td>
                                        <td>{{ $webhook->created_at->format('Y-m-d') }}</td>
                                        <td>{{ $webhook->updated_at->format('Y-m-d') }}</td>
                                        <td>
                                            <div class="btn-group">
                                                <a href="{{ route('iex.webhook-show', $webhook) }}" class="btn btn-sm btn-info">
                                                    <i class="fas fa-eye"></i> View
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center">No webhooks found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const table = document.getElementById('webhooks-table');
    if (!table) return;
    
    const filterInputs = table.querySelectorAll('.filter-input');
    const tbody = table.querySelector('tbody');
    const rows = Array.from(tbody.querySelectorAll('tr')).filter(row => !row.classList.contains('no-results'));
    
    // Debounce function
    function debounce(func, wait) {
        let timeout;
        return function executedFunction(...args) {
            const later = () => {
                clearTimeout(timeout);
                func(...args);
            };
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
        };
    }
    
    // Filter function
    function filterTable() {
        const filters = Array.from(filterInputs).map(input => ({
            column: parseInt(input.dataset.column),
            value: input.value.toLowerCase().trim()
        }));
        
        let visibleCount = 0;
        
        rows.forEach(row => {
            const cells = row.querySelectorAll('td');
            let show = true;
            
            filters.forEach(filter => {
                if (filter.value && cells[filter.column]) {
                    const cellText = cells[filter.column].textContent.toLowerCase().trim();
                    if (!cellText.includes(filter.value)) {
                        show = false;
                    }
                }
            });
            
            row.style.display = show ? '' : 'none';
            if (show) visibleCount++;
        });
        
        // Show/hide no results message
        let noResultsRow = tbody.querySelector('.no-results');
        if (visibleCount === 0) {
            if (!noResultsRow) {
                noResultsRow = document.createElement('tr');
                noResultsRow.className = 'no-results';
                noResultsRow.innerHTML = '<td colspan="8" class="text-center">No matching records found.</td>';
                tbody.appendChild(noResultsRow);
            }
        } else if (noResultsRow) {
            noResultsRow.remove();
        }
    }
    
    // Attach event listeners with debounce
    const debouncedFilter = debounce(filterTable, 300);
    filterInputs.forEach(input => {
        input.addEventListener('input', debouncedFilter);
    });
});
</script>
@endpush

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
.panel-heading .panel-title {
    margin: 0;
    flex: 1;
}
.panel-heading .box-tools {
    margin: 0;
}
.panel-body {
    padding: 15px;
}
.filters td {
    padding: 5px !important;
    background-color: #f8f9fa;
}
.filters .form-control-sm {
    font-size: 0.875rem;
    padding: 0.25rem 0.5rem;
}
.grid__id {
    width: 60px;
}
.action-column-1 {
    width: 100px;
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
.btn-sm {
    padding: 0.25rem 0.5rem;
    font-size: 0.875rem;
    line-height: 1.5;
    border-radius: 0.2rem;
}
.btn-info {
    color: #fff;
    background-color: #17a2b8;
    border-color: #17a2b8;
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
.btn-success {
    color: #fff;
    background-color: #28a745;
    border-color: #28a745;
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
</style>
@endsection
