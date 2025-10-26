@extends('layouts.app')

@section('content')
<div class="box">
    <div class="box-header with-border">
        <h3 class="box-title">Email Templates</h3>
        <div class="box-tools pull-right"></div>
    </div>
    
    <div class="box-body">
        <!-- Summary -->
        <div class="summary" style="margin-bottom:10px;">
            @if($templates instanceof \Illuminate\Contracts\Pagination\Paginator && $templates->total() > 0)
                Showing <b>{{ $templates->firstItem() }}-{{ $templates->lastItem() }}</b> of <b>{{ $templates->total() }}</b> items.
            @elseif(is_array($templates) && count($templates) > 0)
                Showing <b>1-{{ count($templates) }}</b> of <b>{{ count($templates) }}</b> items.
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
                        <th>Subject</th>
                        <th>From</th>
                        <th>Created At</th>
                        <th>Updated At</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($templates as $template)
                        <tr>
                            <td>{{ $template->id }}</td>
                            <td>{{ $template->name }}</td>
                            <td>{{ $template->subject }}</td>
                            <td>{{ $template->from ?? '-' }}</td>
                            <td>{{ $template->created_at->format('M d, Y') }}</td>
                            <td>{{ $template->updated_at?->format('M d, Y') ?? '-' }}</td>
                            <td>
                                <div class="btn-group">
                                    <a href="{{ route('email-template.show', $template) }}" class="btn btn-sm btn-info" title="View">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('email-template.edit', $template) }}" class="btn btn-sm btn-primary" title="Edit">
                                        <i class="fas fa-pencil-alt"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center">No email templates found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($templates instanceof \Illuminate\Contracts\Pagination\Paginator)
            <div class="text-center">
                {{ $templates->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
