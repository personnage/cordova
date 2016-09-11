@extends('layouts.app')

@section('content')

@push('styles')
<style type="text/css">
  html, body {
    background: url("http://subtlepatterns2015.subtlepatterns.netdna-cdn.com/patterns/swirl_pattern.png");
  }

  .map {
    width: 100%;
    height: 300px;
    margin: 0;
    padding: 0;
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
                <div class="panel panel-info">
                  {{-- Head --}}
                  <div class="panel-heading">
                    <dl class="dl-horizontal">
                      <dt>ID:</dt>
                      <dd>{{ $photo['id'] }}</dd>

                      <dt>Title:</dt>
                      <dd>{{ $photo['title'] }}</dd>

                      <dt>Uploaded:</dt>
                      <dd>{{ array_get($photo, 'info.uploaded_at')->toDayDateTimeString() }}</dd>
                    </dl>
                  </div>

                  {{-- Body --}}
                  <div class="panel-body">
                    {{-- Fetch very big photo --}}
                    <img src="{{ array_get(last($photo['sizes']), 'source') }}" class="img-responsive" />
                  </div>

                  {{-- Footer --}}
                  <div class="panel-footer">

                    <div class="map"
                      data-maps-attr-lat="{{ array_get($photo, 'info.location.latitude') }}"
                      data-maps-attr-lng="{{ array_get($photo, 'info.location.longitude') }}"
                      data-maps-attr-title="{{ array_get($photo, 'info.title') }}"
                      data-maps-attr-content="{{ array_get($photo, 'info.description') }}"
                    ></div>

                  </div>
                </div>
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
@push('scripts')

  <script src="https://api-maps.yandex.ru/2.1/?lang=ru_RU" type="text/javascript"></script>
  <script>
    ymaps.ready(initMap);

    function initMap() {
      var map, maps = [], myLatLng = [], placemark;

      document.querySelectorAll('.map').forEach(function (elem) {
        myLatLng = [elem.dataset.mapsAttrLat, elem.dataset.mapsAttrLng];

        map = new ymaps.Map(elem, {
            center: myLatLng,
            zoom: 5,
            controls: ['smallMapDefaultSet'],
        });

        placemark = new ymaps.GeoObject({
            // Описание геометрии.
            geometry: {
                type: "Point",
                coordinates: map.getCenter()
            },
            // Свойства.
            properties: {
                hintContent: elem.dataset.mapsAttrTitle || '',
                balloonContent: elem.dataset.mapsAttrContent || ''
            }
        }, {
            // Опции.
            // Иконка метки будет растягиваться под размер ее содержимого.
            preset: 'islands#circleIcon',
            // Метку можно перемещать.
            draggable: true
        });

        map.geoObjects.add(placemark);

        // maps.push(map);
      });
    }
  </script>
@endpush

@endsection
