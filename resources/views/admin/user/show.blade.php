@extends('layouts.admin')

@section('title', sprintf('Show %s user', $user->name))
@section('description', sprintf('Show %s user', $user->name))

@section('content')
<h2 class="page-header">
  {{ $user->name }}
  @if($user->admin)
    <span class="label label-danger">Admin</span>
  @endif
</h2>

@include('admin.common._alert')

<div class="btn-group btn-group-sm" role="group" aria-label="control user">
  @unless(Auth::id()===$user->id or $user->trashed())
    <a href="{{ url('admin/user', [$user->id, 'impersonate']) }}" class="btn btn-raised btn-info">impersonate</a>
  @endunless
  {{--Not editable if it deleted.--}}
  @unless($user->trashed())
    <a href="{{ url('admin/user', [$user->id, 'edit']) }}" class="btn btn-raised">edit</a>
  @endunless
</div>

<hr>

<div class="row">
  <div class="col-md-6 col-sm-12">
    {{--Profile card--}}
    <div class="panel panel-defult">
      <div class="panel-heading">
        <h3 class="panel-title">Profile</h3>
      </div>
      <div class="panel-body">
        <div class="media">
          <div class="media-left">
            <a href="#/profile">
              <img src="{{ Gravatar::src($user->email, 100) }}" class="img-circle" alt="{{ $user->name }}">
            </a>
          </div>
          <div class="media-body">
            <h4 class="media-heading">{{ $user->name }}</h4>
            <hr>
            <dl class="dl-horizontal">
              <dt>Member since</dt>
              <dd>{{ $user->created_at->toDayDateTimeString() }}</dd>
              <dt>E-mail</dt>
              <dd>{{ $user->email }}</dd>
              <dt>Profile page</dt>
              <dd><a href="#/profile">{{ $user->name }}</a></dd>
            </dl>
          </div>
        </div>
      </div>
    </div>

    {{--Account--}}
    <div class="panel panel-defult">
      <div class="panel-heading">
        <h3 class="panel-title">Account</h3>
      </div>
      <div class="panel-body">
        <dl class="dl-horizontal">
          <dt>Name</dt>
          <dd>{{ $user->name }}</dd>

          <dt>Confirmed at</dt>
          @if($user->isConfirmed())
            <dd>{{ $user->confirmed_at->toDayDateTimeString() }}</dd>
          @else
            <dd>No</dd>
          @endif

          <dt>Deleted at</dt>
          @if($user->trashed())
            <dd>{{ $user->deleted_at->toDayDateTimeString() }}</dd>
          @else
            <dd>No</dd>
          @endif

          <dt>Current sign-in IP</dt>
          @if($user->current_sign_in_ip)
            <dd>{{ $user->current_sign_in_ip }}</dd>
          @else
            <dd>Never</dd>
          @endif

          <dt>Current sign-in at</dt>
          @if($user->current_sign_in_at)
            <dd>{{ $user->current_sign_in_at->toDayDateTimeString() }}</dd>
          @else
            <dd>Never</dd>
          @endif

          <dt>Last sign-in IP</dt>
          @if($user->last_sign_in_ip)
            <dd>{{ $user->last_sign_in_ip }}</dd>
          @else
            <dd>Never</dd>
          @endif

          <dt>Last sign-in at</dt>
          @if($user->last_sign_in_at)
            <dd>{{ $user->last_sign_in_at->toDayDateTimeString() }}</dd>
          @else
            <dd>Never</dd>
          @endif

          <dt>Sign-in count</dt>
          <dd>{{ $user->sign_in_count }}</dd>

          <dt>Failed attempts</dt>
          <dd>{{ $user->failed_attempts }}</dd>

          @if($user->created_by_id)
            <dt>Created by</dt>
            @if(isset($user->created_by_id->exists))
            <dd><a href="{{ url('admin/user', $user->created_by_id->id) }}">{{ $user->created_by_id->name }}</a></dd>
            @else
            <dd>{{ $user->created_by_id }} (User not exist)</dd>
            @endif
          @endif
        </dl>
      </div>
    </div>
  </div>

  <div class="col-md-6 col-sm-12">
    @unless(Auth::id()===$user->id)
      @include('admin.user._remove_or_restore_panels')
    @endunless
  </div>
</div>
@endsection
