@extends('layouts.app')

@section('title', 'Email Pool - Admin')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">Email Pool</h3>
                </div>
                <div class="panel-body">
                    <div class="mb-3">
                        <a href="{{ route('email-pool.export', request()->query()) }}" class="btn btn-success">
                            <i class="fas fa-download"></i> Export CSV
                        </a>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>From</th>
                                    <th>To</th>
                                    <th>Subject</th>
                                    <th>Type</th>
                                    <th>Status</th>
                                    <th>Created At</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($emailPools as $emailPool)
                                    <tr>
                                        <td>{{ $emailPool->id }}</td>
                                        <td>{{ $emailPool->from ?? '-' }}</td>
                                        <td>{{ $emailPool->to ?? '-' }}</td>
                                        <td>{{ Str::limit($emailPool->subject ?? '-', 50) }}</td>
                                        <td>
                                            @if($emailPool->type == 0)
                                                <span class="badge bg-info">General</span>
                                            @elseif($emailPool->type == 1)
                                                <span class="badge bg-success">Marketing</span>
                                            @elseif($emailPool->type == 2)
                                                <span class="badge bg-warning">Notification</span>
                                            @elseif($emailPool->type == 3)
                                                <span class="badge bg-danger">System</span>
                                            @else
                                                <span class="badge bg-default">Type {{ $emailPool->type }}</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($emailPool->status == 0)
                                                <span class="badge bg-secondary">Pending</span>
                                            @elseif($emailPool->status == 1)
                                                <span class="badge bg-success">Sent</span>
                                            @elseif($emailPool->status == 2)
                                                <span class="badge bg-danger">Failed</span>
                                            @elseif($emailPool->status == 3)
                                                <span class="badge bg-default">Cancelled</span>
                                            @else
                                                <span class="badge bg-default">Status {{ $emailPool->status }}</span>
                                            @endif
                                        </td>
                                        <td>{{ $emailPool->created_at->format('M d, Y H:i') }}</td>
                                        <td>
                                            <div class="btn-group">
                                                <a href="{{ route('email-pool.show', ['emailPool' => $emailPool->id]) }}" class="btn btn-sm btn-info" title="View">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                @if($emailPool->status == 0)
                                                    <form action="{{ route('email-pool.resend', ['emailPool' => $emailPool->id]) }}" method="POST" style="display:inline;" onsubmit="return confirm('Are you sure you want to resend this email?');">
                                                        @csrf
                                                        <button type="submit" class="btn btn-sm btn-warning" title="Resend">
                                                            <i class="fas fa-redo"></i>
                                                        </button>
                                                    </form>
                                                @endif
                                                <form action="{{ route('email-pool.destroy', ['emailPool' => $emailPool->id]) }}" method="POST" style="display:inline;" onsubmit="return confirm('Are you sure?');">
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
                                        <td colspan="8" class="text-center">No email pools found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="d-flex justify-content-center">
                        {{ $emailPools->appends(request()->query())->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
