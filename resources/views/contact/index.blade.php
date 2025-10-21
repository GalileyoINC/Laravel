@extends('layouts.app')

@section('content')
<div class="box">
    <div class="box-header with-border">
        <h3 class="box-title">Customers' Requests</h3>
    </div>
    
    <div class="box-body">
        <!-- Summary -->
        <div class="summary" style="margin-bottom:10px;">
            @if(method_exists($contacts, 'total') && $contacts->total() > 0)
                Showing <b>{{ $contacts->firstItem() }}-{{ $contacts->lastItem() }}</b> of <b>{{ $contacts->total() }}</b> items.
            @elseif(!method_exists($contacts, 'total') && count($contacts) > 0)
                Showing <b>1-{{ count($contacts) }}</b> of <b>{{ count($contacts) }}</b> items.
            @else
                Showing <b>0-0</b> of <b>0</b> items.
            @endif
        </div>

        <div class="table-responsive">
            <table class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Subject</th>
                        <th>Status</th>
                        <th>Created At</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($contacts as $contact)
                        <tr>
                            <td>{{ $contact->id }}</td>
                            <td>{{ $contact->name }}</td>
                            <td>{{ $contact->email }}</td>
                            <td>{{ $contact->subject ?? 'N/A' }}</td>
                            <td>
                                @if($contact->status == 1)
                                    <span class="badge bg-warning">New</span>
                                @elseif($contact->status == 2)
                                    <span class="badge bg-success">Replied</span>
                                @else
                                    <span class="badge bg-default">Closed</span>
                                @endif
                            </td>
                            <td>{{ $contact->created_at->format('M d, Y h:i a') }}</td>
                            <td>
                                <div class="btn-group">
                                    <a href="{{ route('contact.show', $contact) }}" class="btn btn-sm btn-info" title="View">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <form action="{{ route('contact.destroy', $contact) }}" method="POST" style="display:inline;" onsubmit="return confirm('Are you sure?');">
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
                            <td colspan="7" class="text-center">No contacts found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if(method_exists($contacts, 'links'))
            <div class="text-center">
                {{ $contacts->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
