@extends('layouts.admin')

@section('title', 'News category')
@section('description', 'News category')

@section('content')
    @include('admin.news.category._list')
@endsection
