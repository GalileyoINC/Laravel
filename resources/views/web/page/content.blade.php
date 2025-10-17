@extends('web.layouts.app')

@section('title', 'Page Content - Admin')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Page Content: {{ $page->name }}</h1>
        <a href="{{ route('web.page.edit', $page) }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back to Page
        </a>
    </div>

    <div class="card shadow mb-4">
        <div class="card-body">
            <form action="{{ isset($pageContent) ? route('web.page.update-content', ['page' => $page, 'pageContent' => $pageContent]) : route('web.page.store-content', $page) }}" method="POST">
                @csrf
                @if(isset($pageContent))
                    @method('PUT')
                @endif

                <div class="form-group">
                    <label for="content">Content</label>
                    <textarea class="form-control @error('content') is-invalid @enderror" 
                              id="content" 
                              name="content" 
                              rows="20" 
                              required>{{ old('content', $pageContent->content ?? '') }}</textarea>
                    @error('content')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="status">Status</label>
                    <select class="form-control @error('status') is-invalid @enderror" id="status" name="status">
                        <option value="0" {{ old('status', $pageContent->status ?? '') == '0' ? 'selected' : '' }}>Draft</option>
                        <option value="1" {{ old('status', $pageContent->status ?? '') == '1' ? 'selected' : '' }}>Published</option>
                    </select>
                    @error('status')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <div class="form-group">
                    <button type="submit" class="btn btn-success">
                        <i class="far fa-save"></i> {{ isset($pageContent) ? 'Update' : 'Create' }} Content
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// Simple WYSIWYG editor functionality
document.addEventListener('DOMContentLoaded', function() {
    const textarea = document.getElementById('content');
    
    // You can integrate a proper WYSIWYG editor here like TinyMCE, CKEditor, etc.
    // For now, we'll keep it as a simple textarea
});
</script>
@endsection
