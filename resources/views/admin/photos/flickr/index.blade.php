@extends('layouts.admin')

@section('title', 'Flickr Service')
@section('description', 'Flickr Service')

@section('content')
    @include('admin.photos.flickr._list')
@endsection
