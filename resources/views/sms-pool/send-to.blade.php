@extends('layouts.app')

@section('title', '{{ $title }} - Admin')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">{{ $title }}</h1>
        <a href="{{ route('sms-pool.send-dashboard') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back to Dashboard
        </a>
    </div>

    <div class="card shadow mb-4">
        <div class="card-body">
            <form action="{{ route('sms-pool.process-send') }}" method="POST" id="sendSmsForm">
                @csrf
                <input type="hidden" name="obj_type" value="{{ $objType }}">
                @if($objId)
                    <input type="hidden" name="obj_id" value="{{ $objId }}">
                @endif
                @if(isset($state))
                    <input type="hidden" name="state" value="{{ $state }}">
                @endif

                <div class="form-group">
                    <label for="body">Message Body</label>
                    <textarea class="form-control @error('body') is-invalid @enderror" 
                              id="body" 
                              name="body" 
                              rows="4" 
                              maxlength="160" 
                              placeholder="Enter your message here..." 
                              required>{{ old('body') }}</textarea>
                    <small class="form-text text-muted">
                        <span id="charCount">0</span>/160 characters
                    </small>
                    @error('body')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <div class="form-group">
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-paper-plane"></i> Send Message
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const textarea = document.getElementById('body');
    const charCount = document.getElementById('charCount');
    
    function updateCharCount() {
        const count = textarea.value.length;
        charCount.textContent = count;
        
        if (count > 160) {
            charCount.style.color = 'red';
        } else if (count > 140) {
            charCount.style.color = 'orange';
        } else {
            charCount.style.color = 'green';
        }
    }
    
    textarea.addEventListener('input', updateCharCount);
    updateCharCount(); // Initial count
});
</script>
@endsection
