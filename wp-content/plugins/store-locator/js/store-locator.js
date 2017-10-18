$ = jQuery.noConflict();

var loadImg = $('#loadImg'); 
$('.search_results_page').append(loadImg);


var sl_map;
var sl_geocoder = new google.maps.Geocoder();
var sl_info_window;
var sl_marker_array = [];
var sl_marker_type;
var sl_geo_flag = 0;
if (!is_array(sl_categorization_array)) {
    var sl_categorization_array = []
}
var sl_marker_categorization_field = (is_array(sl_categorization_array) && sl_categorization_array.length > 0) ? sl_categorization_array[0] : 'type';
var sl_ccTLD = sl_google_map_domain.match(/\.([^\.]+)$/);
var sl_ccTLD_not_set = (typeof sl_ccTLD[1] == 'undefined' || sl_ccTLD[1] == 'null' || sl_ccTLD[1] == '');
if (sl_ccTLD_not_set || sl_ccTLD[1] == 'com') {
    var sl_ccTLD = (sl_ccTLD_not_set || sl_google_map_domain.indexOf('ditu') == -1) ? 'us' : 'cn'
} else {
    var sl_ccTLD = sl_ccTLD[1]
}
if (typeof sl_map_params != "undefined") {
    sl_map_params = sl_map_params.split("=").join("[]=")
}
if (!function_exists("sl_details_filter")) {
    var sl_details_filter = function (sl_details) {
        return sl_details
    }
}
if (window.location.host.indexOf('www.') != -1 && sl_base.indexOf('www.') == -1) {
    sl_base = sl_base.split('http://').join('http://www.')
} else if (window.location.host.indexOf('www.') == -1 && sl_base.indexOf('www.') != -1) {
    sl_base = sl_base.split('http://www.').join('http://')
}

function sl_load() {
    map_type_check();  
	 showLoadImg('show', 'loadImg');
    sl_geocoder = new google.maps.Geocoder();
    if ("undefined" != typeof document.getElementById("sl_map") && null != document.getElementById("sl_map")) {
        sl_map = new google.maps.Map(document.getElementById("sl_map"));
        sl_info_window = new google.maps.InfoWindow;
        window.sl_info_window = sl_info_window;
        if (function_exists("start_sl_load")) {
            start_sl_load()
        }
        if (sl_geolocate == '1') {
            showLoadImg('show', 'loadImg');
            try {
                if (typeof navigator.geolocation === 'undefined') {
                    ng = google.gears.factory.create('beta.geolocation')
                } else {
                    ng = navigator.geolocation
                }
            } catch (e) {
            }
            if (ng) {
                if (sl_geo_flag != 1) {
                    do_load_options()
                }
                ng.getCurrentPosition(sl_geo_success, sl_geo_error)
            } else {
                do_load_options()
            }
        } else {
            do_load_options()
        }
        if (function_exists("end_sl_load")) {
            google.maps.event.addDomListenerOnce(sl_map, 'tilesloaded', end_sl_load)
        }
    }   
	
}
google.maps.event.addDomListener(window, 'load', sl_load);

function sl_geo_success(point) {
    sl_geo_flag = 1;
    var the_coords = new google.maps.LatLng(point.coords.latitude, point.coords.longitude);
    sl_geocoder.geocode({
        'location': the_coords
    }, function (results) {
        aI = document.getElementById('addressInput');
        aI.value = results[0].formatted_address;

        searchLocationsNear(the_coords, aI.value)
    })
}

function sl_geo_error(err) {
    sl_geo_flag = 1;
    do_load_options()
}

function do_load_options() {
    if (sl_load_locations_default == '0') {
        sl_geocoder.geocode({
            'address': sl_google_map_country
        }, function (results, status) {
            var myOptions = {
                center: results[0].geometry.location,
                zoom: sl_zoom_level,
                mapTypeId: sl_map_type_v3,
                overviewMapControl: sl_map_overview_control,
                disableDefaultUI: false,
                mapTypeControlOptions: {
                    style: google.maps.MapTypeId.ROADMAP
                }
            };
            sl_map.setOptions(myOptions);
        })
    }
    if (sl_load_locations_default == "1") {  

        var allBounds = new google.maps.LatLngBounds();
        var searchUrl = sl_base + "/sl-xml.php";
        if (typeof sl_map_params != "undefined") {
            searchUrl += "?" + sl_map_params
        }   
		else
		{
		 	searchUrl += "?load_limit=1"; 
		}
        var docname = jQuery.getQueryString('docname');
        if (typeof docname == 'undefined' || docname.trim() == "") {
            retrieveData(searchUrl, function (data) {
                var xml = data.responseXML;
                var markerNodes = xml.documentElement.getElementsByTagName("marker");   

                for (var i = 0; i < markerNodes.length; i++) {
                    allBounds.extend(buildDetails(markerNodes[i])['point'])
                }
                var center = allBounds.getCenter();
                searchLocationsAll(center);   
				 showLoadImg('stop', 'loadImg');
            })
        }
        else{ 
		
           	 	searchLocations();
        }
    }
}

