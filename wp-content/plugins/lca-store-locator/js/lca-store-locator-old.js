/* global google */

if (typeof google !== 'undefined') {
    google.maps.event.addDomListener(window, 'load', initialize);
    var geocoder = new google.maps.Geocoder();
    var service = new google.maps.DistanceMatrixService();
    var map;
    var infoWindows = new Array();
    var markers = new Array();
}
function initialize() {

    var mapProp = {
        center: new google.maps.LatLng(23.706483, -96.469633),
        zoom: 3,
        mapTypeId: google.maps.MapTypeId.ROADMAP
    };
    var mapElement = document.getElementById("lca_map");
    if (mapElement !== null) {
        map = new google.maps.Map(document.getElementById("lca_map"), mapProp);
    }
}

(function ($) {
    var data = new Result(null);

    getGeocode(data);
    function getGeocode(data) {
        var docname = getQueryString('docname');
        if (typeof docname !== 'undefined' && docname.trim() !== "") {
            geocoder.geocode({'address': docname}, function (results, status) {
                if (status === google.maps.GeocoderStatus.OK) {
                    data.latitude = results[0].geometry.location.lat();
                    data.longitude = results[0].geometry.location.lng();
                    data.latlng = new google.maps.LatLng(parseFloat(data.latitude), parseFloat(data.longitude))
                    data.foundUserLoaction = true;
                    data.geocode = results;
                    if (results[0].types[0] == "administrative_area_level_1") {
                        data.isStateSearch = true;
                    }
                    if (results[0].types[0] == "locality") {
                        data.isCitySearch = true;
                    }
                    getLocations(data);
                } else {
                    getLocations(data);
                }
            });
        } else {
            getLocations(data, false);
        }
    }

    function getLocations(data, loadLocations) {
        if (typeof (loadLocations) !== "boolean") {
            loadLocations = true;
        }
        if (loadLocations) {
            var locations = new Array();
            var $storeLocator = $(".store-locator");
            var typesArray = $storeLocator.data("types").split(",");
            var types = new Array();
            for (var i = 0; i < typesArray.length; i++) {
                var type = typesArray[i].trim().toLowerCase();
                type = type.replace(/ /g, "_");
                types.push(type);
            }
            var typesString = types.join('+');
            var searchUrl = window.location.origin + "/wp-admin/admin-ajax.php?action=GetLocations";
            if (data.foundUserLoaction) {
                searchUrl += "&lat=" + data.latitude + "&lng=" + data.longitude;
            }
            if (typesString != "") {
                searchUrl += "&types=" + typesString;
            }
            $.get(searchUrl).done(function (d) {


                var json;
                try {
                    json = $.parseJSON(d);

                } catch (e) {
                    console.log("Error with Parsing Data");
                    $storeLocator.removeClass("not-loaded");
                    return null;
                }

                if ($(json).length == 0) {
                    console.log("no data returned");
                    $storeLocator.removeClass("not-loaded");
                    return null;
                }
                if (typeof json.message !== 'undefined') {
                    console.log(json.message);
                    $storeLocator.removeClass("not-loaded");
                    return null;
                }

                $(json).each(function () {
                    var location = new Location(this.sl_address, this.sl_address2, this.sl_city, this.sl_description, this.sl_distance, this.sl_email, this.sl_facebook_phone_number, this.sl_fax, this.sl_hours, this.sl_id, this.sl_image, this.sl_landing_page, this.sl_latitude, this.sl_longitude, this.sl_phone, this.zip_state, this.sl_store, this.sl_tags, this.sl_type, this.sl_url, this.sl_zip);
                    locations.push(location);
                });

                data.locations = locations;
                if (data.foundUserLoaction) {
                    var geocodeResult = data.geocode[0];
                    var filterLocations = new Array();
                    if (data.isStateSearch) {
                        var stateName = geocodeResult.address_components[0].short_name;
                        $(data.locations).each(function () {
                            var loc = this;
                            if (loc.state == stateName) {
                                loc.distance = null;
                                filterLocations.push(loc);
                            }

                        });
                        if (filterLocations.length == 0) {
                            var closestLocation = data.locations[0];
                            closestLocation.distance = null;
                            filterLocations.push(closestLocation);
                        }
                    } else {
                        if (typeof(data.locations) !== 'undefined') {
                            var topLocations = data.locations.slice(0, 25);
                            data.locations = topLocations;
                        }
                        $(data.locations).each(function () {
                            var loc = this;
                            if (data.isCitySearch) {
                                loc.distance = null;
                            }
                            filterLocations.push(loc);
                            //window.filterLocations1 = filterLocations;
                            //	console.log(filterLocations.length);
                            if (filterLocations.length === 0) {
                                var closestLocation = data.locations[0];
                                //closestLocation.distance = null;
                                filterLocations.push(closestLocation);
                            }
                        });
                    }
                    data.locations = filterLocations;
                }
                var bounds = new google.maps.LatLngBounds();
                if (data.latlng != null) {
                    bounds.extend(data.latlng);
                }
                var $list = $("#lca_list").first().find(".locations");
                var addDirections = data.foundUserLoaction && !data.isCitySearch && !data.isStateSearch;
                var geoResults = data.geocode;

                $(data.locations).each(function () {
                    if (loadLocations) {
                        var location = buildSideBarHtml(this, addDirections, geoResults);
                        $list.append(location);
                        buildMarkerHtml(this);
                    }
                    bounds.extend(this.latlng);
                });
                if (loadLocations) {
                    $list.find(".location").each(function () {
                        $(this).on("click", function (e) {
                            if (!$(e.target).closest('a').length) {
                                var id = $(this).data("id");
                                activateLocation(id);
                            }
                        })
                    });
                }
                if (map == null) {
                    initializeJquery();
                }
                map.fitBounds(bounds);
                map.setCenter(bounds.getCenter());
                google.maps.event.addListener(map, "click", function (event) {
                    for (var i = 0; i < infoWindows.length; i++) {
                        markers[i].setIcon("/wp-content/plugins/lca-store-locator/img/default-marker.png");
                        infoWindows[i].close();
                    }
                });
                $storeLocator.removeClass("not-loaded");
            });
        }
    }


    function buildSideBarHtml(loc, addDirections, geoResults) {
        var location = "<div class='location.js' data-id='" + loc.id + "'>";
        var name = loc.name;
        location += "<div class='name'>" + name + "</div>";
        if (addDirections) {

            var start = geoResults[0].formatted_address;
            var end = "@" + loc.latitude + "," + loc.longitude;
            location += "<div class='distance'>"
                + loc.distance + " miles away "
                + "<a href='http://maps.google.com/maps?saddr=" + start + "?&daddr=" + end + "' target='blank'>Get Directions</a>"
                + "</div>";
        }
        if (typeof loc.address != 'undefined' && loc.address != null && loc.address != "") {
            var address = loc.address;
            if (typeof loc.street != 'undefined' && loc.street != null && loc.street != "") {
                address = loc.street;
            }
            if (typeof loc.address2 != 'undefined' && loc.address2 != null && loc.address2 != "") {
                address += ", " + loc.address2;
            }
            if (typeof loc.city != 'undefined' && loc.city != null && loc.city != "") {
                address += ", " + loc.city;
            }
            if (typeof loc.state != 'undefined' && loc.state != null && loc.state != "") {
                address += ", " + loc.state;
            }

            if (typeof loc.zip != 'undefined' && loc.zip != null && loc.zip != "") {
                address += " " + loc.zip;
            }
            location += "<div class='address'>" + address + "</div>";
        }
        if (typeof loc.phone != 'undefined' && loc.phone != null && loc.phone != "") {
            location += "<div class='phone'><a href='tel:" + loc.phone + "'>" + loc.phone + "</a></div>";
        }
        if (typeof loc.landing_page != 'undefined' && loc.landing_page != null && loc.landing_page != "") {
            location += "<a class='get-info' href='" + loc.landing_page + "' target='blank'>Get Info</a>";
        }

        if (typeof loc.book != 'undefined' && loc.book != null && typeof loc.id != 'undefined' && loc.id != null) {
            if (loc.book) {
                location += "<a class='get-info' href='/book-appointment/?reference_number=" + loc.id + "' target='blank'>Book Now</a>";
            }
        }
        location += "</div>";
        return location;
    }

    function buildMarkerHtml(loc) {
        var infoWindowContent = "<div class='location-info-window' data-id='" + loc.id + "'>";
        var name = loc.name;
        if (typeof loc.image != 'undefined' && loc.image != null) {
            infoWindowContent += "<img class='store-image' src='" + loc.image + "' alt='" + name + "' />";
        }

        infoWindowContent += "<div class='name'>" + name + "</div>";
        if (typeof loc.address != 'undefined' && loc.address != null) {
            var address = loc.address;
            if (typeof loc.street != 'undefined' && loc.street != null && loc.street != "") {
                address = loc.street;
            }
            if (typeof loc.address2 != 'undefined' && loc.address2 != null && loc.address2 != "") {
                address += ", " + loc.address2;
            }
            if (typeof loc.city != 'undefined' && loc.city != null && loc.city != "") {
                address += ", " + loc.city;
            }
            if (typeof loc.state != 'undefined' && loc.state != null && loc.state != "") {
                address += ", " + loc.state;
            }

            if (typeof loc.zip != 'undefined' && loc.zip != null && loc.zip != "") {
                address += " " + loc.zip;
            }
            infoWindowContent += "<div class='address'>" + address + "</div>";
        }

        if (typeof loc.phone != 'undefined' && loc.phone != null) {
            infoWindowContent += "<div class='phone'><strong>Phone: </strong>" + loc.phone + "</div>";
        }
        if (typeof loc.email != 'undefined' && loc.email != null) {
            infoWindowContent += "<div class='email'><strong>Email: </strong>" + loc.email + "</div>";
        }
        if (typeof loc.landing_page != 'undefined' && loc.landing_page != null && loc.landing_page != "") {
            infoWindowContent += "<a class='get-info' href='" + loc.landing_page + "' target='blank'>Get Info and Schedule Appointment </a>";
        }
        infoWindowContent += "</div>";
        var markerOpts = {
            map: map,
            position: loc.latlng,
            title: name,
            icon: '/wp-content/plugins/lca-store-locator/img/default-marker.png'
        };
        var marker = new google.maps.Marker(markerOpts);
        markers.push(marker);
        marker.addListener('click', function () {
            activateLocation(loc.id);
        });
        var maxWidth = $("#lca_map").width() / 2;
        var infowindow = new google.maps.InfoWindow({
            content: infoWindowContent,
            maxWidth: maxWidth
        });
        infoWindows.push(infowindow);
        google.maps.event.addListener(infowindow, 'closeclick', function () {
            for (var i = 0; i < infoWindows.length; i++) {
                if (infoWindows[i] == infowindow) {
                    markers[i].setIcon("/wp-content/plugins/lca-store-locator/img/default-marker.png");
                }
            }
            var $list = $("#lca_list").first();
            $list.find(".location").each(function () {
                $(this).removeClass("active");
            });
        });
    }

    function activateLocation(id) {

        id = parseInt(id);
        var $locations = $("#lca_list").first().find(".locations").first();
        $locations.find(".location").each(function () {
            var $loc = $(this);
            var locId = parseInt($loc.data("id"));
            if (locId === id) {
                if (!$loc.hasClass("active")) {
                    $loc.addClass("active");
                }

                var $list = $("#lca_list").first();
                var newPosition = $list.scrollTop() + $loc.position().top - $list.position().top;
                $list.animate({scrollTop: newPosition});
            } else {
                $loc.removeClass("active");
            }
        });
        for (var i = 0; i < infoWindows.length; i++) {
            var infoWindow = infoWindows[i];
            var marker = markers[i];
            var infoWindowId = parseInt($(infoWindow.content).data("id"));
            if (infoWindowId === id) {
                infoWindow.open(map, marker);
                marker.setIcon("/wp-content/plugins/lca-store-locator/img/active-marker.png");
            } else {
                infoWindow.close();
                marker.setIcon("/wp-content/plugins/lca-store-locator/img/default-marker.png");
            }
        }
    }

    function initializeJquery() {
        var mapProp = {
            center: new google.maps.LatLng(23.706483, -96.469633),
            zoom: 3,
            mapTypeId: google.maps.MapTypeId.ROADMAP
        };
        map = new google.maps.Map(document.getElementById("lca_map"), mapProp);
    }

    function Location(a, a2, c, de, di, e, fpn, f, h, id, im, lp, lat, lng, p, sta, sto, ta, ty, u, z) {

        this.address = a;
        this.street = a2;
        this.city = c;
        this.description = de;
        this.distance = (di != null) ? Math.round(parseFloat(di) * 10) / 10 : null;
        //this.lcaDistance = (di != null) ? Math.round(parseFloat(di) * 10) / 10 : null; //this is dumb - distance is always null but duplicating it to a new name worked??
        this.email = e;
        this.facebook_phone_number = fpn;
        this.fax = f;
        this.hours = h;
        this.id = id;
        this.image = im;
        this.landing_page = lp;
        this.latitude = lat;
        this.longitude = lng;
        this.latlng = new google.maps.LatLng(parseFloat(lat), parseFloat(lng))
        this.phone = p;
        this.state = sta;
        this.name = sto;
        this.tags = ta;
        this.type = ty;
        this.url = u;
        this.zip = z;
        this.book = checkForBookTag(this.tags);
    }

    function Result(locations) {
        this.locations = locations;
        this.showDistance = false;
        this.foundUserLoaction = false;
        this.isStateSearch = false;
        this.isCitySearch = false;
        this.latitude = 0;
        this.longitude = 0;
        this.latlng = null;
        this.geocode = null;
        this.top10locations = function () {
            if (typeof(this.locations) !== 'undefined') {
                return this.locations.slice(0, 10);
            }
            return null;
        };
        this.top25locations = function () {
            if (typeof(this.locations) !== 'undefined') {
                return this.locations.slice(0, 25);
            }
            return null;
        };
    }

    function getQueryString(name) {
        if (!this.queryStringParams)
            this.queryStringParams = parseParams();
        return this.queryStringParams[name];
    }

    function parseParams() {
        var params = {},
            e,
            a = /\+/g, // Regex for replacing addition symbol with a space
            r = /([^&=]+)=?([^&]*)/g,
            d = function (s) {
                return decodeURIComponent(s.replace(a, " "));
            },
            q = window.location.search.substring(1);
        while (e = r.exec(q))
            params[d(e[1])] = d(e[2]);
        return params;
    }

    function checkForBookTag(tags) {
        var canBook = false;
        if (tags !== null) {
            var tagsArray = tags.split(",");
            for (var i = 0; i < tagsArray.length; i++) {
                var tag = tagsArray[i].toString();
                if (tag.trim() == "book")
                    canBook = true;
            }
        }
        return canBook;
    }

    $(function () {	//show map and not static image
        var docname = getQueryString('docname');
        if (docname == null || docname === '') {
            $('.store-map-static').show();
            $('.store-map').hide();
        }
        else {
            $('.store-map-static').hide();
            $('.store-map').show();
        }
    });
})(jQuery);

