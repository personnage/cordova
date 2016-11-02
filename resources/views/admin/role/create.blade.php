@extends('layouts.admin')

@section('title', 'New role')
@section('description', 'Create new role')

@section('content')
    <h2 class="page-header">New role</h2>
    @include('admin.role._form')
@endsection
