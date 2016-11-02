@extends('layouts.admin')

@section('title', sprintf('Edit %s role', $role->name))
@section('description', sprintf('Edit %s role', $role->name))

@section('content')
    <h2 class="page-header">Edit role: <small>{{ $role->name }}</small></h2>
    @include('admin.role._form')
@endsection
