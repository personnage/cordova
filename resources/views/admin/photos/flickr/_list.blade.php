@push('styles')
  <script src="https://api-maps.yandex.ru/2.1/?lang=ru_RU" type="text/javascript"></script>

  <style>
    .free-wall .thumbnail {
        display: inline-block;
        margin: 12px;
        min-width: 220px;
    }
  </style>
@endpush

@push('scripts')
<script type="text/javascript">
    // var wall = new Freewall("#freewall");
    // wall.reset({
    //     selector: '.cell',
    //     animate: true,
    //     cellW: 20,
    //     cellH: 200,
    //     onResize: function() {
    //         wall.fitWidth();
    //     }
    // });
    // wall.fitWidth();
    // // for scroll bar appear;
    // $(window).trigger("resize");
</script>
@endpush

<photos></photos>
