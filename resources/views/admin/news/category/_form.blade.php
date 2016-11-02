{{--add support tinymce --}}
@push('scripts')
  <script src="//cdn.tinymce.com/4/tinymce.min.js"></script>
@endpush

@include('admin.common._alert')
@include('admin.common._errors')

<form enctype="multipart/form-data" method="POST" action="{{ url('admin/news/category', $category->id) }}" role="form" class="form-horizontal" accept-charset="utf-8" autocomplete="off">
  {{ csrf_field() }}

  @if($category->exists)
  {{ method_field('PATCH') }}
  @endif

  <fieldset>
    <div class="form-group{{ $errors->has('title') ? ' has-error' : '' }}">
      <label for="title" class="col-sm-2 control-label">Title</label>
      <div class="col-sm-10">
        <input type="text" name="title" value="{{ old('title', $category->title) }}" id="title" class="form-control" placeholder="Header to category" autocomplete="off" required>
        @if ($errors->has('title'))
          <span class="help-block">
            <strong>{{ $errors->first('title') }}</strong>
          </span>
        @endif
      </div>
    </div>

    <div class="form-group{{ $errors->has('announcement') ? ' has-error' : '' }}">
      <label for="announcement" class="col-sm-2 control-label">Announcement</label>
      <div class="col-sm-10">
        <textarea class="implement-html-editor-as-basic" name="announcement" id="announcement" placeholder="Short announcement">{{ old('announcement', $category->announcement) }}</textarea>
        @if ($errors->has('announcement'))
          <span class="help-block">
            <strong>{{ $errors->first('announcement') }}</strong>
          </span>
        @endif
      </div>
    </div>

    <div class="form-group{{ $errors->has('description') ? ' has-error' : '' }}">
      <label for="description" class="col-sm-2 control-label">Description</label>
      <div class="col-sm-10">
        <textarea class="implement-html-editor-as-middle" name="description" id="description" placeholder="Short description">{{ old('description', $category->description) }}</textarea>
        @if ($errors->has('description'))
          <span class="help-block">
            <strong>{{ $errors->first('description') }}</strong>
          </span>
        @endif
      </div>
    </div>
  </fieldset>

  @if($category->exists)
    <fieldset>
      <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
        <label for="name" class="col-sm-2 control-label">Name</label>
        <div class="col-sm-10">
          <input type="text" name="name" value="{{ old('name', $category->name) }}" id="name" class="form-control" placeholder="Path to category" autocomplete="off">
          @if ($errors->has('name'))
            <span class="help-block">
              <strong>{{ $errors->first('name') }}</strong>
            </span>
          @endif
        </div>
      </div>
    </fieldset>
  @endif

  <hr>

  <fieldset>
    <div class="form-group{{ $errors->has('category_thumbnail') ? ' has-error' : '' }}">
      <label for="category_thumbnail" class="col-md-2 control-label">Thumbnail</label>
      <div class="col-md-3">
        <div class="thumbnail">
          @if($category->thumbnail)
          <img src="{{ asset($category->thumbnailUrl()) }}">
          @endif

          <div class="caption">
            <p>! Image will be updated after saving.</p>
          </div>
        </div>
      </div>

      <div class="col-md-7">
        <input type="text" readonly class="form-control" placeholder="Browse...">
        <input type="file" name="category_thumbnail" id="category_thumbnail" accept="image/*">
      </div>
    </div>
  </fieldset>

  <div class="form-group">
    @if($category->exists)
      <div class="col-sm-offset-2 col-sm-4">
        <button type="submit" class="btn btn-default">Save changes</button>
      </div>
    @else
      <div class="col-sm-offset-2 col-sm-4">
        <button type="submit" class="btn btn-default">Create category</button>
      </div>
    @endif

    <div class="col-sm-offset-2 col-sm-4">
      <a href="{{ url('admin/news/category') }}" class="btn btn-default">Cancel</a>
    </div>
  </div>
</form>
