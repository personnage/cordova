@extends('layouts.admin')

@section('title', 'Photo groups')
@section('description', 'Photo groups')

@section('content')
    @include('admin.photos.category._list')
@endsection
