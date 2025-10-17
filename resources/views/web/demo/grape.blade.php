@extends('web.layouts.app')

@section('title', 'GrapeJS Demo - Admin')

@section('content')
<style>
.content_wrapper {
    height: 100vh;
}
.content {
    height: 100%;
}
</style>

<div id="gjs">
    <h1>DEMO</h1>
</div>

<!-- GrapeJS CSS -->
<link href="https://unpkg.com/grapesjs@0.21.7/dist/css/grapes.min.css" rel="stylesheet">

<!-- GrapeJS JS -->
<script src="https://unpkg.com/grapesjs@0.21.7/dist/grapes.min.js"></script>
<script src="https://unpkg.com/grapesjs-preset-webpage@1.0.2"></script>

<script>
window.onload = () => {
    window.editor = grapesjs.init({
        height: '100%',
        showOffsets: true,
        noticeOnUnload: false,
        storageManager: false,
        container: '#gjs',
        fromElement: true,
        blocks: ['link-block', 'quote', 'text-basic'],
        plugins: ['grapesjs-preset-webpage'],
        pluginsOpts: {
            'grapesjs-preset-webpage': {}
        }
    });
};
</script>
@endsection
