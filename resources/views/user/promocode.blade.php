@extends('layouts.app')

@section('content')
<div class="box">
    <div class="box-header with-border">
        <h3 class="box-title">Influencer Promocodes</h3>
    </div>
    
    <div class="box-body">
        <div class="table-responsive">
            <table class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Code</th>
                        <th>Influencer</th>
                        <th>Discount</th>
                        <th>Type</th>
                        <th>Status</th>
                        <th>Created At</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($promocodes as $promocode)
                        <tr>
                            <td>{{ $promocode->id }}</td>
                            <td><code>{{ $promocode->text }}</code></td>
                            <td>
                                @if($promocode->influencer)
                                    {{ $promocode->influencer->first_name }} {{ $promocode->influencer->last_name }}
                                @else
                                    N/A
                                @endif
                            </td>
                            <td>
                                @if($promocode->discount_type == 'percent')
                                    {{ $promocode->discount_value }}%
                                @else
                                    ${{ number_format($promocode->discount_value, 2) }}
                                @endif
                            </td>
                            <td>
                                @if($promocode->type == 1)
                                    <span class="badge bg-info">Sale</span>
                                @else
                                    <span class="badge bg-default">Regular</span>
                                @endif
                            </td>
                            <td>
                                @if($promocode->is_active)
                                    <span class="badge bg-success">Active</span>
                                @else
                                    <span class="badge bg-danger">Inactive</span>
                                @endif
                            </td>
                            <td>{{ $promocode->created_at->format('M d, Y') }}</td>
                            <td>
                                <form action="{{ route('user.delete-promocode', $promocode) }}" method="POST" style="display:inline;" onsubmit="return confirm('Are you sure you want to delete this promocode?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" title="Delete">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center">No promocodes found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if(method_exists($promocodes, 'links'))
            <div class="text-center">
                {{ $promocodes->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
