@extends('web.layouts.app')

@section('title', 'Create Staff - Admin')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Create Staff</h1>
        <a href="{{ route('web.staff.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back to Staff
        </a>
    </div>

    <div class="card shadow mb-4">
        <div class="card-body">
            <form action="{{ route('web.staff.store') }}" method="POST" id="staff">
                @csrf
                @include('web.staff._form')
                <div class="form-group mt-3">
                    <button type="submit" class="btn btn-success">
                        <i class="far fa-save"></i> Save
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
