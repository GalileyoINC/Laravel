{{-- 
    Simplified version of table-filter for quick conversion of existing tables
    Just wrap your existing table and add data-column attributes to rows
--}}

@props([
    'title' => 'Records',
    'showCount' => true,
    'showClearButton' => true,
])

<div class="box">
    @if($title)
        <div class="box-header with-border">
            <h3 class="box-title">{{ $title }}</h3>
            <div class="box-tools pull-right">
                {{ $headerActions ?? '' }}
            </div>
        </div>
    @endif
    
    <div class="box-body">
        @if($showCount)
            <div class="summary" style="margin: 0 0 10px 0; display: flex; justify-content: space-between; align-items: center;">
                <div>
                    Showing <b><span class="showing-count">0</span></b> of <b><span class="total-count">0</span></b> items.
                </div>
                <div class="active-filters-badge" style="display: none;">
                    <span class="label label-info">
                        <i class="fas fa-filter"></i> <span class="active-filters-count">0</span> active filter(s)
                    </span>
                </div>
            </div>
        @endif
        
        <div class="table-responsive">
            <div class="live-filter-table">
                {{ $slot }}
            </div>
        </div>

        <div class="no-results-message alert alert-info text-center" style="display: none; margin-top: 15px;">
            <i class="fas fa-search"></i> No results found matching your filters.
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const table = document.querySelector('.live-filter-table table');
    if (table) {
        initLiveFilter(table);
    }
});
</script>
@endpush
