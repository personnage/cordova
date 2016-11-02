@extends('layouts.admin')

@section('title', sprintf('Edit %s permission', $permission->name))
@section('description', sprintf('Edit %s permission', $permission->name))

@section('content')
    <h2 class="page-header">Edit permission: <small>{{ $permission->name }}</small></h2>
    @include('admin.permission._form')
@endsection
