@extends('layouts.app')

@section('title', 'SMS Pool #{{ $smsPool->id }} - Admin')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">SMS Pool #{{ $smsPool->id }}</h1>
        <a href="{{ route('sms-pool.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back to SMS Pool
        </a>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">SMS Pool Details</h6>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <table class="table table-borderless">
                        <tr>
                            <td><strong>ID:</strong></td>
                            <td>{{ $smsPool->id }}</td>
                        </tr>
                        <tr>
                            <td><strong>Purpose:</strong></td>
                            <td>{{ \App\Models\Communication\SmsPool::getPurposes()[$smsPool->purpose] ?? 'Unknown' }}</td>
                        </tr>
                        <tr>
                            <td><strong>Subscription:</strong></td>
                            <td>{{ $smsPool->subscription ? $smsPool->subscription->name : '-' }}</td>
                        </tr>
                        <tr>
                            <td><strong>Sender:</strong></td>
                            <td>
                                @if($smsPool->id_user)
                                    User: {{ $smsPool->user->first_name }} {{ $smsPool->user->last_name }}
                                @elseif($smsPool->id_staff)
                                    Staff: {{ $smsPool->staff->username }}
                                @else
                                    -
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td><strong>Created At:</strong></td>
                            <td>{{ $smsPool->created_at->format('Y-m-d H:i:s') }}</td>
                        </tr>
                    </table>
                </div>
            </div>
            
            <hr>
            
            <div class="form-group">
                <label><strong>Message Body:</strong></label>
                <div class="alert alert-info">
                    {{ $smsPool->body }}
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Actions</h6>
        </div>
        <div class="card-body">
            <a href="{{ route('sms-pool.recipient', $smsPool) }}" class="btn btn-info">
                <i class="fas fa-mail-bulk"></i> View Recipients
            </a>
            <form action="{{ route('sms-pool.destroy', $smsPool) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this SMS pool?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger">
                    <i class="fas fa-trash"></i> Delete SMS Pool
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
