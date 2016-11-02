<nav class="navbar navbar-inverse navbar-fixed-top">
  <div class="container-fluid">
    <div class="navbar-header">
      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
      <a class="navbar-brand" href="{{ url('admin') }}">Dashboard</a>
    </div>
    <div id="navbar" class="navbar-collapse collapse">
      <ul class="nav navbar-nav navbar-right">

        <li>
          <a href="{{ url('admin') }}"><i class="fa fa-tachometer" aria-hidden="true"></i></a>
        </li>

        <li>
          <a href="#/settings"><i class="fa fa-wrench" aria-hidden="true"></i></a>
        </li>

        <li>
          <a href="#/profile"><i class="fa fa-user" aria-hidden="true"></i></a>
        </li>

        <li>
          <a href="#/help"><i class="fa fa-question" aria-hidden="true"></i></a>
        </li>

        <li>
          <a href="{{ url('logout') }}"><i class="fa fa-sign-out" aria-hidden="true"></i></a>
        </li>

      </ul>
      <form method="GET" action="#/search" class="navbar-form navbar-right">
        <input type="text" class="form-control" placeholder="Search...">
      </form>
    </div>
  </div>
</nav>
