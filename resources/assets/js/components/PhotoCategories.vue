<template>
  <li>
    <div
      :class="{bold: isFolder}"
      @click="toggle"
      @dblclick="changeType">
      {{model.name}}
      <span v-if="isFolder">[{{open ? '-' : '+'}}]</span>
    </div>
    <ul v-show="open" v-if="isFolder">
      <photo-categories
        class="item"
        v-for="model in model.children"
        :model="model">
      </photo-categories>
      <li>
        <button type="button" class="btn btn-link" data-toggle="modal" data-target="#modalToCategory">Append</button>
      </li>
      <!-- <li class="add" @click="k">+</li> -->
    </ul>
  </li>

  <div id="modalToCategory" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="add new category to photo">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title">New Category</h4>
        </div>
        <div class="modal-body">
          <form v-on:submit.prevent="store" role="form" id="store" class="form-horizontal bs-component" accept-charset="utf-8" method="post" autocomplete="off">
            <div class="form-group label-floating">
              <label class="control-label" for="photoCategoryName">Name</label>
              <input type="text" id="photoCategoryName" class="form-control" name="name">
            </div>
            <div class="form-group label-floating">
              <label class="control-label" for="photoCategoryDesc">Description</label>
              <input type="text" id="photoCategoryDesc" class="form-control" name="description">
            </div>
          </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary">Save</button>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  data: function () {
    return {
      open: false
    }
  },
  props: {
    model: Object
  },
  computed: {
    isFolder: function () {
      return this.model.children &&
        this.model.children.length
    }
  },
  methods: {
    toggle: function () {
      if (this.isFolder) {
        this.open = !this.open
      }
    },
    changeType: function () {
      if (!this.isFolder) {
        Vue.set(this.model, 'children', [])
        this.addChild()
        this.open = true
      }
    },
    createChild: function() {
      //
    },
    addChild: function () {
      this.model.children.push({
        name: 'new stuff'
      })
    }
  }
};
</script>
