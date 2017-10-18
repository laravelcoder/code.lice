var duplicatesList;
(function ($) {

    $(document).ready(function () {
        duplicatesList = new List('duplicateLocations', {
            valueNames: ['id', 'name', 'street', 'city', 'state', 'zip', 'country'],
            page: 10,
            pagination: [{
                name: "pagination-bottom",
                paginationClass: "pagination-bottom",
                outerWindow: 1,
                innerWindow: 7
            }]
        });


        var $dupLocs = $("#duplicateLocations");
        $dupLocs.find(".keep-location").each(function (i, e) {
            $(this).on("click", function (e) {
                var identity = parseInt($(e.currentTarget).data("id"));
                var data = {id: identity};
                var url = window.location.origin + "/wp-admin/admin-ajax.php?action=KeepDuplicate";
                $.ajax({
                    method: "POST",
                    url: url,
                    data: data,
                    type: 'POST',
                    success: function (d) {
                        try {
                            var result;
                            if (typeof(d) == "string") {
                                result = JSON.parse(d);
                            }
                            else {
                                result = d;
                            }
                            if (result.success) {
                                duplicatesList.remove("id", identity);

                            }
                            else {
                                console.log(result.message);
                            }
                        } catch (e) {
                            console.log(e);
                        }
                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                        console.log(errorThrown);
                    }
                });
            });
        });
        $dupLocs.removeClass("hidden");
        $dupLocs.find(".dropdown-menu").find("li").each(function (j, listItem) {
            var $link = $($(listItem).find("a").first());
            $link.on("click", function (e) {
                e.preventDefault();
                var $target = $(e.currentTarget);
                var $listItem = $($target.parent("li").first());
                if (!$listItem.hasClass("disabled")) {
                    $listItem.siblings("li").each(function (i, sibling) {
                        $(sibling).removeClass("disabled");
                    });
                    $listItem.addClass("disabled");
                    ungeocodedList.page = $target.text();
                    ungeocodedList.update();
                }
            });
        });
    });
})(jQuery);