function searchLocations() {

    if (function_exists("start_searchLocations")) {
        start_searchLocations()
    }
    var address = document.getElementById('addressInput').value;
    if(address == null || address == ""){
        address = jQuery.getQueryString('docname');
    }
    sl_geocoder.geocode({
        'address': address,
        'region': sl_ccTLD
    }, function (results, status) {
        if (status != google.maps.GeocoderStatus.OK) {
            showLoadImg('stop', 'loadImg');
            if (sl_location_not_found_message.split(' ').join('') != "") {
                alert(sl_location_not_found_message);
            } else {
                //alert(address + ' Not Found');
            }
        } else {

            if (results[0].types[0] == "administrative_area_level_1") {
                var components = results[0].address_components;
                var administativeLevelNotFound = true;
                for (var a = 0; a < components.length; a++) {
                    if (administativeLevelNotFound) {
                        if (components[a].types[0] == "administrative_area_level_1") {
                            administativeLevelNotFound = false;
                            searchLocationsForState(results[0].geometry.location, components[a]);
                        }
                    }
                }
                if (administativeLevelNotFound) {
                    searchLocationsNear(results[0].geometry.location, address);
                }
            } else {
                searchLocationsNear(results[0].geometry.location, address);
                searchLocationsAroham(results[0].geometry.location, address);
            }
        }
    });
    if (function_exists("end_searchLocations")) {
        end_searchLocations()
    }
}

(function ($) {
    $.extend({
        getQueryString: function (name) {
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

            if (!this.queryStringParams)
                this.queryStringParams = parseParams();

            return this.queryStringParams[name];
        }
    });
})(jQuery);

function searchLocationsForState(center, component) {
    var homeAddress = component.short_name;
    var searchUrl = sl_base + '/sl-xml.php?mode=gen&load_limit=1&lat=' + center.lat() + '&lng=' + center.lng() + ' &sl=' + jQuery.getQueryString('sl');
    if (typeof sl_map_params != "undefined") {
        searchUrl += sl_map_params
    }
    retrieveData(searchUrl, function (data) {
        var xml = data.responseXML;

        var markerNodes = xml.documentElement.getElementsByTagName('marker');
        if (markerNodes.length == 0) {
            searchLocationsNear(center, homeAddress, parseInt(radius) + 50);
        } else {
            clearLocations();
            for (var i = 0; i < markerNodes.length; i++) {
                var marker = markerNodes[i];
                var markerAddress = marker.getAttribute("address");
                if (markerAddress.indexOf(homeAddress) == -1) {
                    markerNodes[i].remove();
                    i--;
                }
            }
            if (markerNodes.length > 0) {
                var bounds = new google.maps.LatLngBounds();
                var point = new google.maps.LatLng(center.lat(), center.lng());
                var markerOpts = {
                    map: sl_map,
                    position: point,
                    home: true,
                };
                var icon = {
                    url: sl_map_home_icon
                };
                bounds = bounds.extend(point);
                //var homeMarker = new google.maps.Marker(markerOpts);
               // determineShadow(icon, homeMarker);      
               // var html = '<div id="sl_info_bubble"><span class="your_location_label">Your Location:</span> <br/>' + homeAddress + '</div>';
                //bindInfoWindow(homeMarker, sl_map, sl_info_window, html);
                var sidebar = document.getElementById('map_sidebar');
                sidebar.innerHTML = '';
                if (markerNodes.length == 0) {
                    showLoadImg('stop', 'loadImg');
                    sidebar.innerHTML = '<div class="text_below_map">' + sl_no_results_found_message + '</div>';
                   // sl_marker_array.push(homeMarker);
                    sl_map.setCenter(point);
                    return
                }
                var lnt = markerNodes.length;
                for (var i = 0; i < markerNodes.length; i++) {
                    var sl_details = buildDetails(markerNodes[i]);
                    sl_marker_type = markerNodes[i].getAttribute(sl_marker_categorization_field);
                    if (sl_marker_type == "" || sl_marker_type == null) {
                        sl_marker_type = "sl_map_end_icon"
                    }
                    var icon = (typeof sl_icons != 'undefined' && typeof sl_icons[sl_marker_type] != 'undefined') ? sl_icons[sl_marker_type] : {
                        url: sl_map_end_icon,
                        name: 'Default'
                    };
                    var marker = createMarker(sl_details, sl_marker_type, icon);
                    var sidebarEntry = createSidebarEntry(marker, sl_details);
                    sidebarEntry.id = "sidebar_div_" + i;
                    sidebar.appendChild(sidebarEntry);
                    var markerPoint = sl_details['point'];
                    bounds = bounds.extend(markerPoint);

                }
               // sl_marker_array.push(homeMarker);
                sl_map.fitBounds(bounds);
                sl_map.setCenter(bounds.getCenter());
                google.maps.event.addListenerOnce(sl_map, 'idle', function () {
                    checkScreenSize();
                });

                if (lnt > 0) {
                    //document.getElementById('sidebar_div_0').click();
                } else {
//                    console.log(lnt);
                }
            } else {
                searchLocationsNear(center, component.long_name);
            }
        } 
		 showLoadImg('stop', 'loadImg');
    });
}

