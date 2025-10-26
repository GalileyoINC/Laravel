@extends('layouts.app')

@section('title', '{{ $isUnfinishedSignup == 1 ? "Unfinished Signups" : "Subscribed for newsletters" }} - Admin')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">{{ $isUnfinishedSignup == 1 ? 'Unfinished Signups' : 'Subscribed for newsletters' }}</h1>
        <div>
            <a href="{{ route('register.index-unique', ['signup' => $isUnfinishedSignup]) }}" class="btn btn-default">
                Show Unique
            </a>
            <a href="{{ route('register.to-csv', array_merge(['signup' => $isUnfinishedSignup], request()->query())) }}" class="btn btn-default">
                to .CSV
            </a>
        </div>
    </div>

    <div class="card shadow mb-4">
        <div class="card-body">
            @php
            use App\Helpers\TableFilterHelper;
            @endphp
            <x-table-filter 
                :title="$isUnfinishedSignup == 1 ? 'Unfinished Signups' : 'Subscribed for newsletters'" 
                :data="$registers"
                :columns="[
                    TableFilterHelper::textColumn('ID', 'ID', 'grid__id'),
                    TableFilterHelper::textColumn('Email'),
                    TableFilterHelper::textColumn('First Name'),
                    TableFilterHelper::textColumn('Last Name'),
                    TableFilterHelper::textColumn('Created At'),
                    TableFilterHelper::clearButtonColumn('Actions', 'action-column-1'),
                ]"
            >
                @forelse($registers as $register)
                    <tr class="data-row">
                        <td @dataColumn(0)>{{ $register->id }}</td>
                        <td @dataColumn(1)>{{ $register->email }}</td>
                        <td @dataColumn(2)>{{ $register->first_name }}</td>
                        <td @dataColumn(3)>{{ $register->last_name }}</td>
                        <td @dataColumn(4)>{{ $register->created_at->format('M d, Y') }}</td>
                        <td @dataColumn(5)>
                            <div class="btn-group">
                                <button 
                                    class="btn btn-sm {{ $register->is_unsubscribed ? 'btn-danger' : 'btn-success' }}" 
                                    onclick="toggleUnsubscribe({{ $register->id }}, this)"
                                    data-id="{{ $register->id }}"
                                    title="{{ $register->is_unsubscribed ? 'Click to subscribe' : 'Click to unsubscribe' }}"
                                >
                                    <i class="fas fa-{{ $register->is_unsubscribed ? 'ban' : 'check-circle' }}"></i>
                                    {{ $register->is_unsubscribed ? 'Unsubscribed' : 'Subscribed' }}
                                </button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center">No registers found.</td>
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
    width: 150px;
}
</style>

<script>
function toggleUnsubscribe(id, button) {
    // Disable button and show loading
    const originalHTML = button.innerHTML;
    button.disabled = true;
    button.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Loading...';
    
    fetch(`{{ url('admin/register') }}/${id}/toggle-unsubscribe`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === 'success') {
            // Update button appearance based on new status
            if (data.is_unsubscribed) {
                button.className = 'btn btn-sm btn-danger';
                button.innerHTML = '<i class="fas fa-ban"></i> Unsubscribed';
                button.title = 'Click to subscribe';
            } else {
                button.className = 'btn btn-sm btn-success';
                button.innerHTML = '<i class="fas fa-check-circle"></i> Subscribed';
                button.title = 'Click to unsubscribe';
            }
            
            // Re-enable button
            button.disabled = false;
            
            // Show success message
            const message = data.is_unsubscribed ? 'User unsubscribed from newsletter' : 'User subscribed to newsletter';
            showNotification(message, 'success');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        button.innerHTML = originalHTML;
        button.disabled = false;
        showNotification('Error updating subscription status', 'danger');
    });
}

function showNotification(message, type) {
    // Create a simple notification
    const notification = document.createElement('div');
    notification.className = `alert alert-${type} alert-dismissible fade show position-fixed`;
    notification.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
    notification.innerHTML = `
        ${message}
        <button type="button" class="close" data-dismiss="alert" onclick="this.parentElement.remove()">
            <span>&times;</span>
        </button>
    `;
    document.body.appendChild(notification);
    
    // Auto remove after 3 seconds
    setTimeout(() => {
        if (notification.parentNode) {
            notification.remove();
        }
    }, 3000);
}
</script>
@endsection
