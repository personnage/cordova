@extends('layouts.admin')

@section('title', 'Create category to news')
@section('description', 'Create category to news')

@section('content')
    <h2 class="page-header">Create Category</h2>
    @include('admin.news.category._form')
@endsection