function searchLocationsAll(center) {
    var searchUrl = sl_base + '/sl-xml.php?mode=gen&load_limit=1&lat=' + center.lat() + '&lng=' + center.lng() + ' &sl=' + jQuery.getQueryString('sl');
    if (typeof sl_map_params != "undefined") {
        searchUrl += sl_map_params
    }
    retrieveData(searchUrl, function (data) {
        var xml = data.responseXML;

        var markerNodes = xml.documentElement.getElementsByTagName('marker');
        clearLocations();
        var bounds = new google.maps.LatLngBounds();
        var point = new google.maps.LatLng(center.lat(), center.lng());
        var markerOpts = {
            map: sl_map,
            position: point,
            home: true,
        };
        var icon = {
            url: sl_map_home_icon
        };
        bounds = bounds.extend(point);
       // var homeMarker = new google.maps.Marker(markerOpts);
       // determineShadow(icon, homeMarker);
       // var html = '<div id="sl_info_bubble"><span class="your_location_label">Your Location:</span> <br/>USA</div>';
        //bindInfoWindow(homeMarker, sl_map, sl_info_window, html);
        var sidebar = document.getElementById('map_sidebar');
        sidebar.innerHTML = '';   
	
        if (markerNodes.length == 0) {
            showLoadImg('stop', 'loadImg');
            sidebar.innerHTML = '<div class="text_below_map">' + sl_no_results_found_message + '</div>';
            //sl_marker_array.push(homeMarker);
            sl_map.setCenter(point);
            return
        }
        var lnt = markerNodes.length;
        for (var i = 0; i < markerNodes.length; i++) {
            var sl_details = buildDetails(markerNodes[i]);
            sl_marker_type = markerNodes[i].getAttribute(sl_marker_categorization_field);
            if (sl_marker_type == "" || sl_marker_type == null) {
                sl_marker_type = "sl_map_end_icon"
            }
            var icon = (typeof sl_icons != 'undefined' && typeof sl_icons[sl_marker_type] != 'undefined') ? sl_icons[sl_marker_type] : {
                url: sl_map_end_icon,
                name: 'Default'
            };
            var marker = createMarker(sl_details, sl_marker_type, icon);
            var sidebarEntry = createSidebarEntry(marker, sl_details, "none");
            sidebarEntry.id = "sidebar_div_" + i;
            sidebar.appendChild(sidebarEntry);
            var markerPoint = sl_details['point'];
            bounds = bounds.extend(markerPoint);
        }
       // sl_marker_array.push(homeMarker);
        sl_map.fitBounds(bounds);
        sl_map.setCenter(bounds.getCenter());
        google.maps.event.addListenerOnce(sl_map, 'idle', function () {
            checkScreenSize();
        });
			
	/*		
        if (lnt > 0) {
            document.getElementById('sidebar_div_0').click();
        } else {

        }     */        
		 showLoadImg('stop', 'loadImg');
    });
}

