<template>
  <div id="freewall" class="free-wall">
    <div class="cell" v-for="photo in photos"
      v-bind:style="{
        width: photo.item.width + 'px',
        height: photo.item.height + 'px',
        backgroundImage: 'url(' + photo.item.source + ')'
      }"
    >
      <div class="ctrl-panel">
        <div class="btn-group" role="group" aria-label="The photo ctrl panel...">
          <button type="button" class="btn btn-link" data-toggle="modal" data-target="[data-modal-photo={{ photo.id }}]" @click="loadMap()">
            <i class="fa fa-arrows-alt" aria-hidden="true"></i>
          </button>
          <button type="button" class="btn btn-link">
            <i class="fa fa-floppy-o" aria-hidden="true"></i>
          </button>
        </div>
      </div>
    </div>

    <div class="modal fade" data-modal-photo="{{ photo.id }}" tabindex="-1" role="dialog" aria-labelledby="Modal to photo..." v-for="photo in photos">
      <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title">{{ photo.title }}</h4>
          </div>

          <div class="modal-body">
            <div class="panel panel-info">
              <!-- Head -->
              <div class="panel-heading">
                <dl class="dl-horizontal">
                  <dt>ID:</dt>
                  <dd>{{ photo.id }}</dd>

                  <dt>Title:</dt>
                  <dd>{{ photo.title }}</dd>

                  <dt>Uploaded:</dt>
                  <dd>{{ photo.info.uploaded_at }}</dd>
                </dl>
              </div>

              <!-- Body -->
              <div class="panel-body">
                <img v-bind:src="photo.sizes[photo.sizes.length - 1].source" class="img-responsive" />
              </div>

              <!-- Footer -->
              <div class="panel-footer">

                <div class="map"
                  data-maps-attr-lat="{{ photo.info.location.latitude }}"
                  data-maps-attr-lng="{{ photo.info.location.longitude }}"
                  data-maps-attr-title="{{ photo.info.title }}"
                  data-maps-attr-content="{{ photo.info.description }}"
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
  </div>

  <div class="button-wrapper">
    <button @click="loadPhotos" class="ladda-button" data-color="green" data-style="expand-left">
      Load More!
    </button>
  </div>
</template>

<script>
  export default {

    data() {
      return {
        photos: [],
        page: 1,
        per_page: 25,
        wall: null,
        ladda: null
      }
    },

    methods: {
      loadPhotos() {
        this.ladda.start();

        this.$http.get('/photos?per_page=' + this.per_page + '&page=' + this.page++).then((response) => {
          this.ladda.stop();
          this.photos = this.photos.concat(response.json());

          setTimeout(function() {
            this.wall.fitWidth();
          }.bind(this), 500);
        });
      },

      loadMap() {
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
        //
      }
    },

    ready() {
      this.wall = new Freewall("#freewall");
      this.wall.reset({
            selector: '.cell',
            animate: true,
            cellW: 20,
            cellH: 300,
            onResize: function() {
                this.wall.fitWidth();
            }
        });

      this.ladda = Ladda.create(document.querySelector('.ladda-button'));
    }
  };
</script>

<style scoped>
  .button-wrapper {
    text-align: center;
    margin-bottom: 1em;
  }
</style>
