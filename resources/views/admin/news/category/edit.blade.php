@extends('layouts.admin')

@section('title', sprintf('Edit %s category', $category->name))
@section('description', sprintf('Edit %s category', $category->name))

@section('content')
    <h2 class="page-header">Edit category: <small>{{ $category->name }}</small></h2>
    @include('admin.news.category._form')
@endsection
