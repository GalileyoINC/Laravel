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
                        <th>Type</th>
                        <th>Active</th>
                        <th>Updated At</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($templates as $template)
                        <tr>
                            <td>{{ is_array($template) ? ($template['id'] ?? '') : ($template->id ?? '') }}</td>
                            <td>{{ is_array($template) ? ($template['name'] ?? '') : ($template->name ?? '') }}</td>
                            <td>{{ is_array($template) ? ($template['subject'] ?? '') : ($template->subject ?? '') }}</td>
                            <td>{{ is_array($template) ? ($template['type_name'] ?? 'N/A') : ($template->type_name ?? 'N/A') }}</td>
                            <td>
                                @php $active = is_array($template) ? ($template['is_active'] ?? false) : ($template->is_active ?? false); @endphp
                                @if($active)
                                    <span class="badge bg-success">Active</span>
                                @else
                                    <span class="badge bg-danger">Inactive</span>
                                @endif
                            </td>
                            <td>
                                @php
                                    $updatedAt = is_array($template) ? ($template['updated_at'] ?? null) : ($template->updated_at ?? null);
                                @endphp
                                @if($updatedAt instanceof \Illuminate\Support\Carbon)
                                    {{ $updatedAt->format('M d, Y') }}
                                @elseif(!empty($updatedAt))
                                    {{ \Illuminate\Support\Carbon::parse($updatedAt)->format('M d, Y') }}
                                @else
                                    
                                @endif
                            </td>
                            <td>
                                <div class="btn-group">
                                    @php $templateId = is_array($template) ? ($template['id'] ?? null) : ($template->id ?? null); @endphp
                                    @if($templateId)
                                    <a href="{{ route('email-template.show', ['email_template' => $templateId]) }}" class="btn btn-sm btn-info" title="View">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('email-template.edit', ['email_template' => $templateId]) }}" class="btn btn-sm btn-primary" title="Edit">
                                        <i class="fas fa-pencil-alt"></i>
                                    </a>
                                    <form action="{{ route('email-template.destroy', ['email_template' => $templateId]) }}" method="POST" style="display:inline;" onsubmit="return confirm('Are you sure?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" title="Delete">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                    @endif
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
