var productList;
(function ($) {
    $(document).ready(function () {
        var $locationModal = $("#locationModal").modal({show: false});
        var $treatmeantCenterToggle = $('#treatmentCenterCheckbox').bootstrapToggle({
            on: 'Yes',
            off: 'No'
        });

        var $bookAppointmentToggle = $('#bookableCheckbox').bootstrapToggle({
            on: 'Yes',
            off: 'No'
        });
        $locationModal.on("show.bs.modal", function (e) {
            $("#locationModal").addClass("loading");
            var data = {id: $(e.relatedTarget).data("id")};
            var url = window.location.origin + "/wp-admin/admin-ajax.php?action=GetLocation";
            $.ajax({
                method: "GET",
                url: url,
                data: data,
                success: function (d) {
                    try {
                        var result;
                        if (typeof(d) == "string") result = JSON.parse(d);
                        else result = d;

                        if (result.success) setDataForLocationModal(d.location);
                        else console.log(result.message);
                    } catch (e) {
                        console.log(e);
                    }
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    console.log(errorThrown);
                },
                complete: function () {
                    $("#locationModal").removeClass("loading");
                }
            });
        });


        $("#saveLocation").on("click", function () {
            $("#locationModal").addClass("loading");
            var row = getDataFromLocationModal();
            var data = {
                row: row
            };
            var url = window.location.origin + "/wp-admin/admin-ajax.php?action=AddOrUpdateLocation";
            $.ajax({
                method: "POST",
                url: url,
                data: data,
                type: 'POST',
                success: function (d) {
                    try {
                        var result;
                        if (typeof(d) == "string") result = JSON.parse(d);
                        else result = d;

                        if (result.success) {
                            location.reload();
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
                    $("#locationModal").removeClass("loading");
                }
            });
        });

        $("#addProduct").on("click", function () {
            var addProductText = $("#addProductText").val().trim();
            if (addProductText === "") {
                alert("Product text cannot be null");
            }
            else {
                var itemExists = false;
                $(productList.items).each(function () {
                    if (this._values.product === addProductText) itemExists = true;
                });
                if (itemExists) {
                    alert("Item Already Exists in list");
                }
                else {
                    productList.add({product: addProductText});
                    $("#addProductText").val("");
                }
            }
        });
        productList = new List('productListContainer', {
            valueNames: ['product'],
            item: '<li class="list-group-item"><span class="product"></span><span class="pull-right"><span class="glyphicon glyphicon-remove-circle" aria-hidden="true"></span></span></li>',
            listClass: "location-product-list"
        });
        productList.on("updated", function (list) {
            $(list.list).find(".glyphicon-remove-circle").each(function () {
                $(this).off();
                $(this).on("click", function (e) {
                    e.preventDefault();
                    var item = $(this).closest('li').find('.product').text();
                    productList.remove('product', item);
                });
            });

        });
    });

    function getDataFromLocationModal() {
        var loc = $("#locationModal").data("location");
        var newProductsArray = new Array();
        $(productList.items).each(function () {
            var product = this._values.product.split(' ').join('_');
            newProductsArray.push(product);
        });
        var tags = newProductsArray.join(',');


        var row = {
            sl_id: loc.id,
            sl_store: $("#storeName").val(),
            sl_address: $("#locationStreet1").val(),
            sl_address2: $("#locationStreet2").val(),
            sl_city: $("#locationCity").val(),
            sl_state: $("#locationState").val(),
            sl_country: $("#locationCountry").val(),
            sl_zip: $("#locationZip").val(),
            sl_latitude: loc.latitude,
            sl_longitude: loc.longitude,
            sl_tags: tags,
            sl_description: $("#locationDescription").val(),
            sl_url: $("#locationUrl").val(),
            sl_hours: $("#locationHours").val(),
            sl_phone: $("#locationPhone").val(),
            sl_fax: $("#locationFax").val(),
            sl_email: $("#locationEmail").val(),
            sl_image: $("#locationImage").val(),
            sl_private: $("#locationPrivate").val(),
            sl_neat_title: $("#locationNeatTitle").val(),
            sl_linked_postid: $("#locationLinkedPostId").val(),
            sl_pages_url: $("#locationPagesUrl").val(),
            sl_pages_on: $("#locationPagesOn").val(),
            sl_option_value: $("#locationOptionValue").val(),
            sl_type: $("#locationType").val(),
            sl_facebook_phone_number: $("#locationFacebookPhone").val(),
            sl_website: $("#locationWebsite").val(),
            sl_seo_landing_page: $("#locationLandingPageSEO").val(),
            sl_real_phone: $("#locationRealPhone").val(),
            sl_ppc_landing_page: $("#locationPpcLandingPage").val(),
            sl_landing_page: $("#locationLandingPageMain").val(),
            sl_first_name: $("#locationFirstName").val(),
            sl_last_name: $("#locationLastName").val(),
            sl_category: $("#locationCategory").val(),
            sl_action: $("#locationAction").val(),
            sl_label: $("#locationLabel").val(),
            sl_youtube_phone_number: $("#locationYoutubePhone").val(),
            sl_category_facebook: $("#locationFacebookCategory").val(),
            sl_category_youtube: $("#locationYoutubeCategory").val(),
            sl_lcoa_locator: $("#locationLcoaLocator").val(),
            sl_book_appointment: $($("#treatmentCenterCheckbox").parents(".toggle").first()).hasClass("off") ? 0 : 1,
            sl_treatment_center: $($("#bookableCheckbox").parents(".toggle").first()).hasClass("off") ? 0 : 1,
            sl_formatted_address: "",
            sl_duplicate_id: 0
        };
        return row;
    }

    function setDataForLocationModal(loc) {
        $("#locationModal").data("location", loc);
        $("#locationModalLabel").find("#storeName").val(loc.sl_store);
        $("#locationStreet1").val(loc.sl_address);
        $("#locationStreet2").val(loc.sl_address2);
        $("#locationCity").val(loc.sl_city);
        $("#locationState").val(loc.sl_state);
        $("#locationZip").val(loc.sl_zip);
        $("#locationCountry").val(loc.sl_country);
        $("#locationFirstName").val(loc.sl_first_name);
        $("#locationLastName").val(loc.sl_last_name);
        $("#locationPhone").val(loc.sl_phone);
        $("#locationEmail").val(loc.sl_email);
        $("#locationRealPhone").val(loc.sl_real_phone);
        $("#locationWebsite").val(loc.sl_website);
        $("#locationFacebookPhone").val(loc.sl_facebook_phone_number);
        $("#locationFacebookCategory").val(loc.sl_category_facebook);
        $("#locationYoutubePhone").val(loc.sl_youtube_phone_number);
        $("#locationYoutubeCategory").val(loc.sl_category_youtube);
        $("#locationLandingPageMain").val(loc.sl_landing_page);
        $("#locationLandingPageSEO").val(loc.sl_seo_landing_page);
        $("#locationCategory").val(loc.sl_category);
        $("#locationAction").val(loc.sl_action);
        $("#locationLabel").val(loc.sl_label);
        $("#locationLcoaLocator").val(loc.sl_lcoa_locator);
        $("#locationDescription").val(loc.sl_description);
        $("#locationImage").val(loc.sl_image);
        $("#locationUrl").val(loc.sl_url);
        $("#locationHours").val(loc.sl_hours);
        $("#locationFax").val(loc.sl_fax);
        $("#locationPrivate").val(loc.sl_private);
        $("#locationNeatTitle").val(loc.sl_neat_title);
        $("#locationLinkedPostId").val(loc.sl_linked_postid);
        $("#locationPagesUrl").val(loc.sl_pages_url);
        $("#locationPagesOn").val(loc.sl_pages_on);
        $("#locationOptionValue").val(loc.sl_option_value);
        $("#locationType").val(loc.sl_type);
        $("#locationPpcLandingPage").val(loc.sl_ppc_landing_page);

        loc.sl_treatment_center === "1" ? $('#treatmentCenterCheckbox').bootstrapToggle("on") : $('#treatmentCenterCheckbox').bootstrapToggle("off");
        loc.sl_book_appointment === "1" ? $('#bookableCheckbox').bootstrapToggle("on") : $('#bookableCheckbox').bootstrapToggle("off");

        if (loc.sl_tags !== '') {
            $(loc.sl_tags.split(',')).each(function () {
                productList.add({
                    product: this.split('_').join(' ').trim()
                });
            });
        }

        var latitude = parseFloat(loc.sl_latitude);
        var longitude = parseFloat(loc.sl_longitude);
        var center = [latitude, longitude];
        var map = new GMaps({
            el: '#locationModalMap',
            lat: latitude,
            lng: longitude
        });
        map.addMarker({
            lat: latitude,
            lng: longitude,
            title: loc.sl_store
        });
    }
})(jQuery);