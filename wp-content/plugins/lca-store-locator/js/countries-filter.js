(function ($) {
    $(document).ready(function () {
        var $filterModal = $("#myFilterModal").modal({show: false});
        $("#filterCountries").on("click", function () {
            $filterModal.modal('show');
        });

        var $filters = $("#myFilterModal").find(".country-filter")
        $filters.each(function (index, filter) {
            var filter = $(filter).find("input").first();
            filter.on("change", function (e) {
                var filteredCountries = new Array();
                $("#myFilterModal").find(".country-filter").each(function (index, filter) {
                    var $filter = $(filter);
                    var $toggle = $filter.find(".toggle");
                    var countryName = $toggle.find("input").val().toUpperCase();
                    if (!$toggle.hasClass("off")) {
                        filteredCountries.push(countryName);
                    }
                });
                locationsList.filter(function (item) {

                    var filterItem = filteredCountries.includes(item.values().country);
                    return filterItem;
                });
            });
        });

        $("#usOnly").on("click", function () {
            $filters.each(function (index, filter) {
                var $filter = $(filter).find("input");
                var onOrOff = ($filter.val().toLowerCase() === "us") ? "on" : "off";
                $filter.bootstrapToggle(onOrOff);
            });
        });

        $("#allCountriesOn").on("click", function () {
            $filters.each(function (index, filter) {
                var $filter = $(filter).find("input");
                $filter.bootstrapToggle("on");
            });
        });

        $("#allCountriesOff").on("click", function () {
            $filters.each(function (index, filter) {
                var $filter = $(filter).find("input");
                $filter.bootstrapToggle("off");
            });
        });
    });

})(jQuery);