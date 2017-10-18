var ungeocodedList;
(function ($) {

    $(document).ready(function () {
        ungeocodedList = new List('geocodeLocations', {
            valueNames: ['id', 'name', 'street', 'city', 'state', 'zip', 'country'],
            page: 10,
            pagination: [{
                name: "pagination-bottom",
                paginationClass: "pagination-bottom",
                outerWindow: 1,
                innerWindow: 7
            }]
        });
        var $geoLocs = $("#geocodeLocations");
        $geoLocs.find(".geocode-location").each(function (i, e) {
            $(this).on("click", function (e) {
                $(".plugin_config").addClass("loading");
                var $btn = $(e.currentTarget);
                var id = parseInt($btn.parents(".location-row").find(".id").text());
                var row = {
                    sl_id: id,
                    sl_store: "",
                    sl_address: "",
                    sl_address2: "",
                    sl_city: "",
                    sl_state: "",
                    sl_country: "",
                    sl_zip: "",
                    sl_latitude: "",
                    sl_longitude: "",
                    sl_tags: "",
                    sl_description: "",
                    sl_url: "",
                    sl_hours: "",
                    sl_phone: "",
                    sl_fax: "",
                    sl_email: "",
                    sl_image: "",
                    sl_private: "",
                    sl_neat_title: "",
                    sl_linked_postid: 0,
                    sl_pages_url: "",
                    sl_pages_on: "",
                    sl_option_value: "",
                    sl_type: "",
                    sl_facebook_phone_number: "",
                    sl_website: "",
                    sl_seo_landing_page: "",
                    sl_real_phone: "",
                    sl_ppc_landing_page: "",
                    sl_landing_page: "",
                    sl_first_name: "",
                    sl_last_name: "",
                    sl_category: "",
                    sl_action: "",
                    sl_label: "",
                    sl_youtube_phone_number: "",
                    sl_category_facebook: "",
                    sl_category_youtube: "",
                    sl_lcoa_locator: "",
                    sl_book_appointment: "",
                    sl_treatment_center: 0,
                    sl_formatted_address: "",
                    sl_duplicate_id: 0
                };
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
                            result.row.sl_id
                            locationsList.add({
                                id: result.row.sl_id,
                                name: result.row.sl_store,
                                address: result.row.sl_formatted_address,
                                country: result.row.sl_country
                            });
                            locationsList.update();
                            ungeocodedList.remove("id", id);
                            ungeocodedList.update();
                        }
                        else {
                            console.log(result.message);
                        }
                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                        console.log(textStatus);
                        console.log(errorThrown);
                    },
                    complete: function () {
                        $(".plugin_config").removeClass("loading");
                    }
                });
            });
        });
        $geoLocs.removeClass("hidden");
        $geoLocs.find(".dropdown-menu").find("li").each(function (j, listItem) {
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

    var GeocodeResult = function (r) {
        var geocodeResult = {
            street_address: null, // indicates a precise street address.
            route: null, //  indicates a named route (such as "US 101").
            intersection: null, //  indicates a major intersection, usually of two major roads.
            country: null, //  indicates the national political entity, and is typically the highest order type returned by the Geocoder.
            administrative_area_level_1: null, //  indicates a first-order civil entity below the country level. Within the United States, these administrative levels are states. Not all nations exhibit these administrative levels.
            administrative_area_level_2: null, //  indicates a second-order civil entity below the country level. Within the United States, these administrative levels are counties. Not all nations exhibit these administrative levels.
            administrative_area_level_3: null, //  indicates a third-order civil entity below the country level. This type indicates a minor civil division. Not all nations exhibit these administrative levels.
            administrative_area_level_4: null, //  indicates a fourth-order civil entity below the country level. This type indicates a minor civil division. Not all nations exhibit these administrative levels.
            administrative_area_level_5: null, //  indicates a fifth-order civil entity below the country level. This type indicates a minor civil division. Not all nations exhibit these administrative levels.
            locality: null, //  indicates an incorporated city or town political entity.
            postal_code: null, //  indicates a postal code as used to address postal mail within the country.
            street_number: null, // indicates the precise street number.
            floor: null, // indicates the floor of a building address.
            room: null // indicates the room of a building address.
        }

        $(r.address_components).each(function () {
            var addressComponent = this;
            var $types = $(addressComponent.types);
            var long = addressComponent.long_name;
            var short = addressComponent.short_name;
            var name = (typeof short !== 'undefined') ? short : long;
            $types.each(function () {
                var type = this.toString();
                if (type === 'street_address' && geocodeResult.street_address === null) {
                    geocodeResult.street_address = name;
                } else if (type === 'route' && geocodeResult.route === null) {
                    geocodeResult.route = name;
                } else if (type === 'intersection' && geocodeResult.intersection === null) {
                    geocodeResult.intersection = name;
                } else if (type === 'country' && geocodeResult.country === null) {
                    geocodeResult.country = name;
                } else if (type === 'administrative_area_level_1' && geocodeResult.administrative_area_level_1 === null) {
                    geocodeResult.administrative_area_level_1 = name;
                } else if (type === 'administrative_area_level_2' && geocodeResult.administrative_area_level_2 === null) {
                    geocodeResult.administrative_area_level_2 = name;
                } else if (type === 'administrative_area_level_3' && geocodeResult.administrative_area_level_3 === null) {
                    geocodeResult.administrative_area_level_3 = name;
                } else if (type === 'administrative_area_level_4' && geocodeResult.administrative_area_level_4 === null) {
                    geocodeResult.administrative_area_level_4 = name;
                } else if (type === 'administrative_area_level_5' && geocodeResult.administrative_area_level_5 === null) {
                    geocodeResult.administrative_area_level_5 = name;
                } else if (type === 'locality' && geocodeResult.locality === null) {
                    geocodeResult.locality = name;
                } else if (type === 'postal_code' && geocodeResult.postal_code === null) {
                    geocodeResult.postal_code = name;
                } else if (type === 'street_number' && geocodeResult.street_number === null) {
                    geocodeResult.street_number = name;
                } else if (type === 'floor' && geocodeResult.floor === null) {
                    geocodeResult.floor = name;
                } else if (type === 'room' && geocodeResult.room === null) {
                    geocodeResult.room = name;
                }
            });
        });
        var result = {
            Street1: null,
            Street2: null,
            City: null,
            State: null,
            Zip: null,
            Country: null,
            Latitude: null,
            Longitude: null,
            FormattedAddress: null
        }
        var street1 = "";
        if (geocodeResult.street_number !== null) {
            street1 += geocodeResult.street_number.trim() + " ";
        }
        if (geocodeResult.street_address !== null) {
            street1 += geocodeResult.street_address.trim();
        } else if (geocodeResult.route !== null) {
            street1 += geocodeResult.route.trim();
        } else if (geocodeResult.intersection !== null) {
            street1 += geocodeResult.intersection.trim();
        } else {
            street1 = geocodeResult.trim();
        }
        result.Street1 = street1 === "" ? null : street1;

        var street2 = "";
        if (geocodeResult.floor !== null) {
            street2 = geocodeResult.floor + " ";
        }
        if (geocodeResult.room !== null) {
            street2 = geocodeResult.room;
        }
        street2 = street2.trim();
        result.Street2 = street2 === "" ? null : street2;

        result.City = geocodeResult.locality === null ? null : geocodeResult.locality.trim();

        var state = "";
        if (geocodeResult.administrative_area_level_1 !== null) {
            state += geocodeResult.administrative_area_level_1.trim();
        } else if (geocodeResult.administrative_area_level_2 !== null) {
            state += geocodeResult.administrative_area_level_2.trim();
        } else if (geocodeResult.administrative_area_level_3 !== null) {
            state += geocodeResult.administrative_area_level_3.trim();
        } else if (geocodeResult.administrative_area_level_4 !== null) {
            state += geocodeResult.administrative_area_level_4.trim();
        } else if (geocodeResult.administrative_area_level_5 !== null) {
            state += geocodeResult.administrative_area_level_5.trim();
        } else {
            state = null;
        }
        result.State = state;

        result.Zip = geocodeResult.postal_code === null ? null : geocodeResult.postal_code.trim();

        result.Country = geocodeResult.country === null ? null : geocodeResult.country.trim();

        if (r.geometry.location.lat !== null) {
            result.Latitude = r.geometry.location.lat;
        }
        if (r.geometry.location.lng !== null) {
            result.Longitude = r.geometry.location.lng;
        }
        if (r.geometry.location.lng !== null) {
            result.FormattedAddress = r.formatted_address;
        }
        return result;
    }


})(jQuery);


