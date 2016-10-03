<template>
<div class="container-fluid">

  <!-- Error message panel... -->
  <div class="row" v-show="errors.length">
    <div class="col-sm-8 col-sm-offset-2">
      <div class="panel panel-danger">
        <div class="panel-heading">
          <h3 class="panel-title">Whoops, looks like something went wrong!</h3>
        </div>
        <div class="panel-body">
          <ul>
            <li v-for="error in errors">
              {{ error.message }}
            </li>
          </ul>
        </div>
      </div>
    </div>
  </div>
  <!-- Error message panel... -->

  <div class="row">
    <div class="col-sm-8 col-sm-offset-2">
      <div class="panel panel-default">
        <div class="panel-heading">
          <button type="submit" form="search" id="btnSearch"
            class="btn btn-default btn-lg btn-block"
            data-style="slide-down">
            Search by Flickr ...
          </button>
        </div>
        <div class="panel-body">
          <form v-on:submit.prevent="search" role="form" id="search" class="bs-component" accept-charset="utf-8" method="get" autocomplete="off">

            <div class="form-group label-floating is-empty">
              <label class="control-label" for="message">A free text search...</label>
              <input id="message" class="form-control" type="search" name="message">
              <p class="help-block">Photos who's title, description or tags contain the text will be returned.</p>
            </div>

            <input type="hidden" name="page" value="1" />
            <input type="hidden" name="per_page" value="30" />

          </form>
        </div>
      </div>
    </div>
  </div>

  <div class="row">
    <div class="col-sm-12">
      <div id="freewall" class="free-wall">
        <div class="cell" v-for="item in photos"
          v-bind:style="{
            width: item.thumbnail.width + 'px',
            height: item.thumbnail.height + 'px',
            backgroundImage: 'url(' + item.thumbnail.source + ')'
          }"
        >
          <div class="ctrl-panel">
            <div class="btn-group" role="group" aria-label="The photo ctrl panel...">
              <button type="button" class="btn btn-link" data-toggle="modal" data-target="#modalToPhoto" data-photo-id="{{item.photo.id}}" data-photo-index={{$index}}>
                <i class="fa fa-arrows-alt" aria-hidden="true"></i>
              </button>
              <button type="button" class="btn btn-link">
                <i class="fa fa-floppy-o" aria-hidden="true"></i>
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="row">
    <button v-on:click.stop.prevent="append" v-show="photos.length" type="submit" form="search" id="btnAppend" class="center-block" data-color="mint" data-style="expand-left">More ...</button>
  </div>

  <!-- Modal window -->
  <div id="modalToPhoto" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="Modal to photo...">
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title">{{ photoTitle }}</h4>
        </div>

        <div class="modal-body">
          <div class="panel panel-in">
            <!-- Head -->
            <div class="panel-heading">
              #{{ photoId }}
            </div>
            <!-- end Head -->

            <!-- Body -->
            <div class="panel-body">

              <div class="row">
                <!-- Main for to store photo -->
                <form v-on:submit.prevent="store" role="form" id="store" class="form-horizontal bs-component" accept-charset="utf-8" method="post" autocomplete="off">

                  <fieldset>
                    <input type="hidden" name="provider" value="flickr"/>
                    <input type="hidden" name="extern_id" v-bind:value="photoId"/>
                    <input type="hidden" name="location[latitude]" v-bind:value="photoLatitude"/>
                    <input type="hidden" name="location[longitude]" v-bind:value="photoLongitude"/>
                  </fieldset>

                  <fieldset>
                    <div class="form-group">
                      <label for="photoTitle" class="col-sm-2 control-label">Title</label>

                      <div class="col-sm-9">
                        <input type="text" id="photoTitle" class="form-control" v-bind:value="photoTitle" name="title">
                      </div>
                    </div>

                    <div class="form-group">
                      <label for="photoDescription" class="col-sm-2 control-label">Description</label>

                      <div class="col-sm-9">
                        <input type="text" id="photoDescription" class="form-control" v-bind:value="photoDescription" name="description">
                      </div>
                    </div>

                    <div class="form-group">
                      <label for="photoTags" class="col-sm-2 control-label">Tags</label>

                      <div class="col-sm-9">
                        <input type="text" id="photoTags" class="form-control" v-bind:value="photoTags" name="tags">
                      </div>
                    </div>

                  </fieldset>
                </form>
              </div>

              <div class="row">
                <div class="col-sm-12">
                  <img v-bind:src="photoPath" class="img-responsive" />
                </div>
              </div>

              <hr>

              <div class="row">
                <div class="col-sm-12">
                  <div id="map" class="map"></div>
                </div>
              </div>

            </div>
            <!-- end Body -->


          </div>
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary" form="store">Save</button>
        </div>

      </div>
    </div>
  </div>
  <!-- End modal -->

