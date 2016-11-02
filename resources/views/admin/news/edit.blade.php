@extends('layouts.admin')

@section('title', sprintf('Edit %s news', $news->name))
@section('description', sprintf('Edit %s news', $news->name))

@section('content')
    <h2 class="page-header">Edit news: <small>{{ $news->title }}</small></h2>
    @include('admin.news._form')
@endsection
