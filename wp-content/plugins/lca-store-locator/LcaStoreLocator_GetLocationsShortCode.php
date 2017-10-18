<?php
include_once('LcaStoreLocator_ShortCodeLoader.php');
include_once('LcaStoreLocator_LifeCycle.php');

class LcaStoreLocator_GetLocationsShortCode extends LcaStoreLocator_ShortCodeLoader
{
    /**
     * @param  $atts shortcode inputs
     * @return string shortcode content
     */
    public function handleShortcode($atts)
    {
        $value = null;
        if (isset($_GET['docname'])) {
            $value = $_GET['docname'];
        }
        $a = shortcode_atts(array('types' => null, 'limit' => 10), $atts);
        $type = $a['types'];
        $limit = intval($a['limit']);

        if ($value == null) return "";
        $geocodeResult = $this->GeocodeValue($value);
        if (!$geocodeResult->success) {
            return "<div class='alert alert-danger' role='alert'>" . $geocodeResult->message . "</div>";
        }
        $locations = is_null($type) ? $this->GetClosestLocations($geocodeResult->latitude, $geocodeResult->longitude, $limit) : $this->GetClosestLocationsForType($geocodeResult->latitude, $geocodeResult->longitude, $limit, $type);

        $map = "<div id='lca_map' class='store-locator-map'></div>";
        $list = "<div id='lca_list' class='store-locator-list'><ul class='list'>";
        foreach ($locations as $location) {
            $loc = new stdClass();
            $loc->id = $location["sl_id"];
            $loc->name = $location["sl_store"];

            $loc->address = $location["sl_address"];

	        /**
	         *  Matches on  "City With Spaces, KY 55555-3333"
	         *
	         *  The last four zip code digits are optional. Example, the following string will produce a truthy value:
	         *    "Louisville, KY 40299" => true
	         *    "Louisville, KY 40299-1111" => true
	         *    "Louisville, KY" => false
	         */
            if(!preg_match("/([^,]+),\s*(\w{2})\s*(\d{5}(?:-\d{4})?)/", $loc->address)) {
	            if ($location["sl_address2"] != "") {
		            $loc->address .= ", " . $location["sl_address2"];
	            }

	            if($location["sl_city"] !== "") {
		            $loc->address .= " " . $location["sl_city"];
	            }

	            if ($location["sl_state"] !== "") {
		            $loc->address .= ", " . $location["sl_state"];
	            }

	            if ($location["sl_country"] !== "") {
		            $loc->address .= ", " . $location["sl_country"];
	            }

	            if ($location["sl_zip"] !== "") {
		            $loc->address .= " " . $location["sl_zip"];
	            }
            }

	        $loc->address = rtrim($loc->address);
            $loc->distance = round($location["sl_distance"], 2);
            $loc->phone = $location["sl_phone"];
            $loc->landing = $location["sl_landing_page"];
            $loc->booking = $location["sl_book_appointment"];
            $loc->email = $location["sl_email"];
            $loc->latitude = $location["sl_latitude"];
            $loc->longitude = $location["sl_longitude"];
            $loc->image = $location["sl_image"];
            $list .= "<li class='location' data-id='" . $loc->id . "' data-email='" . $loc->email . "' data-latitude='" . $loc->latitude . "' data-longitude='" . $loc->longitude . "' data-image='" . $loc->image . "'>";

            $startAddressEncoded = urlencode($geocodeResult->formattedAddress);
            $destAddressEncoded = urlencode_deep($location["sl_formatted_address"]);
            $href = "https://maps.google.com/maps?saddr=" . $startAddressEncoded . "&daddr=" . $destAddressEncoded;
            $name = "<h3 class='name'>" . $loc->name . "</h3>";
            $distance = "<div class='distance-container'><span class='distance'>" . $loc->distance . "</span> miles away <a href='" . $href . "' target='_blank'>Get Directions</a></div>";
            $address = "<div class='address'>" . $loc->address . "</div>";
            $locInfo = "<div class='location-info'>" . $name . $distance . $address;
            if ($loc->phone != "") {
                $locInfo .= "<a class='phone' href='tel:" . $loc->phone . "'>" . $loc->phone . "</a>";
            }
            $locInfo .= "</div>";
            $list .= $locInfo;

            if ($loc->landing != "" || $loc->booking == 1) {
                $locButtons = "<div class='location-buttons'>";
                if ($loc->landing != "") {
                    $locButtons .= "<a href='" . $loc->landing . "' class='get-info landing' target='_blank'>Get Info</a>";
                }
                if ($loc->booking == 1) {
                    $locButtons .= "<a href='/book-appointment/?reference_number=" . $loc->id . "' class='get-info booking' target='_blank'>Book Now</a>";
                }
                $locButtons .= "</div>";
                $list .= $locButtons;
            }
            $list .= "</li>";
        }
        $list .= "</ul></div></div>";
        return "<div class='store-locator'>" . $map . $list . "</div>";

    }

