(function ($, w) {

    function initPaginator(options)
    {
        $('#allDoctorsPagination').bootpag({
            total: options.pages,
            page: options.currentPage,
            next: options.next,
            prev: options.prev
        }).on("page", function(event, num){
            $("#allDoctorsTable").waiting({
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

            var filterVal = $('#allDoctorsNameFilter').val();

            $.ajax({
                url: options.url,
                data: {
                    page: num,
                    name :filterVal
                },
                success: function(result) {
                    $(".activeGrid").html(result);
                    options.currentPage = num;
                    initPaginator(options);
                },
                error: function() {
                    alert(options.error);
                    $("#allDoctorsTable").waiting("done");
                }
            });
        });
    }

    window.initAllDoctorsPaginator = initPaginator;
})(jQuery, window)