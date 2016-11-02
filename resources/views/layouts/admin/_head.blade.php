<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <title>
    @hasSection('title')
        Dashboard: @yield('title')
    @else
        Dashboard
    @endif
  </title>
  <meta name="description" content="@yield('description')">


  <link href="{{ elixir('css/admin.css') }}" rel="stylesheet">

  {{-- Override std styles --}}
  @stack('styles')

  <script>
    window.Laravel = <?php echo json_encode([
      'csrfToken' => csrf_token(),
      ]); ?>
  </script>
</head>
