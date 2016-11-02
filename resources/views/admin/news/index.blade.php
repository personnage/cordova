@extends('layouts.admin')

@section('title', 'News')
@section('description', 'News')

@section('content')
    @include('admin.news._list')
@endsection