</div>
</template>

<script>
  export default {
    data: function() {
      return {
        page: 1,    // default page to search

        item: {},   // current photo inside modal window
        photos: [], // collections photos

        errors: [], // error message bug...

        wall: null,

        btnStore: null,
        btnAppend: null,
        btnSearch: null,
      }
    },

    computed: {
      photoId: function() {
        try {
          return this.item.photo.id;
        } catch (error) {
          //
        }
      },

      photoTitle: function() {
        try {
          return this.item.info.title;
        } catch (error) {
          //
        }
      },

      photoDescription: function() {
        try {
          return this.item.info.description;
        } catch (error) {
          //
        }
      },

      photoTags: function() {
        try {
          var tags = [];

          this.item.info.tags.forEach(function(tag) {
            tags.push(tag.name);
          });

          return tags.join(',');
        } catch (error) {
          //
        }
      },

      photoPath: function() {
        try {
          return this.item.sizes[this.item.sizes.length - 1].source;
        } catch (error) {
          return 'data:image/gif;base64,R0lGODlhAQABAIAAAAUEBAAAACwAAAAAAQABAAACAkQBADs=';
        }
      },

      photoLatitude: function() {
        try {
          return this.item.info.location.latitude;
        } catch (error) {
          //
        }
      },

      photoLongitude: function() {
        try {
          return this.item.info.location.longitude;
        } catch (error) {
          //
        }
      },
    },

    methods: {
      /**
       * Store photo to server.
       *
       * @param  Object event
       * @return void
       */
      store: function(event) {
        var body = new FormData(document.getElementById('store'));

        this.$http.post('/api/photos', body).then((response) => {
          console.log('pass');
          console.log(response);
          console.log(response.json());
        }, (response) => {
          console.log('fail');
          console.log(response);
        });
      },

      /**
       * Exec search to API.
       *
       * @param  Object event
       * @return void
       */
      search: function(event) {
        this.btnSearch.start();
        this.resetSearch();
        this.append(event);
      },

      /**
       * Exec search to API and incr page.
       *
       * @param  Object event
       * @return void
       */
      append: function (event) {
        var options = {
          // Parameters object to be sent as URL parameters.
          params: this.buildData(
            document.getElementById('search'), {page: this.page}
          ),

          // Abort previous request, if exists.
          before: (request) => {
            if (this.previousRequest) {
              this.previousRequest.abort();
            }

            this.previousRequest = request;

            this.btnAppend.start();

            // reset errors bug...
            this.errors = [];
          },

          //
        };

        var successCallback = this.responseCallback(function (response) {
          this.photos = this.photos.concat(response.json());
        }.bind(this));

        this.$http.get('/flickr', options).then(successCallback, (response) => {
          try {
            console.log(response.json());
          } catch (error) {
            this.errors.push({
              message: response.statusText
            })
          }
        });
      },

      /**
       * Decorator to user success callback.
       *
       * @param  Function  callback
       * @return Funtion
       */
      responseCallback: function (callback) {
        return function (response) {
          this.incrPage();
          // reset images...
          setTimeout(this.freeWallCallback(), 700);

          callback(response);

          this.btnSearch.stop();
          this.btnAppend.stop();
        }.bind(this);

      },

      buildData: function (node, params) {
        var data = {};

        for(var pair of (new FormData(node)).entries()) {
          data[ pair[0] ] = pair[1];
        }

        for (var x in (params || {})) {
          data[x] = params[x];
        }

        return data;
      },

      /**
       * Transform Vue data object to plain js hash.
       *
       * @deprecated
       * @return Object
       */
      getDataAsPlainObject: function () {
        return JSON.parse(JSON.stringify(this.$data));
      },

      /**
       * Incr page.
       *
       * @return Int
       */
      incrPage: function () {
        return ++this.page;
      },

      /**
       * Reset page to default.
       *
       * @return void
       */
      resetPage: function () {
        this.page = 1;
      },

      /**
       * Reset photos array to fresh search.
       *
       * @return void
       */
      resetPhotos: function () {
        this.photos.length = 0;
      },

      /**
       * Reset all options to fresh search.
       *
       * @return void
       */
      resetSearch: function () {
        this.resetPage();
        this.resetPhotos();
      },

      init: function () {
        this.initModal();
        this.initFreewall(); // why?

        this.btnAppend = Ladda.create(document.getElementById('btnAppend'));
        this.btnSearch = Ladda.create(document.getElementById('btnSearch'));
      },

      getMap: function() {
        var container = document.querySelector('#modalToPhoto .map');

        var removeAllChild = function(node) {
          while (node.firstChild) {
              node.removeChild(node.firstChild);
          }

          return node;
        };

        return new ymaps.Map(removeAllChild(container), {
          center: [null, null],
          zoom: 10,
          controls: ['smallMapDefaultSet'],
        });
      },

      initModal: function() {
        var map, that = this;

        $('#modalToPhoto').on('shown.bs.modal', function(event) {
          $('#photoTags').selectize({
              delimiter: ',',
              persist: false,
              create: function(input) {
                  return {
                      value: input,
                      text: input
                  }
              }
          });
        });

        $('#modalToPhoto').on('show.bs.modal', function(event) {
          map = that.getMap();

          var photo,
              photoId = $(event.relatedTarget).data('photoId'),
              photoIndex = $(event.relatedTarget).data('photoIndex')
          ;

          if (that.photos[ photoIndex ].photo.id == photoId) {
            // search photo by index inside photos array. (quickly)
            photo = that.photos[ photoIndex ];
          } else {
            // else for each to evry item.
            photo = that.searchPhotoById(photoId);
          }

          try {
            that.item = photo;

            that.showMap(map, {
              lat: that.item.info.location.latitude,
              lng: that.item.info.location.longitude,
            });
          } catch (error) {
            console.error('Photo #{id} not found!'.replace(/\{id\}/g, photoId));
          }

        })
        .on('hidden.bs.modal', function(event) {
          // remove photo ref.
          that.item = {};
          map && map.geoObjects.removeAll();
        });

      },

      initFreewall: function () {
        this.wall = new Freewall("#freewall");

        this.wall.reset({
            selector: '.cell',
            animate: true,
            cellW: 20,
            cellH: 300,
            onResize: this.freeWallCallback()
        });
      },

      freeWallCallback: function () {
        return function () {
          return this.wall.fitWidth();
        }.bind(this);
      },

      searchPhotoById: function(photoId) {
        for (var i = 0; i < this.photos.length; ++i) {
          if (this.photos[i].id == photoId) {
            return this.photos[i];
          }
        }

        return null;
      },

      showMap: function(map, options) {
        var placemark, myLatLng = [options.lat, options.lng];

        map.setCenter(myLatLng);

        placemark = new ymaps.GeoObject({
            // Описание геометрии.
            geometry: {
                type: "Point",
                coordinates: map.getCenter(),
            },
            // Свойства.
            properties: {
                //
            }
        }, {
            // Опции.
            // Иконка метки будет растягиваться под размер ее содержимого.
            preset: 'islands#circleIcon',
            // Метку можно перемещать.
            draggable: true
        });

        map.geoObjects.add(placemark);
      },

      //

    },

    ready() {
      this.init();
    }
  };
</script>
