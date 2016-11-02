@extends('layouts.admin')

@section('title', 'Users')
@section('description', 'Users')

@section('content')
    @include('admin.user._list')
@endsection
