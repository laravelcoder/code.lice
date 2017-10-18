var csvList;
var fileObj = new Blob();
(function ($) {
    $(document).ready(function () {
        var $file = $("#importCSV");
        $file.fileinput({
            showPreview: false,
            maxFileCount: 1,
            allowedFileExtensions: ['csv']
        });
        $file.on('fileloaded', function (event, file, previewId, index, reader) {
            var $uploadButton = $(event.target).parents(".file-input").find(".fileinput-upload-button");
            $uploadButton.on("click", function () {
                var parsedData = Papa.parse(file, {
                    delimiter: "",
                    newline: "",
                    header: true,
                    dynamicTyping: false,
                    preview: 0,
                    step: undefined,
                    encoding: "",
                    worker: false,
                    comments: false,
                    complete: completeFn,
                    error: errorFn,
                    download: false,
                    fastMode: undefined,
                    skipEmptyLines: true,
                    chunk: undefined,
                    beforeFirstChunk: undefined
                });
                console.log(parsedData);
            });
        });
        var $importModal = $("#importModal").modal({show: false});

    });


    function completeFn(results) {
        if (results.errors.length > 0) {
            var totalErrors = results.errors.length;
            $(results.errors).each(function (i, error) {
                var errorNumber = i + 1;
                console.log("Error " + errorNumber + " of " + totalErrors);
                console.log("Type: " + error.type);
                console.log(error.message + " in row " + error.row);
            });
        }
        else {
            $(".kv-upload-progress").removeClass("hide");
            var rows = new Array();
            $(results.data).each(function () {
                var isTreatmeantCenter = this["Type"].trim() === "Treatment Center" ? 1 : 0;
                var isBookable = this["Tags"].trim() === "book" ? 1 : 0;
                var row = {
                    sl_id: getInt(this["ID"]),
                    sl_store: this["Service Provider"],
                    sl_address: this["Location"],
                    sl_address2: "",
                    sl_city: "",
                    sl_state: "",
                    sl_country: "",
                    sl_zip: "",
                    sl_latitude: getDouble(this["Latitude"]),
                    sl_longitude: getDouble(this["Longitude"]),
                    sl_tags: this["Tags"],
                    sl_description: this["Description"],
                    sl_url: "",
                    sl_hours: "",
                    sl_phone: this["Number"],
                    sl_fax: "",
                    sl_email: this["Email"],
                    sl_image: this["Logo_LCoA"],
                    sl_private: "",
                    sl_neat_title: "",
                    sl_linked_postid: 0,
                    sl_pages_url: "",
                    sl_pages_on: "",
                    sl_option_value: "",
                    sl_type: this["Type"],
                    sl_facebook_phone_number: this["Facebook Phone Number"],
                    sl_website: this["Website"],
                    sl_seo_landing_page: this["SEO Landing Page"],
                    sl_real_phone: this["Real Phone #"],
                    sl_ppc_landing_page: "",
                    sl_landing_page: this["Landing Page"],
                    sl_first_name: this["First Name"],
                    sl_last_name: this["Last Name"],
                    sl_category: this["Category"],
                    sl_action: this["Action"],
                    sl_label: this["Label"],
                    sl_youtube_phone_number: this["YouTube Phone Number"],
                    sl_category_facebook: this["Category Facebook"],
                    sl_category_youtube: this["Category YouTube"],
                    sl_lcoa_locator: this["LCoA Locator"],
                    sl_book_appointment: isBookable,
                    sl_treatment_center: isTreatmeantCenter,
                    sl_formatted_address: "",
                    sl_duplicate_id: 0
                };
                rows.push(row);
            });
            importRows(rows);
        }
    }

    function errorFn(err, file) {
        end = now();
        console.log(err)
        console.log(file);
        disableButton();
    }

    function now() {
        return typeof window.performance !== 'undefined'
            ? window.performance.now()
            : 0;
    }

    function importRows(rows, info) {
        if (typeof info === 'undefined') {
            info = {
                success: 0,
                error: 0,
                complete: 0,
                total: rows.length,
                continue: true
            }
        }
        if (info.complete < info.total && info.continue) {
            var row = rows[info.complete];
            var data = {
                row: row
            };
            $.ajax({
                method: "POST",
                url: window.location.origin + "/wp-admin/admin-ajax.php?action=AddOrUpdateLocation",
                data: data,
                type: 'POST',
                success: function (d) {
                    var result = JSON.parse(d);
                    if (result.success) {
                        var store = result.row.sl_store + " (" + result.row.sl_formatted_address + ")";
                        info.success++;
                        var message = result.row.sl_duplicate_id != 0 ?
                            "<div class='log-entry'>Updated Successfully: " + store + "</div>" :
                            "<div class='log-entry'>Successful Upload: " + store + "</div>";
                        $("#importModal").find(".uploading-log").prepend(message);
                    }
                    else {
                        info.error++;
                        if (result.message === 'OVER_QUERY_LIMIT' || result.message === 'REQUEST_DENIED') {
                            info.continue = false;
                            var message = "<div class='log-entry'>Upload stopped.<br />Error Occurred: " + result.message + "</div>";
                            $("#importModal").find(".uploading-log").prepend(message);
                        }
                        else {
                            var message = "<div class='log-entry'>Error Occurred on row " + (info.complete + 1) + ": " + result.message + "</div>";
                            $("#importModal").find(".uploading-log").prepend(message);
                        }
                    }
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    var message = "<div class='log-entry'>Error Occurred: " + errorThrown + "</div>";
                    $("#importModal").find(".uploading-log").prepend(message);
                    info.error++;
                },
                complete: function () {
                    info.complete++;
                    var completed = parseFloat(parseInt((info.complete / info.total) * 10000) / 100);
                    var completedWhole = parseInt(completed);
                    var completedPercentage = completed + "%";
                    var $progress = $("#importModal").find(".progress-bar");
                    $progress.attr("aria-valuenow", completedWhole);
                    $progress.css("width", completedPercentage);
                    $progress.text(completedPercentage);
                    importRows(rows, info);
                }
            });
        }
        else {
            if (info.continue) {
                var message = "<div class='log-entry'><a class='btn btn-default' onclick='location.reload();'>Click Here</a> to reload page.</div><br />Upload complete.<br />Complete: " + info.complete + "<br />Success: " + info.success + "<br />Failure: " + info.error + "<br />";
                $("#importModal").find(".uploading-log").prepend(message);
            }
        }
    }

    function getInt(val) {
        if (typeof(val) === 'undefined') return 0;
        if (val === null) return 0;
        if (val == "") return 0;
        var parsedInt = parseInt(val);
        if (isNaN(parsedInt)) return 0;
        return parsedInt;
    }

    function getDouble(val) {
        if (typeof(val) === 'undefined') return 0;
        if (val === null) return 0;
        if (val == "") return 0;
        var parsedFloat = parseFloat(val);
        if (isNaN(parsedFloat)) return 0;
        return parsedFloat;
    }

})(jQuery);
