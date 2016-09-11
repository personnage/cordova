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
      <photos></photos>
    </div>
  </div>
</div>

@endsection

