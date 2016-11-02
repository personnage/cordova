@include('admin.dashboard._panels')

<div class="row">
  <!-- pipe -->

  <div class="col-xs-12 col-sm-4">
    <h3>Latest users</h3>
    <hr>
    @foreach ($stats->latestUser() as $user)
        <p>
          <a href="{{ url('admin/user', $user->id) }}" class="str-truncated">{{ $user->name }}</a>
          <span class="pull-right">
            <time class="text-muted">{{ $user->created_at->diffForHumans() }}</time>
          </span>
        </p>
    @endforeach
  </div>

  <!-- pipe -->

  <div class="col-xs-12 col-sm-4">
    <h3>Latest news</h3>
    <hr>
    <p>
      <a href="#" class="str-truncated" data-toggle="tooltip" data-placement="top" title="Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</a>
      <span class="pull-right">
        <time class="text-muted">1 days ago</time>
      </span>
    </p>
    <p>
      <a href="#" class="str-truncated" data-toggle="tooltip" data-placement="top" title="Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.">Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</a>
      <span class="pull-right">
        <time class="text-muted">2 months ago</time>
      </span>
    </p>

  </div>

  <!-- pipe -->

  <div class="col-xs-12 col-sm-4">
    <h3>Latest artiles</h3>
    <hr>
    <p>
      <a href="#" class="str-truncated" data-toggle="tooltip" data-placement="top" title="Как поесть бесплатно?">Как поесть бесплатно?</a>
      <span class="pull-right">
        <time class="text-muted">6 days ago</time>
      </span>
    </p>
    <p>
      <a href="#" class="str-truncated" data-toggle="tooltip" data-placement="top" title="Поедим! Поедим!">Поедим! Поедим!</a>
      <span class="pull-right">
        <time class="text-muted">19 days ago</time>
      </span>
    </p>
    <p>
      <a href="#" class="str-truncated" data-toggle="tooltip" data-placement="top" title="Тараканы в ресторане у Петро!">Тараканы в ресторане у Петро!</a>
      <span class="pull-right">
        <time class="text-muted">about a month ago</time>
      </span>
    </p>
  </div>
</div>
