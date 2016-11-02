@extends('layouts.admin')

@section('title', 'Create news')
@section('description', 'Create news')

@section('content')
    <h2 class="page-header">Add News</h2>
    @include('admin.news._form')
@endsection