function searchLocationsNear(center, homeAddress, radius) {
    if (function_exists("start_searchLocationsNear")) {
        start_searchLocationsNear()
    }                        
									 
								 
    if (typeof radius == 'undefined') {
        var radius = 1;
    } else {
        //console.log("search radius: " + radius + "miles");
    }
    var searchUrl = sl_base + '/sl-xml.php?mode=gen&lat=' + center.lat() + '&lng=' + center.lng() + '&radius=' + radius + '&address=' + homeAddress + ' &sl=' + jQuery.getQueryString('sl');
    if (typeof sl_map_params != "undefined") {
        searchUrl += sl_map_params
    } 

    retrieveData(searchUrl, function (data) {
        var xml = data.responseXML;

        var markerNodes = xml.documentElement.getElementsByTagName('marker');
        if (markerNodes.length == 0) {

            searchLocationsNear(center, homeAddress, parseInt(radius) + 50);
        } else {
            clearLocations();
            var bounds = new google.maps.LatLngBounds();
            var point = new google.maps.LatLng(center.lat(), center.lng());

            var markerOpts = {
                map: sl_map,
                position: point,
                home: true,
            };
            var icon = {
                url: sl_map_home_icon
            };
            bounds = bounds.extend(point);
           // var homeMarker = new google.maps.Marker(markerOpts);
          //  determineShadow(icon, homeMarker);
           // var html = '<div id="sl_info_bubble"><span class="your_location_label">Your Location:</span> <br/>' + homeAddress + '</div>';
          //  bindInfoWindow(homeMarker, sl_map, sl_info_window, html);
            var sidebar = document.getElementById('map_sidebar');
            sidebar.innerHTML = '';             
		
            if (markerNodes.length == 0) {
                showLoadImg('stop', 'loadImg');
                sidebar.innerHTML = '<div class="text_below_map">' + sl_no_results_found_message + '</div>';
               // sl_marker_array.push(homeMarker);
                sl_map.setCenter(point);
                return
            }
            var lnt = markerNodes.length;
            for (var i = 0; i < markerNodes.length; i++) {
                var sl_details = buildDetails(markerNodes[i]);
                sl_marker_type = markerNodes[i].getAttribute(sl_marker_categorization_field);
                if (sl_marker_type == "" || sl_marker_type == null) {
                    sl_marker_type = "sl_map_end_icon"
                }
                var icon = (typeof sl_icons != 'undefined' && typeof sl_icons[sl_marker_type] != 'undefined') ? sl_icons[sl_marker_type] : {
                    url: sl_map_end_icon,
                    name: 'Default'
                };
                var marker = createMarker(sl_details, sl_marker_type, icon);
                var sidebarEntry = createSidebarEntry(marker, sl_details);
                sidebarEntry.id = "sidebar_div_" + i;
                sidebar.appendChild(sidebarEntry);
                var markerPoint = sl_details['point'];
                bounds = bounds.extend(markerPoint);
            }
            //sl_marker_array.push(homeMarker);
            sl_map.fitBounds(bounds);
            sl_map.setCenter(bounds.getCenter());
            google.maps.event.addListenerOnce(sl_map, 'idle', function () {
                checkScreenSize();
            });

            if (lnt === 1) {
                //document.getElementById('sidebar_div_0').click();
            } else {

            }       
			 showLoadImg('stop', 'loadImg');
        }

    });

    if (function_exists("end_searchLocationsNear")) {
        end_searchLocationsNear()
    }
}

function showVisibleMarkers() {
    var bounds = sl_map.getBounds(),
            count = 0;

    for (var i = 0; i < markers.length; i++) {
        var marker = markers[i],
                infoPanel = $('.info-' + (i + 1)); // array indexes start at zero, but not our class names :)

        if (bounds.contains(marker.getPosition()) === true) {
            infoPanel.show();
            count++;
        } else {
            infoPanel.hide();
        }
    }

    $('#infos h2 span').html(count);
}
function checkScreenSize() {
    var mapHeight = document.getElementById("sl_map").clientHeight;
    var screenSize = Math.max(document.documentElement.clientHeight, window.innerHeight || 0);
    if ( mapHeight > ( screenSize * (3/4))) {
        var newHeight = screenSize * (3/4);
        var map = document.getElementById("sl_map");
        map.style.height = newHeight.toString() + "px";
    }
}

function searchLocationsAroham(center, homeAddress) {
    var radiusElement = document.getElementById('radiusSelect');
    if(typeof radiusElement == 'undefined' || radiusElement == null) return;
    var radius = radiusElement.value;
    var searchUrl = sl_base + '/sl-xml.php?mode=gen&lat=' + center.lat() + '&lng=' + center.lng() + '&radius=' + radius + '&address=' + homeAddress + '&listc=1';
    jQuery.ajax({
        type: 'POST',
        url: searchUrl,
        success: function (response) {
            jQuery('.doc_searchxx').append(response);
        }
    });
}


