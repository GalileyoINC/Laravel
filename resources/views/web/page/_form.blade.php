<div class="row">
    <div class="col-lg-8 col-md-6 col-12">
        <div class="form-group">
            <label for="name">Name</label>
            <input type="text" 
                   class="form-control @error('name') is-invalid @enderror" 
                   id="name" 
                   name="name" 
                   value="{{ old('name', $page->name ?? '') }}" 
                   maxlength="255" 
                   required>
            @error('name')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>

        <div class="form-group">
            <label for="title">Title</label>
            <input type="text" 
                   class="form-control @error('title') is-invalid @enderror" 
                   id="title" 
                   name="title" 
                   value="{{ old('title', $page->title ?? '') }}" 
                   maxlength="255">
            @error('title')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>

        <div class="form-group">
            <label for="slug">Slug</label>
            <input type="text" 
                   class="form-control @error('slug') is-invalid @enderror" 
                   id="slug" 
                   name="slug" 
                   value="{{ old('slug', $page->slug ?? '') }}" 
                   maxlength="255">
            @error('slug')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>

        <div class="form-group">
            <label for="meta_keywords">Meta Keywords</label>
            <input type="text" 
                   class="form-control @error('meta_keywords') is-invalid @enderror" 
                   id="meta_keywords" 
                   name="meta_keywords" 
                   value="{{ old('meta_keywords', $page->meta_keywords ?? '') }}" 
                   maxlength="255">
            @error('meta_keywords')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>

        <div class="form-group">
            <label for="meta_description">Meta Description</label>
            <input type="text" 
                   class="form-control @error('meta_description') is-invalid @enderror" 
                   id="meta_description" 
                   name="meta_description" 
                   value="{{ old('meta_description', $page->meta_description ?? '') }}" 
                   maxlength="255">
            @error('meta_description')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>
    </div>
    
    <div class="col-lg-4 col-md-4 col-12">
        <div class="form-group">
            <label for="status">Status</label>
            <select class="form-control @error('status') is-invalid @enderror" 
                    id="status" 
                    name="status" 
                    {{ !isset($page) || !$page->id ? 'disabled' : '' }}>
                <option value="">Select Status</option>
                <option value="1" {{ old('status', $page->status ?? '') == '1' ? 'selected' : '' }}>Published</option>
                <option value="0" {{ old('status', $page->status ?? '') == '0' ? 'selected' : '' }}>Draft</option>
            </select>
            @error('status')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>

        @if(isset($page) && $page->id)
            @if(empty($page->pageContents))
                <a href="{{ route('web.page.content', $page) }}" class="btn btn-success btn-sm">
                    <i class="fas fa-pen-fancy fa-fw"></i> Content
                </a>
            @endif

            <table class="table">
                @foreach($page->pageContents as $pageContent)
                    <tr>
                        <td>{{ $pageContent->created_at->format('Y-m-d H:i:s') }}</td>
                        <td>
                            @if($pageContent->status === 1)
                                <span class="badge badge-success">Published</span>
                            @else
                                <span class="badge badge-warning">Draft</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ url('site/temp-page?id=' . $pageContent->id . '&sc=GYvvi85f') }}" 
                               class="btn btn-info btn-sm" 
                               target="_blank">
                                <i class="far fa-eye fa-fw"></i>
                            </a>
                            <a href="{{ route('web.page.content', ['page' => $page, 'pageContent' => $pageContent]) }}" 
                               class="btn btn-success btn-sm" 
                               target="_blank">
                                <i class="fas fa-pen-fancy fa-fw"></i>
                            </a>
                        </td>
                    </tr>
                @endforeach
            </table>
        @endif
    </div>
</div>

<style>
.badge-success {
    background-color: #1cc88a;
}
.badge-warning {
    background-color: #f6c23e;
}
</style>
