
@push('styles')
<style type="text/css">
  #mynetwork {
    width: 100%;
    height: 500px;
    border: 1px solid lightgray;
  }
</style>
@endpush

@push('scripts')
<script type="text/javascript" src="http://visjs.org/examples/network/exampleUtil.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/vis/4.16.1/vis.min.js"></script>
<script type="text/javascript">
  var nodes = null;
  var edges = null;
  var network = null;

  function destroy() {
      if (network !== null) {
          network.destroy();
          network = null;
      }
  }

  function draw() {
      destroy();
      // randomly create some nodes and edges
      var nodeCount = 3;
      var data = getScaleFreeNetwork(nodeCount)

      var nodes = new vis.DataSet([
          {id: 1, label: 'Node 1'},
          {id: 2, label: 'Node 2'},
          {id: 3, label: 'Node 3'},
          {id: 4, label: 'Node 4'},
          {id: 5, label: 'Node 5'}
        ]);

        // create an array with edges
        var edges = new vis.DataSet([
          {from: 1, to: 3},
          {from: 1, to: 2},
          {from: 2, to: 4},
          {from: 2, to: 5}
        ]);

        var data = {
          nodes: nodes,
          edges: edges
        };

      // create a network
      var container = document.getElementById('mynetwork');
      var options = {
          layout: {
              hierarchical: {
                  direction: "UD"
              }
          }
      };
      network = new vis.Network(container, data, options);

      // add event listeners
      network.on('select', function (params) {
          console.log(params.nodes);
      });
  }

  // call
  draw();
</script>
@endpush

<div class="container">
  <div class="row">
    <div class="col-sm-12">
      <div id="mynetwork"></div>
    </div>
  </div>
</div>


{{-- <ul id="tree">
  <li class="lead">My Tree <i class="fa fa-plus" aria-hidden="true"></i></li>
  <li>
    <ul>
      <li>foo <span class="tree-expand">[ <i class="fa fa-plus" aria-hidden="true"></i> ]</span></li>
      <li>bar <span class="tree-expand">[ <i class="fa fa-minus" aria-hidden="true"></i> ]</span></li>
      <li>
        <ul>
          <li>baz</li>
          <li>bax</li>
          <li>
            <ul>
              <li>one</li>
              <li><button type="button" class="btn btn-raised btn-xs btn-link btn-tree" data-toggle="modal" data-target="#FOFOFO">Append</button></li>
            </ul>
          </li>
          <li><button type="button" class="btn btn-raised btn-xs btn-link btn-tree" data-toggle="modal" data-target="#FOFOFO">Append</button></li>
        </ul>
      </li>
      <li><button type="button" class="btn btn-raised btn-xs btn-link btn-tree" data-toggle="modal" data-target="#FOFOFO">Append</button></li>
    </ul>
  </li>
  <li><button type="button" class="btn btn-raised btn-xs btn-link btn-tree" data-toggle="modal" data-target="#FOFOFO">Append</button></li>
</ul>

<div id="FOFOFO" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="add-new-category-to-photo">
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


<ul id="demo">
  <photo-categories class="item" :model="treeData"></photo-categories>
</ul> --}}

{{-- <div class="list-group">
  <div class="list-group-item">
    <div class="row-action-primary">
      <i class="fa fa-object-group" aria-hidden="true"></i>
    </div>
    <div class="row-content">
      <div class="least-content">15m</div>
      <h4 class="list-group-item-heading">Tile with a label</h4>

      <p class="list-group-item-text">Donec id elit non mi porta gravida at eget metus.</p>
    </div>
  </div>
  <div class="list-group-separator"></div>
</div> --}}


