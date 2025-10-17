@extends('web.layouts.app')

@section('title', 'Bootstrap WYSIWYG Demo - Admin')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">Bootstrap WYSIWYG Demo</h3>
                </div>
                <div class="panel-body">
                    <div class="form-group">
                        <label class="control-label" for="editor">Message:</label>
                        <textarea id="editor" class="form-control" rows="3">Some Text</textarea>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Bootstrap WYSIWYG CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap-wysiwyg@0.0.2/bootstrap-wysiwyg.min.css" rel="stylesheet">

<!-- Bootstrap WYSIWYG JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap-wysiwyg@0.0.2/bootstrap-wysiwyg.min.js"></script>

<style>
.panel-default {
    border: 1px solid #ddd;
}
.panel-heading {
    background-color: #f5f5f5;
    border-bottom: 1px solid #ddd;
    padding: 10px 15px;
}
.panel-body {
    padding: 15px;
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
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    $('#editor').wysiwyg();
});
</script>
@endsection
