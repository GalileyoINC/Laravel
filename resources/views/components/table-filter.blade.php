@props([
    'columns' => [],
    'data' => [],
    'routeName' => null,
    'exportRoute' => null,
    'title' => 'Records',
])

<div class="box">
    <div class="box-header with-border">
        <h3 class="box-title">{{ $title }}</h3>
        <div class="box-tools pull-right">
            @if($exportRoute)
                <a href="{{ $exportRoute }}" class="btn btn-default">
                    <i class="fas fa-download"></i> to .CSV
                </a>
            @endif
            {{ $headerActions ?? '' }}
        </div>
    </div>
    
    <div class="box-body">
        <div class="summary" style="margin: 0 0 10px 0; display: flex; justify-content: space-between; align-items: center;">
            <div>
                Showing <b><span class="showing-count">{{ is_countable($data) ? count($data) : 0 }}</span></b> of <b><span class="total-count">{{ is_countable($data) ? count($data) : 0 }}</span></b> items.
            </div>
            <div class="active-filters-badge" style="display: none;">
                <span class="badge bg-info">
                    <i class="fas fa-filter"></i> <span class="active-filters-count">0</span> active filter(s)
                </span>
            </div>
        </div>
        
        <div class="table-responsive">
            <table class="table table-striped table-bordered live-filter-table">
                <thead>
                    <tr>
                        @foreach($columns as $column)
                            <th class="{{ $column['class'] ?? '' }}">{{ $column['label'] }}</th>
                        @endforeach
                    </tr>
                    <tr class="filters">
                        @foreach($columns as $index => $column)
                            <td>
                                @if(isset($column['filter']) && $column['filter'] !== false)
                                    @if($column['filter']['type'] === 'text')
                                        <input 
                                            type="text" 
                                            class="form-control filter-input" 
                                            data-column="{{ $index }}" 
                                            placeholder="{{ $column['filter']['placeholder'] ?? $column['label'] }}"
                                        >
                                    @elseif($column['filter']['type'] === 'select')
                                        <select class="form-control filter-select" data-column="{{ $index }}">
                                            <option value="">All</option>
                                            @foreach($column['filter']['options'] as $value => $label)
                                                <option value="{{ $value }}">{{ $label }}</option>
                                            @endforeach
                                        </select>
                                    @elseif($column['filter']['type'] === 'date')
                                        <input 
                                            type="date" 
                                            class="form-control filter-input" 
                                            data-column="{{ $index }}"
                                        >
                                    @elseif($column['filter']['type'] === 'button')
                                        <button type="button" class="btn btn-sm btn-warning clear-filters-btn">
                                            <i class="fas fa-times"></i> Clear
                                        </button>
                                    @endif
                                @endif
                            </td>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    {{ $slot }}
                    <tr class="no-results-row" style="display: none;">
                        <td colspan="{{ count($columns) }}" class="text-center">No records found.</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="no-results-message alert alert-info text-center" style="display: none; margin-top: 15px;">
            <i class="fas fa-search"></i> No results found matching your filters.
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    initLiveFilter(document.querySelector('.live-filter-table'));
});
</script>
@endpush
