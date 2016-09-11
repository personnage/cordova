(function () {
    var wall = new Freewall("#freewall");

    wall.reset({
        selector: '.cell',
        animate: true,
        cellW: 20,
        cellH: 300,
        onResize: function() {
            wall.fitWidth();
        }
    });
    wall.fitWidth();
    // for scroll bar appear;
    $(window).trigger("resize");
})(this);
