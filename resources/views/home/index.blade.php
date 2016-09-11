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
      <photos></photos>
    </div>
  </div>
</div>
@push('scripts')

  <script src="https://api-maps.yandex.ru/2.1/?lang=ru_RU" type="text/javascript"></script>
  <script>
    // ymaps.ready(initMap);

    // function initMap() {
    //   var map, maps = [], myLatLng = [], placemark;

    //   document.querySelectorAll('.map').forEach(function (elem) {
    //     myLatLng = [elem.dataset.mapsAttrLat, elem.dataset.mapsAttrLng];

    //     map = new ymaps.Map(elem, {
    //         center: myLatLng,
    //         zoom: 5,
    //         controls: ['smallMapDefaultSet'],
    //     });

    //     placemark = new ymaps.GeoObject({
    //         // Описание геометрии.
    //         geometry: {
    //             type: "Point",
    //             coordinates: map.getCenter()
    //         },
    //         // Свойства.
    //         properties: {
    //             hintContent: elem.dataset.mapsAttrTitle || '',
    //             balloonContent: elem.dataset.mapsAttrContent || ''
    //         }
    //     }, {
    //         // Опции.
    //         // Иконка метки будет растягиваться под размер ее содержимого.
    //         preset: 'islands#circleIcon',
    //         // Метку можно перемещать.
    //         draggable: true
    //     });

    //     map.geoObjects.add(placemark);

    //     // maps.push(map);
    //   });
    // }
  </script>
@endpush

@endsection

