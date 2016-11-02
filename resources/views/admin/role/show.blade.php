@extends('layouts.admin')

@section('title', sprintf('Show %s role', $role->name))
@section('description', sprintf('Show %s role', $role->name))

@section('content')
<h2 class="page-header">
  Show role: <small>{{ $role->name }}</small>
</h2>

@include('admin.common._alert')

@unless($role->trashed())
{{--Not editable if it deleted.--}}
<div class="btn-group btn-group-sm" role="group" aria-label="control role">
  <a href="{{ url('admin/role', [$role->id, 'edit']) }}" class="btn btn-raised">edit</a>
</div>
<hr>
@endunless


<div class="row">
  <div class="col-md-6 col-sm-12">
    {{--About--}}
    <div class="panel panel-defult">
      <div class="panel-heading">
        <h3 class="panel-title">Role</h3>
      </div>
      <div class="panel-body">
        <dl class="dl-horizontal">
          <dt>Name</dt>
          <dd>{{ $role->name }}</dd>

          <dt>Label</dt>
          <dd>{{ $role->label }}</dd>

          <dt>Deleted at</dt>
          @if($role->trashed())
            <dd>{{ $role->deleted_at->toDayDateTimeString() }}</dd>
          @else
            <dd>No</dd>
          @endif
        </dl>
      </div>
    </div>
  </div>

  <div class="col-md-6 col-sm-12">
    @if(count($role->permissions))
      <div class="panel panel-primary">
        <div class="panel-heading">
          <h3 class="panel-title">Permissions</h3>
        </div>
        <div class="panel-body">
          <div class="list-group">
            @foreach($role->permissions as $permission)
            <div class="list-group-item">
              <div class="row-content">
                <div class="action-secondary">
                  <a href="{{ url('admin/permission', $permission->id) }}"><i class="fa fa-info" aria-hidden="true"></i></a>
                </div>
                <h4 class="list-group-item-heading">{{ $permission->name }}</h4>
                <p class="list-group-item-text">{{ $permission->label }}</p>
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
