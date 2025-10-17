@extends('web.layouts.app')

@section('title', 'Edit Page: {{ $page->name }} - Admin')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Edit Page: {{ $page->name }}</h1>
        <div>
            @if($page->slug && $page->status == \App\Models\Content\Page::STATUS_ON)
                <a href="{{ url($page->slug) }}" class="btn btn-success" target="_blank">
                    Frontend
                </a>
            @endif
            <a href="{{ route('web.page.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Back to Pages
            </a>
        </div>
    </div>

    <div class="card shadow mb-4">
        <div class="card-body">
            <form action="{{ route('web.page.update', $page) }}" method="POST" id="form_page">
                @csrf
                @method('PUT')
                @include('web.page._form', ['page' => $page])
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
