@extends('layouts.app')

@section('content')
<div class="box">
    <div class="box-header with-border">
        <h3 class="box-title">Edit Email Template #{{ $emailTemplate->id }}</h3>
    </div>
    
    <form action="{{ route('email-template.update', $emailTemplate) }}" method="POST">
        @csrf
        @method('PUT')
        @include('email-template._form')
        
        <div class="box-footer">
            <button type="submit" class="btn btn-success">
                <i class="far fa-save"></i> Save
            </button>
            <a href="{{ route('email-template.index') }}" class="btn btn-default">
                <i class="fas fa-times"></i> Cancel
            </a>
        </div>
    </form>
</div>
@endsection
