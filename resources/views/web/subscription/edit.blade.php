@extends('web.layouts.app')

@section('title', 'Update Subscription - Admin')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">Update Subscription: {{ $subscription->title }}</h3>
                    <div class="panel-heading-actions">
                        <a href="{{ route('web.subscription.index') }}" class="btn btn-default">
                            <i class="fas fa-arrow-left"></i> Back to List
                        </a>
                    </div>
                </div>
                <div class="panel-body">
                    <form id="subscription" method="POST" action="{{ route('web.subscription.update', $subscription) }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="row">
                            <div class="col-lg-6 col-xs-12">
                                <div class="form-group">
                                    <label for="id_subscription_category">Category</label>
                                    <select name="id_subscription_category" id="id_subscription_category" class="form-control" required>
                                        <option value="">Select Category</option>
                                        @foreach($categories as $category)
                                            <option value="{{ $category->id }}" {{ old('id_subscription_category', $subscription->id_subscription_category) == $category->id ? 'selected' : '' }}>
                                                {{ $category->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('id_subscription_category')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="title">Title</label>
                                    <input type="text" name="title" id="title" class="form-control" value="{{ old('title', $subscription->title) }}" maxlength="255" required>
                                    @error('title')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="percent">Percent</label>
                                    <input type="number" name="percent" id="percent" class="form-control" value="{{ old('percent', $subscription->percent) }}" min="0" max="100" step="0.01">
                                    @error('percent')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="alias">Alias</label>
                                    <input type="text" name="alias" id="alias" class="form-control" value="{{ old('alias', $subscription->alias) }}" maxlength="255">
                                    @error('alias')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="description">Description</label>
                                    <textarea name="description" id="description" class="form-control" rows="2">{{ old('description', $subscription->description) }}</textarea>
                                    @error('description')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <div class="form-check">
                                        <input type="checkbox" name="is_custom" id="is_custom" class="form-check-input" value="1" {{ old('is_custom', $subscription->is_custom) ? 'checked' : '' }}>
                                        <label for="is_custom" class="form-check-label">Is Custom</label>
                                    </div>
                                    @error('is_custom')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <div class="form-check">
                                        <input type="checkbox" name="show_reactions" id="show_reactions" class="form-check-input" value="1" {{ old('show_reactions', $subscription->show_reactions) ? 'checked' : '' }}>
                                        <label for="show_reactions" class="form-check-label">Show Reactions</label>
                                    </div>
                                    @error('show_reactions')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <div class="form-check">
                                        <input type="checkbox" name="show_comments" id="show_comments" class="form-check-input" value="1" {{ old('show_comments', $subscription->show_comments) ? 'checked' : '' }}>
                                        <label for="show_comments" class="form-check-label">Show Comments</label>
                                    </div>
                                    @error('show_comments')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-lg-6 col-xs-12">
                                @if($subscription->image)
                                    <div class="mb-3">
                                        <label>Current Image</label>
                                        <div>
                                            <img src="{{ Storage::url($subscription->image) }}" alt="{{ $subscription->title }}" class="img-responsive img-thumbnail" style="max-width: 200px;">
                                        </div>
                                    </div>
                                @endif
                                <div class="form-group">
                                    <label for="imageFile">Image</label>
                                    <input type="file" name="imageFile" id="imageFile" class="form-control" accept="image/*">
                                    @error('imageFile')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">Upload a new image (JPEG, PNG, JPG, GIF) - Max 2MB</small>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="panel-footer">
                    <button type="submit" form="subscription" class="btn btn-success">
                        <i class="far fa-save"></i> Save
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.panel-default {
    border: 1px solid #ddd;
}
.panel-heading {
    background-color: #f5f5f5;
    border-bottom: 1px solid #ddd;
    padding: 10px 15px;
    display: flex;
    justify-content: space-between;
    align-items: center;
}
.panel-heading-actions {
    display: flex;
    gap: 10px;
}
.panel-body {
    padding: 15px;
}
.panel-footer {
    background-color: #f5f5f5;
    border-top: 1px solid #ddd;
    padding: 10px 15px;
}
.form-group {
    margin-bottom: 1rem;
}
.form-group label {
    display: inline-block;
    margin-bottom: 0.5rem;
    font-weight: 600;
}
.form-control {
    display: block;
    width: 100%;
    padding: 0.375rem 0.75rem;
    font-size: 1rem;
    line-height: 1.5;
    color: #495057;
    background-color: #fff;
    background-clip: padding-box;
    border: 1px solid #ced4da;
    border-radius: 0.25rem;
}
.form-control:focus {
    color: #495057;
    background-color: #fff;
    border-color: #80bdff;
    outline: 0;
    box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
}
.form-check {
    position: relative;
    display: block;
    padding-left: 1.25rem;
}
.form-check-input {
    position: absolute;
    margin-top: 0.3rem;
    margin-left: -1.25rem;
}
.form-check-label {
    margin-bottom: 0;
}
.text-danger {
    color: #dc3545;
}
.text-muted {
    color: #6c757d;
}
.img-responsive {
    display: block;
    max-width: 100%;
    height: auto;
}
.img-thumbnail {
    padding: 0.25rem;
    background-color: #fff;
    border: 1px solid #dee2e6;
    border-radius: 0.25rem;
    max-width: 100%;
    height: auto;
}
.btn-success {
    color: #fff;
    background-color: #28a745;
    border-color: #28a745;
}
.btn-default {
    color: #333;
    background-color: #fff;
    border-color: #ccc;
}
.btn {
    display: inline-block;
    font-weight: 400;
    text-align: center;
    vertical-align: middle;
    cursor: pointer;
    border: 1px solid transparent;
    padding: 0.375rem 0.75rem;
    font-size: 1rem;
    line-height: 1.5;
    border-radius: 0.25rem;
    text-decoration: none;
}
.btn:hover {
    text-decoration: none;
}
.btn-success:hover {
    color: #fff;
    background-color: #218838;
    border-color: #1e7e34;
}
.btn-default:hover {
    color: #333;
    background-color: #e6e6e6;
    border-color: #adadad;
}
</style>
@endsection
