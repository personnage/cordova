@extends('layouts.app')

@section('content')

@push('styles')
<style type="text/css">
  html, body {
    background: url("http://subtlepatterns2015.subtlepatterns.netdna-cdn.com/patterns/swirl_pattern.png");
  }
</style>
@endpush

<div class="container-fluid">
  <div class="row">
    <div class="col-sm-12">
      <div id="freewall" class="free-wall">
      @foreach($photos as $photo)
        <div class="cell" style="width: {{$photo['width']}}px; height: {{$photo['height']}}px; background-image: url({{ $photo['source'] }})"></div>
      @endforeach
      </div>
    </div>
  </div>
</div>

@endsection
