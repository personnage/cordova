@extends('layouts.admin')

@section('title', 'New user')
@section('description', 'Create new user')

@section('content')
    <h2 class="page-header">New user</h2>
    @include('admin.user._form')
@endsection
