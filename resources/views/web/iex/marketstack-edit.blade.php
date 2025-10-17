@extends('web.layouts.app')

@section('title', 'Update Marketstack Index - Admin')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">Update Marketstack Index: {{ $index->name }}</h3>
                    <div class="panel-heading-actions">
                        <a href="{{ route('web.iex.marketstack') }}" class="btn btn-default">
                            <i class="fas fa-arrow-left"></i> Back to List
                        </a>
                    </div>
                </div>
                <div class="panel-body">
                    <form method="POST" action="{{ route('web.iex.update-marketstack', $index) }}" id="marketstack-index" class="JS__load_in_modal">
                        @csrf
                        @method('PUT')

                        <div class="form-group">
                            <div class="form-check">
                                <input type="checkbox" name="is_active" id="is_active" class="form-check-input" value="1" {{ $index->is_active ? 'checked' : '' }}>
                                <label for="is_active" class="form-check-label">Is Active</label>
                            </div>
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-success">
                                <i class="far fa-save"></i> Save
                            </button>
                        </div>
                    </form>
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
.form-group {
    margin-bottom: 1rem;
}
.form-check {
    display: flex;
    align-items: center;
}
.form-check-input {
    margin-right: 0.5rem;
}
.form-check-label {
    margin-bottom: 0;
    font-weight: 600;
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
</style>
@endsection
