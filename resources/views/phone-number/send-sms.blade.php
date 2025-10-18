@extends('layouts.app')

@section('content')
<div class="box">
    <div class="box-header with-border">
        <h3 class="box-title">Send SMS to {{ $phoneNumber->number }}</h3>
    </div>
    
    <form action="{{ route('phone-number.send-sms-post', $phoneNumber) }}" method="POST">
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
                <i class="fas fa-info-circle"></i> Sending SMS to: <strong>{{ $phoneNumber->number }}</strong>
            </div>

            <div class="form-group">
                <label for="message">Message <span class="text-danger">*</span></label>
                <textarea class="form-control" id="message" name="message" rows="5" required>{{ old('message') }}</textarea>
                <small class="text-muted">Maximum 160 characters for standard SMS</small>
            </div>
        </div>
        
        <div class="box-footer">
            <button type="submit" class="btn btn-success">
                <i class="fas fa-paper-plane"></i> Send SMS
            </button>
            <a href="{{ route('phone-number.show', $phoneNumber) }}" class="btn btn-default">
                <i class="fas fa-times"></i> Cancel
            </a>
        </div>
    </form>
</div>
@endsection
