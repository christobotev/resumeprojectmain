(function($, w) {

    $(document).ready(function(){
       $(".items-count-per-page").change(function(){
           window.location = $("option:selected", this).attr("data-url");
       });
    });

    /**
     * Prepare the grid filters - disable all empty filter
     * fields before form submit
     */
    w.prepareGridFilter = function(filterFormID) {

        $(".grid-filter").on("submit", function() {

            $(this).find(":input").each(function(i, el) {

                if (el.value == 0 && (!el.dataset.allowempty || el.multiple)) {
                    el.disabled = true;
                }

            });
        });
    }
})(jQuery, window);
