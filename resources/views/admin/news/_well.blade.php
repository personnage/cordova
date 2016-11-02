<div class="well">
  <div class="row">
    <div class="col-sm-6">
      <form method="GET" action="{{ url('admin/news') }}" class="bs-component" accept-charset="UTF-8">
        <div class="form-group label-floating is-empty">
          <label class="control-label" for="namesearch">Title</label>
          <div class="input-group">
            <input type="search" name="search" autocomplete="off" id="namesearch" class="form-control" spellcheck="false">
            @if(Request::has('filter'))
            <input type="hidden" name="filter" value="{{request('filter')}}">
            @endif
            <span class="input-group-btn">
              <button type="submit" class="btn btn-fab btn-fab-mini btn-primary">
                <i class="fa fa-search" aria-hidden="true"></i>
              </button>
            </span>
          </div>
        </div>
      </form>
    </div>

    <div class="col-sm-4 col-sm-offset-2">
      <div class="btn-group">
        <div class="btn-group">
          <a href="#" data-target="#" class="btn btn-raised dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
            Sort <span class="caret"></span>
            <div class="ripple-container"></div>
          </a>
          @include('admin.news._sort')
        </div>
        <a href="{{ url('admin/news/create') }}" class="btn btn-raised">Add News<div class="ripple-container"></div></a>
      </div>
    </div>
  </div>
</div>
