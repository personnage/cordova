@extends('layouts.app')

@section('content')

<style type="text/css">
  html, body {
    background: url("http://subtlepatterns2015.subtlepatterns.netdna-cdn.com/patterns/swirl_pattern.png");
  }
  .free-wall {
    margin: 15px;
  }
  .cell {
    background-position: center center;
    background-repeat: no-repeat;
    background-size: cover;
    position: absolute;
    background-color: #222;
  }
</style>

<div class="container-fluid">
  <div class="row">
    <div class="col-sm-12">
      <div id="freewall" class="free-wall">
      @foreach($images as $i => $image)
        <div class="cell" style="width: {{$imagesSize[$i]['w']}}px; height: 300px; background-image: url(data:image/png;base64,{{ $image }})"></div>
      @endforeach
      </div>
    </div>
  </div>
</div>

@endsection