function createMarker(sl_details, type, icon) {
    var marker = new google.maps.Marker({
        map: sl_map,
        position: sl_details['point'],
        icon: icon.url,
        locID: sl_details['reference_number']
    });
    determineShadow(icon, marker);
    if (function_exists("start_createMarker")) {
        start_createMarker()
    }
    html = buildMarkerHTML(sl_details);
    bindInfoWindow(marker, sl_map, sl_info_window, html);
    sl_marker_array.push(marker);
    if (function_exists("end_createMarker")) {
        end_createMarker()
    }
    return marker
}
var resultsDisplayed = 0;
var bgcol = "white";

function createSidebarEntry(marker, sl_details, display) {
    if (!isset(display)) {
        display = "block";
    }
    if (function_exists("start_createSidebarEntry")) {
        start_createSidebarEntry()
    }
    if (document.getElementById('map_sidebar_td') != null) {
        document.getElementById('map_sidebar_td').style.display = 'block'
    }
    var div = document.createElement('div');
    var html = buildSidebarHTML(sl_details, display);
    div.innerHTML = html;
    div.className = 'results_entry1 row';
    div.setAttribute('name', 'results_entry');
    div.setAttribute('data-reference-number', sl_details['reference_number']);
    resultsDisplayed++;
    google.maps.event.addDomListener(div, 'click', function () {
        google.maps.event.trigger(marker, 'click')
        jQuery('#map_td').addClass('show_div');
    });
    if (function_exists("end_createSidebarEntry")) {
        end_createSidebarEntry()
    }
    return div;
}

function retrieveData(url, callback) {
    var request = window.ActiveXObject ? new ActiveXObject('Microsoft.XMLHTTP') : new XMLHttpRequest;
    request.onreadystatechange = function () {
        if (request.readyState == 4) {
            request.onreadystatechange = doNothing;
            callback(request, request.status);
            if (function_exists("end_retrieveData")) {
                end_retrieveData()
            }
        }
    };
    request.open('GET', url, true);
    request.send(null)
}

function doNothing() {
}

function bindInfoWindow(marker, map, infoWindow, html) {
    window.ustLocator.binding(sl_marker_array, marker, infoWindow, map);   
	google.maps.event.addListener(marker, 'click', function() {
        infoWindow.close();
        infoWindow.setContent(html);
       	infoWindow.open(map, marker)   
		//console.log('called');
    });
    google.maps.event.addListener(marker, 'visible_changed', function() {
        infoWindow.close()
    })
}

function clearLocations() {
    sl_info_window.close();
    for (var i = 0; i < sl_marker_array.length; i++) {
        sl_marker_array[i].setMap(null)
    }
    sl_marker_array.length = 0
}

function determineShadow(icon, marker) {
    if (icon.url.indexOf('flag') != '-1') {
        marker.setShadow(sl_base + "/icons/flag_shadow_v3.png")
    } else if (icon.url.indexOf('arrow') != '-1') {
        marker.setShadow(sl_base + "/icons/arrow_shadow_v3.png")
    } else if (icon.url.indexOf('bubble') != '-1') {
        marker.setShadow(sl_base + "/icons/bubble_shadow_v3.png")
    } else if (icon.url.indexOf('marker') != '-1') {
        marker.setShadow(sl_base + "/icons/marker_shadow_v3.png")
    } else if (icon.url.indexOf('sign') != '-1') {
        marker.setShadow(sl_base + "/icons/sign_shadow_v3.png")
    } else if (icon.url.indexOf('droplet') != '-1') {
        marker.setShadow(sl_base + "/icons/droplet_shadow_v3.png")
    } else {
        marker.setShadow(sl_base + "/icons/blank.png")
    }
}

function map_type_check() {
    if (sl_map_type == 'G_NORMAL_MAP') {
        sl_map_type_v3 = google.maps.MapTypeId.ROADMAP
    } else if (sl_map_type == 'G_SATELLITE_MAP') {
        sl_map_type_v3 = google.maps.MapTypeId.SATELLITE
    } else if (sl_map_type == 'G_HYBRID_MAP') {
        sl_map_type_v3 = google.maps.MapTypeId.HYBRID
    } else if (sl_map_type == 'G_PHYSICAL_MAP') {
        sl_map_type_v3 = google.maps.MapTypeId.TERRAIN
    } else if (sl_map_type != google.maps.MapTypeId.ROADMAP && sl_map_type != google.maps.MapTypeId.SATELLITE && sl_map_type != google.maps.MapTypeId.HYBRID && sl_map_type != google.maps.MapTypeId.TERRAIN) {
        sl_map_type_v3 = google.maps.MapTypeId.ROADMAP
    } else {
        sl_map_type_v3 = sl_map_type
    }
}

