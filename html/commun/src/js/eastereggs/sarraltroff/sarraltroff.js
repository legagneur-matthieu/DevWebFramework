$sarraltroff_called = false;
sarraltroff = function (id) {
    if (!$sarraltroff_called) {
        $("head").append('<link rel="stylesheet" href="../commun/src/js/sarraltroff/sarraltroff.css" />');
        $sarraltroff_called = true;
    }
    var _id = "#" + id;

    this.start_loading = function (animated = true) {
        $(_id).addClass("sarraltroff").addClass((animated ? "sarraltroff-load-animate" : "sarraltroff-load"));
        return this;
    };
    this.stop_loading = function () {
        $(_id).removeClass("sarraltroff").removeClass("sarraltroff-load-animate").removeClass("sarraltroff-load");
        return this;
    };
};