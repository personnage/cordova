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
          <button type="button" class="btn btn-link" data-toggle="modal" data-target="[data-modal-photo={{ photo.id }}]">
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
            <img v-bind:src="photo.item.source" class="img-responsive" alt="Responsive image">
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