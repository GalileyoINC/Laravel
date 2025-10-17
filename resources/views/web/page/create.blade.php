@extends('web.layouts.app')

@section('title', 'Create Page - Admin')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Create Page</h1>
        <a href="{{ route('web.page.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back to Pages
        </a>
    </div>

    <div class="card shadow mb-4">
        <div class="card-body">
            <form action="{{ route('web.page.store') }}" method="POST" id="form_page">
                @csrf
                @include('web.page._form')
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
