@extends('layouts.admin')

@section('title', 'Permissions')
@section('description', 'Permissions')

@section('content')
    @include('admin.permission._list')
@endsection
