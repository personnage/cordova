@extends('layouts.admin')

@section('title', sprintf('Edit %s user', $user->name))
@section('description', sprintf('Edit %s user', $user->name))

@section('content')
    <h2 class="page-header">Edit user: {{ $user->name }}</h2>
    @include('admin.user._form')
@endsection
