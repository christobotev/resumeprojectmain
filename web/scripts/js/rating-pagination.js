(function ($, w) {

    function initPaginator(options)
    {
        $('#ratingPagination').bootpag({
            total: options.pages,
            page: options.currentPage,
            next: options.next,
            prev: options.prev
        }).on("page", function(event, num){
            $("#listRatings").waiting({
                size: 40,
                quantity: 12,
                dotSize: 8,
                enableReverse: true,
                waitMovementIncrementer: 1,
                light: false,
                fullScreen: false,
                speed: 80,
                circleCount: 1,
                tailPercent: 1
            });
            $.ajax({
                url: options.url,
                data: {
                    page: num
                },
                success: function(result) {
                    $("#ratingsSnippet").html(result);
                    options.currentPage = num;
                    initPaginator(options);
                },
                error: function() {
                    alert(options.error);
                    $("#listRatings").waiting("done");
                }
            });
        });
    }

    window.initRatingPaging = initPaginator;
})(jQuery, window)