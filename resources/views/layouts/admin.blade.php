<!DOCTYPE html>
<html lang="en">
  @include('layouts.admin._head')

  <body>
    @include('layouts.admin._nav')

    <div id="vue-app" class="container-fluid">
      <div class="row">

        @hasSection('sidebar')
            @yield('sidebar')
        @else
            @include('layouts.admin._sidebar')
        @endif

        <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
          @yield('content')
        </div>
      </div>
    </div>

    <script src="{{ elixir('js/admin.js') }}"></script>

    {{-- Override or append scripts --}}
    @stack('scripts')

    @if(config('app.env') === 'local')
      @include('layouts.admin._bootlint')
    @endif
  </body>
</html>