function function_exists(func) {
    return eval("typeof window." + func + " === 'function'")
}

function is_array(arr) {
    return eval(typeof arr === 'object' && arr instanceof Array)
}

function empty(value) {
    return eval(typeof value === 'undefined')
}

function isset(value) {
    return eval(typeof value !== 'undefined')
}

function mergeArray(array1, array2) {
    for (item in array1) {
        array2[item] = array1[item]
    }
    return array2
}



function determineDirectionsLink(sl_details, html) {
    var homeAddress = sl_details['homeAddress'];
    if (homeAddress.split(" ").join("") != "") {
        html = html.split("sl_details['sl_directions_link']").join('\'<a href="http://' + sl_google_map_domain + '/maps?saddr=' + encodeURIComponent(homeAddress) + '&daddr=' + encodeURIComponent(sl_details['fullAddress']) + '" target="_blank" class="storelocatorlink">' + sl_directions_label + '</a>\'')
    } else {
        html = html.split("sl_details['sl_directions_link']").join('\'<a href="http://' + sl_google_map_domain + '/maps?q=' + encodeURIComponent(sl_details['fullAddress']) + '" target="_blank" class="storelocatorlink">Map</a>\'')
    }
    return html
}
var count123 = 0;
if (!function_exists("buildSidebarHTML")) {
    function buildSidebarHTML(sl_details, display) {
        if (!isset(display)) {
            display = "block";
        }
        var locationId = sl_details['reference_number'];
        var bookNowButton = '<div class="appointment_button appt_results_page" style="margin-top: 34px; margin-right: 13%; margin-bottom:0px;">\n\
                                <a style="background-color: #faf705; padding: 12px 100px; border-radius: 40px; color: #4a4c4b; text-decoration: none;" href="/book-appointment?reference_number=' + locationId + '">\n\
                                    <strong>BOOK NOW</strong>\n\
                                </a>\n\
                            </div>';
        if (sl_details['sl_tags'].indexOf("book") != 0) {
            bookNowButton = '';
        }
        var street = sl_details['sl_address'];
        if (street.split(' ').join('') != "") {
            street += '<br/>'
        } else {
            street = ""
        }
        if (sl_details['sl_address2'].split(' ').join('') != "") {
            street += sl_details['sl_address2'] + '<br/>'
        }
        var city = sl_details['sl_city'];
        if (city.split(' ').join('') != "") {
            city += ', '
        } else {
            city = ""
        }
        var phone = sl_details['sl_phone'];
        var phone_link = "<a href='tel:" + phone + "' target='_blank' class='more_info_link'>"+ phone +"</a>"
        var state_zip = sl_details['sl_state'] + ' ' + sl_details['sl_zip'];
        if (sl_details['fullAddress'].split(",").join("").split(" ").join("") == "") {
            sl_details['fullAddress'] = sl_details['sl_latitude'] + "," + sl_details['sl_longitude']
        }
        var homeAddress = sl_details['homeAddress'];


        var name = sl_details['sl_store'];
        var distance = sl_details['sl_distance'];
        var url = sl_details['sl_url'];
        var sl_landing_page = sl_details['sl_landing_page'];

        if (url.indexOf("http://") == -1) {
            url = "http://" + url
        }
        if (sl_landing_page.indexOf("http://") == -1) {
            sl_landing_page = "http://" + sl_landing_page
        }
        if (sl_landing_page != 'http://') {
            more_info_link = "<a href='" + sl_landing_page + "' target='_blank' class='more_info_link'>Get Info</a>"
        } else {
            more_info_link = "";
        }

        if (url.indexOf("http://") != -1 && url.indexOf(".") != -1) {
            link = "&nbsp;|&nbsp;<a href='" + url + "' target='_blank' class='storelocatorlink'><nobr>" + sl_website_label + "</nobr></a>"
        } else {
            url = "";
            link = ""

        }
        sl_details['sl_distance_unit'] = sl_distance_unit;
        sl_details['sl_google_map_domain'] = sl_google_map_domain;
        if (function_exists("sl_results_template") && sl_results_template(sl_details)) {
            var html = decode64(sl_results_template(sl_details));
            html = determineDirectionsLink(sl_details, html);
            html = eval("'" + html + "'")
        } else {
            var distance_display = (distance.toFixed(1) != '' && distance.toFixed(1) != 'null' && distance.toFixed(1) != 'NaN') ? '<br>' + distance.toFixed(1) + ' ' + sl_distance_unit + ' away' : "";

//            var html = '<center>\n\
//                            <table class="searchResultsTable">\n\
//                                <tr>\n\
//                                    <td class="results_row_left_column">\n\
//                                        <h3 class="location_name">' + name + '</h3>\n\
//                                        <div class="address_part">\n\
//                                            <span> ' + street + city + state_zip + '</span>\n\
//                                            <span><strong>' + phone + '</strong></span><span>' + more_info_link + '</span>\n\
//                                        </div>\n\
//                                    </td>\n\
//                                    <td class="results_row_center_column">\n\
//                                        <div class="map_right_part" style="display: ' + display + ';">\n\
//                                            <span class="distance_value">' + distance_display + '</span>\n\
//                                            <span>\n\
//                                                <a href="#"  id="store_map_link" class="results_entry map_link">\n\
//                                                    map\n\
//                                                </a>\n\
//                                            </span>\n\
//                                            <span>\n\
//                                                <a href="http://' + sl_google_map_domain + '/maps?saddr=' + encodeURIComponent(homeAddress) + '&daddr=' + encodeURIComponent(sl_details['fullAddress']) + '" target="_blank" class="storelocatorlink map_link_direction">\n\
//                                                    directions\n\
//                                                </a>\n\
//                                            </span>\n\
//                                        </div>\n\
//                                    </td>\n\
//                                </tr>\n\
//                            </table>' + bookNowButton + '</center>'
            var html = '<div class="col-xs-12">\n\
                            <h3 class="location_name">' + name + '</h3>\n\
                        </div>\n\
                        <div class="col-xs-12" style="display: ' + display + ';">\n\
                            <h4 class="distance_value">' + distance_display + '\
                                <a href="http://' + sl_google_map_domain + '/maps?saddr=' + encodeURIComponent(homeAddress) + '&daddr=' + encodeURIComponent(sl_details['fullAddress']) + '" target="_blank" class="storelocatorlink map_link_direction">\n\
                                    directions\n\
                                </a>\n\
                            </h4>\n\
                        </div>\n\
                        <div class="col-xs-12">\n\
                            <span> ' + street + city + state_zip + '</span>\n\
                        </div>\n\
                        <div class="col-xs-12">\n\
                            <a class="phone_number" href="tel:+' + phone + '">' + phone + '</a>\n\
                        </div>\n\
                        <div class="more_info_link_container">' + more_info_link + '\n\
                        </div>\n\
                        <div class="book_now_container">\n\
                            <span>' + bookNowButton + '</span>\n\
                        </div>\n\
                        <div class="clearfix"></div>';
                                    


        }
        if (distance_display != '') {

            return html
        } else {
            count123++;
            if (count123 == 1) {
                return '<div class="text_below_map"> </div>';
                // return '';
            } else {
                return '';
            }


        }

    }
}
if (function_exists("buildMarkerHTML") != true) {
    function buildMarkerHTML(sl_details) {
        var street = sl_details['sl_address'];
        if (street.split(' ').join('') != "") {
            street += '<br/>'
        } else {
            street = ""
        }
        if (sl_details['sl_address2'].split(' ').join('') != "") {
            street += sl_details['sl_address2'] + '<br/>'
        }
        var city = sl_details['sl_city'];
        if (city.split(' ').join('') != "") {
            city += ', '
        } else {
            city = ""
        }
        var state_zip = sl_details['sl_state'] + ' ' + sl_details['sl_zip'];
        if (sl_details['fullAddress'].split(",").join("").split(" ").join("") == "") {
            sl_details['fullAddress'] = sl_details['sl_latitude'] + "," + sl_details['sl_longitude']
        }
        var homeAddress = sl_details['homeAddress'];
        var name = sl_details['sl_store'];
        var distance = sl_details['sl_distance'];
        var url = sl_details['sl_url'];
        var image = sl_details['sl_image'];
        var description = ''; //sl_details['sl_description'];
        var hours = sl_details['sl_hours'];
        var phone = sl_details['sl_phone'];
        var fax = sl_details['sl_fax'];
        var email = sl_details['sl_email'];
        var sl_landing_page = sl_details['sl_landing_page'];
        var more_html = "";
        if (url.indexOf("http://") == -1) {
            url = "http://" + url
        }
        if (sl_landing_page.indexOf("http://") == -1) {
            sl_landing_page = "http://" + sl_landing_page
        }
        /*if (url.indexOf("http://") != -1 && url.indexOf(".") != -1) {
         more_html += "| <a href='" + url + "' target='_blank' class='storelocatorlink'><nobr>" + sl_website_label + "</nobr></a>"
         } else {
         url = ""
         }*/
        if (image.indexOf(".") != -1) {
            image = "<div class='mapImage'><img src='" + image + "' class='sl_info_bubble_main_image'></div>"
        } else {
            image = ""
        }
        if (description != "") {
            more_html += "<div class='mapIDesc'>" + description + "</div>"
        } else {
            description = ""
        }
        if (hours != "") {
            more_html += "<div class='mapHours'><span class='location_detail_label'>Hours:</span> " + hours + "</div>"
        } else {
            hours = ""
        }
        if (phone != "") {
            more_html += "<div class='mapPhone'><span class='location_detail_label'>Phone:</span> " + phone + "</div>"
        } else {
            phone = ""
        }
        if (fax != "") {
            more_html += "<div class='mapDetails'><span class='location_detail_label'>Fax:</span> " + fax + "</div>"
        } else {
            fax = ""
        }
        if (email != "") {
            more_html += "<div class='mapEmail'><span class='location_detail_label'>Email:</span><a href='mailto:" + email + "'>" + email + "</a> </div>"
        } else {
            email = ""
        }
        sl_details['sl_more_html'] = more_html;
        sl_details['sl_distance_unit'] = sl_distance_unit;
        sl_details['sl_google_map_domain'] = sl_google_map_domain;
        if (function_exists("sl_bubble_template") && sl_bubble_template(sl_details)) {
            sl_details['sl_distance'] = distance.toFixed(1);
            var html = decode64(sl_bubble_template(sl_details));
            html = determineDirectionsLink(sl_details, html);
            html = eval("'" + html + "'")
        } else {
            var html = '<div id="sl_info_bubble">' + image + '<div class="mapName"><strong>' + name + '</strong></div><div class="mapAddress">' + street + city + state_zip + '</div> ' + more_html + ' <br/> ';
            if (sl_landing_page != 'http://') {
                html += '<a href="' + sl_landing_page + '" target="_blank" class="storelocatorlink">Get Info and Schedule Appointment</a> <br/>';
            }
            html + '<a href="http://' + sl_google_map_domain + '/maps?saddr=' + encodeURIComponent(homeAddress) + '&daddr=' + encodeURIComponent(sl_details['fullAddress']) + '" target="_blank" class="storelocatorlink">' + sl_directions_label + '</a></div>'
        }
        return html
    }
}
if (function_exists("buildDetails") != true) {
    function buildDetails(markerNode) {
        //console.log(markerNode);
        var details_array = {
            'reference_number': markerNode.getAttribute('reference_number'),
            'fullAddress': markerNode.getAttribute('address'),
            'sl_address': markerNode.getAttribute('street'),
            'sl_address2': markerNode.getAttribute('street2'),
            'sl_city': markerNode.getAttribute('city'),
            'sl_state': markerNode.getAttribute('state'),
            'sl_zip': markerNode.getAttribute('zip'),
            'sl_latitude': markerNode.getAttribute('lat'),
            'sl_longitude': markerNode.getAttribute('lng'),
            'sl_store': markerNode.getAttribute('name'),
            'sl_description': markerNode.getAttribute('description'),
            'sl_url': markerNode.getAttribute('url'),
            'sl_hours': markerNode.getAttribute('hours'),
            'sl_phone': markerNode.getAttribute('phone'),
            'sl_fax': markerNode.getAttribute('fax'),
            'sl_email': markerNode.getAttribute('email'),
            'sl_image': markerNode.getAttribute('image'),
            'sl_landing_page': markerNode.getAttribute('sl_landing_page'),
            'sl_tags': markerNode.getAttribute('tags'),
            'sl_distance': parseFloat(markerNode.getAttribute('distance')),
            'homeAddress': document.getElementById('addressInput').value,
            'point': new google.maps.LatLng(parseFloat(markerNode.getAttribute('lat')), parseFloat(markerNode.getAttribute('lng')))
        };
        if (typeof sl_xml_properties_array != "undefined") {
            if (is_array(sl_xml_properties_array)) {
                for (key in sl_xml_properties_array) {
                    details_array[sl_xml_properties_array[key]] = markerNode.getAttribute(sl_xml_properties_array[key])
                }
            }
        }
        details_array = sl_details_filter(details_array);
        return details_array
    }
}



