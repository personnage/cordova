@extends('layouts.admin')

@section('title', 'New permisson')
@section('description', 'Create new permission')

@section('content')
    <h2 class="page-header">New permission</h2>
    @include('admin.permission._form')
@endsection