    function GeocodeValue($docname)
    {
        $result = new stdClass();
        $result->success = false;
        $encodedAddress = urlencode($docname);
        $url = "https://maps.googleapis.com/maps/api/geocode/json?address=={$encodedAddress}&key=AIzaSyDYnVtzGEXD79VNW8tEemBHHbNqI880fl0";
        try {
            $resp_json = file_get_contents($url);
            $resp = json_decode($resp_json, true);
            if ($resp['status'] != 'OK') {
                $result->message = "Geocoding location gave response of " . $resp['status'];
            } else {
                $result->latitude = $resp['results'][0]['geometry']['location']['lat'];
                $result->longitude = $resp['results'][0]['geometry']['location']['lng'];
                $result->formattedAddress = $resp['results'][0]['formatted_address'];
                $result->success = true;
            }
        } catch (Exception $e) {
            $result->message = json_encode($e);
        }
        return $result;
    }

    function GetClosestLocations($lat, $lng, $limit)
    {
        $username = DB_USER;
        $password = DB_PASSWORD;
        $database = DB_NAME;
        $host = DB_HOST;
        $charset = 'utf8';
        $dsn = "mysql:host=$host;dbname=$database;charset=$charset";
        $opt = array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC);
        $pdo = new PDO($dsn, $username, $password, $opt);

        $sth = $pdo->prepare("SELECT *, 3956 * 2 * ASIN(SQRT(POWER(SIN((:latitude - sl_latitude) * pi()/180 / 2), 2) + COS(:latitude * pi()/180) * COS(sl_latitude * pi()/180) * POWER(SIN((:longitude - sl_longitude) * pi()/180 / 2), 2))) AS sl_distance FROM wp_store_locator ORDER BY sl_distance LIMIT :limit");
        $sth->bindParam(':latitude', $lat);
        $sth->bindParam(':longitude', $lng);
        $sth->bindParam(':limit', $limit, PDO::PARAM_INT);
        $sth->execute();
        return $sth->fetchAll();;
    }

    function GetClosestLocationsForType($lat, $lng, $limit, $type)
    {
        $username = DB_USER;
        $password = DB_PASSWORD;
        $database = DB_NAME;
        $host = DB_HOST;
        $charset = 'utf8';
        $dsn = "mysql:host=$host;dbname=$database;charset=$charset";
        $opt = array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC);
        $pdo = new PDO($dsn, $username, $password, $opt);

        $sth = $pdo->prepare("SELECT *, 3956 * 2 * ASIN(SQRT(POWER(SIN((:latitude - sl_latitude) * pi()/180 / 2), 2) + COS(:latitude * pi()/180) * COS(sl_latitude * pi()/180) * POWER(SIN((:longitude - sl_longitude) * pi()/180 / 2), 2))) AS sl_distance FROM wp_store_locator WHERE sl_type = :type ORDER BY sl_distance LIMIT :limit");
        $sth->bindParam(':latitude', $lat);
        $sth->bindParam(':longitude', $lng);
        $sth->bindParam(':limit', $limit, PDO::PARAM_INT);
        $sth->bindParam(':type', $type);
        $sth->execute();
        return $sth->fetchAll();
    }
}
