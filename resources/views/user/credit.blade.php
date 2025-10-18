@extends('layouts.app')

@section('content')
<div class="box">
    <div class="box-header with-border">
        <h3 class="box-title">Apply Credit to User #{{ $user->id }}</h3>
    </div>
    
    <form action="{{ route('user.credit.store', $user) }}" method="POST">
        @csrf
        
        <div class="box-body">
            @if($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="alert alert-info">
                <strong>Current Bonus Points:</strong> {{ $user->bonus_point ?? 0 }}
            </div>

            <div class="row">
                <div class="col-lg-6">
                    <div class="form-group">
                        <label for="amount">Amount <span class="text-danger">*</span></label>
                        <input type="number" step="0.01" class="form-control" id="amount" name="amount" value="{{ old('amount') }}" required>
                        <small class="text-muted">Enter the credit amount to add</small>
                    </div>
                </div>
                
                <div class="col-lg-6">
                    <div class="form-group">
                        <label for="reason">Reason</label>
                        <input type="text" class="form-control" id="reason" name="reason" value="{{ old('reason', 'Admin credit') }}">
                        <small class="text-muted">Optional reason for this credit</small>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="box-footer">
            <button type="submit" class="btn btn-success">
                <i class="fas fa-coins"></i> Apply Credit
            </button>
            <a href="{{ route('user.show', $user) }}" class="btn btn-default">
                <i class="fas fa-times"></i> Cancel
            </a>
        </div>
    </form>
</div>
@endsection
