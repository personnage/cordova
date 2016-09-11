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
        <div class="cell" style="width: {{$photo['item']['width']}}px; height: {{$photo['item']['height']}}px; background-image: url({{ $photo['item']['source'] }})">
          <div class="ctrl-panel">
            <div class="btn-group" role="group" aria-label="The photo ctrl panel...">
              <button type="button" class="btn btn-link" data-toggle="modal" data-target="[data-modal-photo={{ $photo['id'] }}]">
                <i class="fa fa-arrows-alt" aria-hidden="true"></i>
              </button>
              <button type="button" class="btn btn-link">
                <i class="fa fa-floppy-o" aria-hidden="true"></i>
              </button>
            </div>
          </div>
        </div>

        {{-- Modal to photo --}}
        <div class="modal fade" data-modal-photo="{{ $photo['id'] }}" tabindex="-1" role="dialog" aria-labelledby="Modal to photo...">
          <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">{{ $photo['title'] }}</h4>
              </div>

              <div class="modal-body">
                <img src="{{ $photo['item']['source'] }}" class="img-responsive" alt="Responsive image">
              </div>

              <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary">Save changes</button>
              </div>

            </div>
          </div>
        </div>
        {{-- End modal --}}

      @endforeach
      </div>
    </div>
  </div>
</div>

@endsection
