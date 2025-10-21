/**
 * Live Table Filter
 * Reusable JavaScript module for client-side table filtering
 * 
 * @version 1.0.0
 * @author Galileyo Team
 * 
 * Features:
 * - Real-time filtering as you type
 * - Debounced input (300ms) for better performance
 * - Support for text, select, and date filters
 * - Visual indicators for active filters
 * - Active filters counter badge
 * - Clear all filters button
 * 
 * Usage:
 *   initLiveFilter(document.querySelector('.live-filter-table'));
 * 
 * Requirements:
 * - Table rows must have class="data-row"
 * - Table cells must have data-column="X" attributes (X = 0, 1, 2, ...)
 * - Filter inputs must have class="filter-input" and data-column="X"
 * - Filter selects must have class="filter-select" and data-column="X"
 * - For boolean/enum columns, use data-value or data-status attributes
 */

function initLiveFilter(tableElement) {
    if (!tableElement) {
        console.warn('Live Filter: No table element provided');
        return;
    }

    const filterInputs = tableElement.querySelectorAll('.filter-input');
    const filterSelects = tableElement.querySelectorAll('.filter-select');
    const dataRows = tableElement.querySelectorAll('.data-row');
    const showingCount = tableElement.closest('.box-body').querySelector('.showing-count');
    const totalCount = tableElement.closest('.box-body').querySelector('.total-count');
    const noResultsMessage = tableElement.closest('.box-body').querySelector('.no-results-message');
    const clearButton = tableElement.querySelector('.clear-filters-btn');
    const noResultsRow = tableElement.querySelector('.no-results-row');
    const activeFiltersBadge = tableElement.closest('.box-body').querySelector('.active-filters-badge');
    const activeFiltersCount = tableElement.closest('.box-body').querySelector('.active-filters-count');
    
    // Hide the default "no results" row if it exists
    if (noResultsRow) {
        noResultsRow.style.display = 'none';
    }
    
    // Set initial total count
    if (totalCount) {
        totalCount.textContent = dataRows.length;
    }
    
    /**
     * Debounce function for better performance
     */
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
    
    /**
     * Main filter function
     */
    function filterTable() {
        let visibleCount = 0;
        let activeFiltersCounter = 0;
        
        // Get all filter values and update visual indicators
        const filters = {};
        filterInputs.forEach(input => {
            const column = input.getAttribute('data-column');
            filters[column] = input.value.toLowerCase().trim();
            // Add visual indicator for active filters
            if (input.value.trim()) {
                input.classList.add('has-value');
                activeFiltersCounter++;
            } else {
                input.classList.remove('has-value');
            }
        });
        
        filterSelects.forEach(select => {
            const column = select.getAttribute('data-column');
            filters[column] = select.value.toLowerCase().trim();
            // Add visual indicator for active filters
            if (select.value) {
                select.classList.add('has-value');
                activeFiltersCounter++;
            } else {
                select.classList.remove('has-value');
            }
        });
        
        // Filter each row
        dataRows.forEach(row => {
            let showRow = true;
            
            // Check each filter
            for (let column in filters) {
                if (filters[column] === '') continue;
                
                const cell = row.querySelector(`td[data-column="${column}"]`);
                if (!cell) continue;
                
                // Check for data attributes first (for special columns like status, boolean)
                const dataValue = cell.getAttribute('data-value');
                const dataStatus = cell.getAttribute('data-status');
                
                if (dataValue) {
                    // Use data-value attribute for boolean/enum columns
                    if (dataValue.toLowerCase() !== filters[column]) {
                        showRow = false;
                        break;
                    }
                } else if (dataStatus) {
                    // Use data-status attribute for status columns
                    if (!dataStatus.toLowerCase().includes(filters[column])) {
                        showRow = false;
                        break;
                    }
                } else {
                    // For text columns, use textContent
                    const cellText = cell.textContent.toLowerCase().trim();
                    if (!cellText.includes(filters[column])) {
                        showRow = false;
                        break;
                    }
                }
            }
            
            // Show/hide row with smooth transition
            if (showRow) {
                row.style.display = '';
                visibleCount++;
            } else {
                row.style.display = 'none';
            }
        });
        
        // Update count
        if (showingCount) {
            showingCount.textContent = visibleCount;
        }
        
        // Update active filters badge
        if (activeFiltersBadge && activeFiltersCount) {
            if (activeFiltersCounter > 0) {
                activeFiltersCount.textContent = activeFiltersCounter;
                activeFiltersBadge.style.display = 'block';
            } else {
                activeFiltersBadge.style.display = 'none';
            }
        }
        
        // Show/hide no results message
        if (noResultsMessage) {
            if (visibleCount === 0 && dataRows.length > 0) {
                noResultsMessage.style.display = 'block';
            } else {
                noResultsMessage.style.display = 'none';
            }
        }
    }
    
    // Add event listeners with debounce for inputs
    const debouncedFilter = debounce(filterTable, 300);
    filterInputs.forEach(input => {
        input.addEventListener('input', debouncedFilter);
    });
    
    // Immediate filter for selects
    filterSelects.forEach(select => {
        select.addEventListener('change', filterTable);
    });
    
    // Clear filters button
    if (clearButton) {
        clearButton.addEventListener('click', function() {
            filterInputs.forEach(input => {
                input.value = '';
                input.classList.remove('has-value');
            });
            filterSelects.forEach(select => {
                select.value = '';
                select.classList.remove('has-value');
            });
            filterTable();
        });
    }
    
    // Initial filter (in case there are pre-filled values)
    filterTable();
}

// Export for use in modules
if (typeof module !== 'undefined' && module.exports) {
    module.exports = { initLiveFilter };
}
