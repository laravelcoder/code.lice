(function ($) {


    $(document).ready(function () {
        var options = {
            valueNames: [
                'name',
                {data: ['id']},
                {data: ['latitude']},
                {data: ['longitude']},
                {data: ['email']},
                {data: ['image']},
                'distance',
                'phone',
                'address',
                {attr: 'href', name: 'landing'}
            ]
        };
        var locationsList = new List('lca_list', options);
        var locations = new Array();
        var bounds = new google.maps.LatLngBounds();
        var markers = new Array();
        $(locationsList.items).each(function () {
            var loc = new location(this._values);
            if (loc.isLocated()) {
                bounds.extend(loc.getCoordinates());
                markers.push(loc.getCoordinates());
            }
            locations.push(loc);
        });


        var map = new GMaps({
            el: '#lca_map',
            lat: bounds.getCenter().lat(),
            lng: bounds.getCenter().lng()
        });
        $(locations).each(function () {
            if (this.isLocated()) {
                map.addMarker({
                    lat: this.latitude,
                    lng: this.longitude,
                    title: this.name,
                    icon: "/wp-content/plugins/lca-store-locator/img/default-marker.png",
                    infoWindow: {
                        content: this.buildMarker()
                    }
                });
            }
        });
        map.fitLatLngBounds(markers);

    });

    var location = function (item) {
        this.distance = isNaN(parseFloat(item.distance)) ? null : parseFloat(item.distance);
        this.email = item.email !== "" ? item.email : null;
        this.id = isNaN(parseInt(item.id)) ? null : parseInt(item.id);
        this.landing = item.landing !== "" ? item.landing : null;
        this.latitude = isNaN(parseFloat(item.latitude)) ? null : parseFloat(item.latitude);
        this.longitude = isNaN(parseFloat(item.longitude)) ? null : parseFloat(item.longitude);
        this.name = item.name !== "" ? item.name : null;
        this.phone = item.phone !== "" ? item.phone : null;
        this.image = item.image !== "" ? item.image : null;
        this.address = item.address !== "" ? item.address : null;

        this.isLocated = function () {
            return !(this.latitude === null || this.longitude === null);
        };
        this.getCoordinates = function () {
            if (this.latitude === null || this.longitude === null) return null;
            return myLatLng = new google.maps.LatLng({lat: this.latitude, lng: this.longitude});
        };
        this.buildMarker = function () {
            var html = "<div class='location-info-window' data-id='" + this.id + "'>";
            if (this.image != null) html += "<img class='store-image' src='" + this.image + "' alt='" + this.name + "' />";
            if (this.name != null) html += "<div class='name'>" + this.name + "</div>"
            if (this.address != null) html += "<div class='address'>" + this.address + "</div>";
            if (this.phone != null) html += "<div class='phone'><strong>Phone: </strong>" + this.phone + "</div>";
            if (this.email != null) html += "<div class='email'><strong>Email: </strong>" + this.email + "</div>";
            if (this.landing != null) html += "<a class='get-info' href='" + this.landing + "' target='blank'>Get Info and Schedule Appointment </a>";
            html += "</div>";
            return html;
        };
        this.getMarker = function () {
            if (this.latitude === null || this.longitude === null) return null;
            return {
                latLng: [this.latitude, this.longitude],
                data: this.name
            };
        }

    }
})(jQuery);


