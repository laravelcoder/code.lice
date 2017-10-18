var locationsList;
(function ($) {

    $(document).ready(function () {
        locationsList = new List('locations', {
            valueNames: ['id', 'name', 'address', 'country'],
            page: 10,
            pagination: [{
                name: "pagination-bottom",
                paginationClass: "pagination-bottom",
                outerWindow: 1,
                innerWindow: 7
            }]
        });
        $(".delete-location").each(function () {
            $(this).on("click", function (e) {
                $(".plugin_config").addClass("loading");
                var identity = parseInt($(e.currentTarget).data("id"));
                var data = {id: identity};
                var url = window.location.origin + "/wp-admin/admin-ajax.php?action=DeleteLocation";
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
                                if (locationsList.items.length > 0) {
                                    locationsList.remove("id", identity);
                                }
                                if (duplicatesList.items.length > 0) {
                                    duplicatesList.remove("id", identity);
                                    if(duplicatesList.size() === 0){
                                        location.reload();
                                    }
                                }
                                if (ungeocodedList.items.length > 0) {
                                    ungeocodedList.remove("id", identity);
                                    if(ungeocodedList.size() === 0){
                                        location.reload();
                                    }
                                }

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
                    },
                    complete: function () {
                        $(".plugin_config").removeClass("loading");
                    }
                });
            });

        });

        $locations = $("#locations");
        $locations.removeClass("hidden");
        $locations.find(".dropdown-menu").find("li").each(function (j, listItem) {
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
                    locationsList.page = $target.text();
                    locationsList.update();
                }
            });
        });
    });

})(jQuery);
