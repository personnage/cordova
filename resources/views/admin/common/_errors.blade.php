@if (count($errors) > 0)
<div class="row">
  <div class="col-sm-10 col-sm-offset-2">
    <div class="panel panel-danger">
      <div class="panel-heading">
        <h3 class="panel-title">Validation error</h3>
      </div>
      <div class="panel-body">
        <ul>
          @foreach ($errors->all() as $error)
          <li>{{ $error }}</li>
          @endforeach
        </ul>
      </div>
    </div>
  </div>
</div>
@endif
