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
        <div class="col-sm-6 col-xs-12">
            <div class="well">
                <form v-on:submit.prevent="search" role="form" id="search" class="bs-component" accept-charset="utf-8" method="get" autocomplete="off">
                    <input type="hidden" name="page" value="1" />
                    <input type="hidden" name="per_page" value="30" />

                    <div class="form-group label-floating is-empty">
                      <label class="control-label" for="text">A free text search</label>
                      <input id="text" class="form-control" type="search" name="text">
                      <p class="help-block">Photos who's title, description or tags contain the text will be returned</p>
                    </div>

                    <div class="row">
                        <div class="col-sm-10">
                            <div class="form-group label-floating is-empty">
                              <label class="control-label" for="user_id">The NSID of the user who's photo to search</label>
                              <input id="user_id" class="form-control" type="text" name="user_id">
                              <p class="help-block">If this parameter isn't passed then everybody's public photos will be searched</p>
                            </div>
                        </div>
                        <div class="col-sm-2">
                            <button type="submit" id="btnSearch" class="btn btn-raised btn-primary">Search</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="col-sm-6 col-xs-12">
            <div class="well">
                select category
            </div>
        </div>
    </div>

    <!-- style="min-width: 240px; width: 200px;" -->

    <div class="row">
        <div class="col-sm-12">
            <div id="freewall" class="free-wall">
                <div class="thumbnail" v-for="item in photos" v-bind:style="{width: item.thumbnail.width+'px'}">
                    <img :src="item.thumbnail.source" v-bind:width="item.thumbnail.width+'px'" v-bind:height="item.thumbnail.height+'px'">

                    <div class="caption">
                        <h4 class="str-truncated">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Similique possimus maiores recusandae natus eum architecto soluta nisi quam tempore. Deserunt corporis minima, ullam. Cumque consequuntur repellendus doloribus soluta aut tempore.</h4>
                        <p>
                          <a href="javascript:void(0)" class="btn btn-raised btn-xs">Expand</a>
                          <a href="javascript:void(0)" class="btn btn-raised btn-xs">Save</a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
      <div class="col-sm-1 col-sm-offset-11">
        <!-- append button -->
        <button v-on:click.stop.prevent="append" v-show="photos.length" type="submit" form="search" class="btn btn-info btn-fab" style="margin: 20px;"><i class="fa fa-plus" aria-hidden="true"></i></button>
      </div>
    </div>

  </div>
</template>

<script>
  export default {
    data() {
      return {
        // default page to search
        page: 1,

        // current photo inside modal window
        item: {},

        // collections photos
        photos: [],

        // error message bug...
        errors: [],

        wall: null,

        btnStore: null,
        btnAppend: null,
        btnSearch: null,
      }
    },

    methods: {
      search: function(event) {
        this.resetSearch();
        this.append(event);
      },

      append: function(event) {
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

            // this.btnAppend.start();

            // reset errors bug...
            this.errors = [];
          },

          //
        };

        this.$http.get('/admin/photos/flickr/search', options).then((response) => {
          this.incrPage();
          this.photos = this.photos.concat(response.body);
        }, (response) => {
          this.errors.push({
            message: response.statusText || 'error'
          })
        });
      },

      buildData: function (node, params) {
        var data = {};

        for(var pair in (new FormData(node)).entries()) {
          data[ pair[0] ] = pair[1];
        }

        for (var x in (params || {})) {
          data[x] = params[x];
        }

        return data;
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
      }
    },

    ready() {
      // this.init();
      console.log('init');
    }
  };
</script>
