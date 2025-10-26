@extends('layouts.app')

@section('content')
<div class="box">
    <div class="box-header with-border">
        <h3 class="box-title">Edit News #{{ $news->id }}</h3>
    </div>
    
    <form action="{{ route('news.update', $news) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        
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

            <div class="row">
                <div class="col-lg-6">
                    <div class="form-group">
                        <label for="name">Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $news->name) }}" required>
                    </div>
                </div>
                
                <div class="col-lg-6">
                    <div class="form-group">
                        <label for="slug">Slug</label>
                        <input type="text" class="form-control" id="slug" name="slug" value="{{ old('slug', $news->slug) }}">
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-6">
                    <div class="form-group">
                        <label for="image">Image</label>
                        <input type="file" class="form-control" id="image" name="image" accept="image/*">
                        @if($news->image)
                            <small class="text-muted">Current: {{ basename($news->image) }}</small>
                        @endif
                    </div>
                </div>
                
                <div class="col-lg-6">
                    <div class="form-group">
                        <label for="status">Status</label>
                        <select class="form-control" id="status" name="status">
                            <option value="0" {{ old('status', $news->status) == '0' ? 'selected' : '' }}>Draft</option>
                            <option value="1" {{ old('status', $news->status) == '1' ? 'selected' : '' }}>Published</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="box-footer">
            <button type="submit" class="btn btn-success">
                <i class="far fa-save"></i> Save
            </button>
            <a href="{{ route('news.index') }}" class="btn btn-default">
                <i class="fas fa-times"></i> Cancel
            </a>
        </div>
    </form>
</div>
@endsection
