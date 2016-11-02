@extends('layouts.admin')

@section('title', sprintf('Show %s permission', $permission->name))
@section('description', sprintf('Show %s permission', $permission->name))

@section('content')
<h2 class="page-header">
  Show permission: <small>{{ $permission->name }}</small>
</h2>

@include('admin.common._alert')

@unless($permission->trashed())
{{--Not editable if it deleted.--}}
<div class="btn-group btn-group-sm" role="group" aria-label="control permission">
  <a href="{{ url('admin/permission', [$permission->id, 'edit']) }}" class="btn btn-raised">edit</a>
</div>
<hr>
@endunless


<div class="row">
  <div class="col-md-6 col-sm-12">
    {{--About--}}
    <div class="panel panel-defult">
      <div class="panel-heading">
        <h3 class="panel-title">Permission</h3>
      </div>
      <div class="panel-body">
        <dl class="dl-horizontal">
          <dt>Name</dt>
          <dd>{{ $permission->name }}</dd>

          <dt>Label</dt>
          <dd>{{ $permission->label }}</dd>

          <dt>Deleted at</dt>
          @if($permission->trashed())
            <dd>{{ $permission->deleted_at->toDayDateTimeString() }}</dd>
          @else
            <dd>No</dd>
          @endif
        </dl>
      </div>
    </div>
  </div>

  <div class="col-md-6 col-sm-12">
    @if(count($permission->roles))
      <div class="panel panel-primary">
        <div class="panel-heading">
          <h3 class="panel-title">Roles</h3>
        </div>
        <div class="panel-body">
          <div class="list-group">
            @foreach($permission->roles as $role)
            <div class="list-group-item">
              <div class="row-content">
                <div class="action-secondary">
                  <a href="{{ url('admin/role', $role->id) }}"><i class="fa fa-info" aria-hidden="true"></i></a>
                </div>
                <h4 class="list-group-item-heading">{{ $role->name }}</h4>
                <p class="list-group-item-text">{{ $role->label }}</p>
              </div>
            </div>
            @endforeach
          </div>
        </div>
      </div>
    @endif
  </div>
</div>
@endsection
