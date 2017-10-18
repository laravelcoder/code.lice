(function ($) {
    $(document).ready(function () {
        var $exportFilers = $("#exportModal").find(".csv-filter");
        $exportFilers.each(function (index, filter) {
            var $toggle = $(filter).find(".toggle");
            var $input = $toggle.find("input");
            $input.on("change", function (e) {
                var count = 0;
                $exportFilers.each(function (index, filter) {
                    var $toggle = $(filter).find(".toggle");
                    if (!$toggle.hasClass("off")) {
                        count++;
                    }

                });
                if(count > 0){
                    $("#exportCsvBtn").removeAttr("disabled");
                }
                else{
                    $("#exportCsvBtn").attr("disabled", "disabled");
                }
            });
        });
        $("#exportCsvBtn").on("click", function (e) {
            var removeFields = new Array();
            $exportFilers.each(function (index, filter) {
                var $toggle = $(filter).find(".toggle");
                var toggleName = $toggle.find("input").val();
                if ($toggle.hasClass("off")) {
                    removeFields.push(toggleName);
                }
            });

            var jsonList = locationsList.toJSON();
            $(jsonList).each(function (i, obj) {
                $(removeFields).each(function (j, field) {
                    delete obj[field];
                });
            });
            var csv = Papa.unparse(jsonList);
            var data, filename, link;
            if (csv == null) return;
            var date = new Date();
            filename = "export_lca_" + date.getFullYear() + "-" + (date.getMonth() + 1) + "-" + date.getDate() + "_" + date.getHours() + "-" + date.getMinutes() + "-" + date.getSeconds() + ".csv";

            if (!csv.match(/^data:text\/csv/i)) {
                csv = 'data:text/csv;charset=utf-8,' + csv;
            }
            data = encodeURI(csv);

            link = document.createElement('a');
            link.setAttribute('href', data);
            link.setAttribute('download', filename);
            link.click();
        });

        $("#checkAll").on("click", function () {
            $exportFilers.each(function (index, filter) {
                var $filter = $(filter).find("input");
                $filter.bootstrapToggle("on");
            });
        });
        $("#uncheckAll").on("click", function () {
            $exportFilers.each(function (index, filter) {
                var $filter = $(filter).find("input");
                $filter.bootstrapToggle("off");
            });
        });
    });
})(jQuery);