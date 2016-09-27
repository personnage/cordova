@extends('layouts.app')

@push('styles')
  <style>
    html, body
    { background: url("http://subtlepatterns2015.subtlepatterns.netdna-cdn.com/patterns/congruent_pentagon.png"); }
  </style>
@endpush

@push('styles')
  <script src="https://api-maps.yandex.ru/2.1/?lang=ru_RU" type="text/javascript"></script>
@endpush

@section('content')
  <photos></photos>
@endsection
