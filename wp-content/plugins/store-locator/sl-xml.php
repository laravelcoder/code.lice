<?php
function xml_out($buff) {
	preg_match("@<markers>.*<\/markers>@s", $buff, $the_xml);
	//$the_xml[0]=preg_replace("@\n@","",$the_xml[0]);
	return $the_xml[0];
}
if(!isset($_REQUEST['listc'])){
	if (empty($_GET['debug'])) {
		ob_start("xml_out");
	}
	header("Content-type: text/xml");
}
include("sl-inc/includes/sl-env.php");

// Opens a connection to a MySQL server
$connection=mysql_connect ($host, $username, $password);
if (!$connection) { die('Not connected : ' . mysql_error()); }

// Set the active MySQL database
$db_selected = mysql_select_db($database, $connection);
mysql_query("SET NAMES utf8");
if (!$db_selected) { die ('Can\'t use db : ' . mysql_error());}

//Removing any vars never intended for $_GET
$sl_ap_xml = array("sl_custom_fields", "sl_xml_columns");
foreach ($sl_ap_xml as $value){ if (!empty($_GET[$value])){ unset($_GET[$value]); } }

$sl_custom_fields = (!empty($sl_xml_columns))? ", ".implode(", ", $sl_xml_columns) : "" ;
$load_limit = isset($_GET['load_limit']);
if (!empty($_GET)) { extract($_GET); unset($_GET['mode']); unset($_GET['lat']); unset($_GET["lng"]); unset($_GET["radius"]); unset($_GET["edit"]);}
$_GET=array_filter($_GET); //removing any empty $_GET items that may disrupt query

$sl_param_where_clause="";
if (function_exists("do_sl_hook")){ do_sl_hook("sl_xml_query"); }

$slID = intval(base64_decode($_GET['sl']));
$sel_where = '';
if($slID > 0){
	$sel_where .= ' AND `sl_id` ='.$slID;
}
if (!empty($mode) && $mode=='gen') {
	// Get parameters from URL
	$center_lat = $lat;
	$center_lng = $lng;
	$radius = $radius;
	
	$multiplier=3959;
	$multiplier=($sl_vars['distance_unit']=="km")? ($multiplier*1.609344) : $multiplier;

	$num_initial_displayed=(trim($sl_vars['num_initial_displayed'])!="")? $sl_vars['num_initial_displayed'] : "25";
	// Select all the rows in the markers table
	$query = sprintf(
	"SELECT sl_id, sl_address, sl_address2, sl_store, sl_city, sl_state, sl_zip, sl_latitude, sl_longitude, sl_description, sl_url, sl_hours, sl_phone, sl_fax, sl_email, sl_image, sl_landing_page, sl_tags, sl_facebook_phone_number".
	" $sl_custom_fields,".
	" ( $multiplier * acos( cos( radians('%s') ) * cos( radians( sl_latitude ) ) * cos( radians( sl_longitude ) - radians('%s') ) + sin( radians('%s') ) * sin( radians( sl_latitude ) ) ) ) AS sl_distance".
	" FROM ".SL_TABLE.
	" WHERE sl_store<>'' AND sl_longitude<>'' AND sl_latitude<>''".
	" $sl_param_where_clause ". $sel_where .
	" ORDER BY sl_distance ASC LIMIT $num_initial_displayed",
	//" HAVING sl_distance < '%s' ORDER BY sl_distance ASC LIMIT $num_initial_displayed",
	esc_sql($center_lat),
	esc_sql($center_lng),
	esc_sql($center_lat));
	//esc_sql($radius)); //die($query);
} else {
	$num_initial_displayed=(trim($sl_vars['num_initial_displayed'])!="")? $sl_vars['num_initial_displayed'] : "25";
	// Select all the rows in the markers table
	$query = "SELECT sl_id, sl_address, sl_address2, sl_store, sl_city, sl_state, sl_zip, sl_latitude, sl_longitude, sl_description, sl_url, sl_hours, sl_phone, sl_fax, sl_email, sl_image, sl_landing_page, sl_tags, sl_facebook_phone_number".
	" $sl_custom_fields".
	" FROM ".SL_TABLE.
	" WHERE sl_store<>'' AND sl_longitude<>'' AND sl_latitude<>''".
	" $sl_param_where_clause". $sel_where . 
	" ORDER BY sl_city ASC LIMIT $num_initial_displayed"; //die($query);
}
//die($query);
$result = mysql_query($query);
$num_rows = mysql_num_rows($result);
if($num_rows == 0){
	$creNewQuery = explode("WHERE sl_store<>''", $query);
	$newSql = $creNewQuery['0']."  WHERE sl_address LIKE '%".trim($_REQUEST['address'])."%' AND sl_store<>'' AND sl_longitude<>'' AND sl_latitude<>'' ". $sel_where ."  ORDER BY sl_city ASC LIMIT $num_initial_displayed";
	$result = mysql_query($newSql);
}


if (!$result) { die('Invalid query: ' . mysql_error()); }
if(!isset($_REQUEST['listc'])){
		// Start XML file, echo parent node
		echo "<markers>\n";
			// Iterate through the rows, printing XML nodes for each
			$count = 0; 
				while ($row = @mysql_fetch_assoc($result)){     

					if ( ($count >0 && $row['sl_distance'] >100) && !$load_limit) break;

				  $addr2=(trim($row['sl_address2'])!="")? " ".parseToXML($row['sl_address2']) : "" ;
				  $row['sl_distance']=(!empty($row['sl_distance']))? $row['sl_distance'] : "" ;
				  $row['sl_url']=(!url_test($row['sl_url']) && trim($row['sl_url'])!="")? "http://".$row['sl_url'] : $row['sl_url'] ;
				  if($row['sl_facebook_phone_number'] != ''){
					$phone = $row['sl_facebook_phone_number'];
				  }else{
						$phone = $row['sl_phone'];
				  }
				  $distance = ($row['sl_distance'] == 0) ? "0" : $row['sl_distance'];

				  // ADD TO XML DOCUMENT NODE
				  echo '<marker ';
				  //echo 'sl_id="' . parseToXML($row['sl_id']) . '" ';
				  echo 'reference_number="' . parseToXML($row['sl_id']) . '" ';
				  echo 'name="' . parseToXML($row['sl_store']) . '" ';
				  echo 'address="' . parseToXML($row['sl_address']) .$addr2. ', '. parseToXML($row['sl_city']). ', ' .parseToXML($row['sl_state']).' ' .parseToXML($row['sl_zip']).'" ';
				  echo 'street="' . parseToXML($row['sl_address']) . '" ';  //should've been sl_street in DB
				  echo 'street2="' . parseToXML($row['sl_address2']) . '" '; //should've been sl_street2 in DB
				  echo 'city="' . parseToXML(''). '" ';
				  echo 'state="' . parseToXML(''). '" ';
				  echo 'zip="' . parseToXML(''). '" ';
				  echo 'lat="' . $row['sl_latitude'] . '" ';
				  echo 'lng="' . $row['sl_longitude'] . '" ';
				  echo 'distance="' . $distance . '" ';
				  echo 'description="' . parseToXML($row['sl_description']) . '" ';
				  echo 'url="' . parseToXML($row['sl_url']) . '" ';
				  echo 'hours="' . parseToXML($row['sl_hours']) . '" ';
				  echo 'phone="' . parseToXML($row['sl_facebook_phone_number']) . '" ';
				  echo 'fax="' . parseToXML($row['sl_fax']) . '" ';
				  echo 'email="' . parseToXML($row['sl_email']) . '" ';
				  echo 'sl_landing_page="' . parseToXML($row['sl_landing_page']) . '" ';
				  echo 'image="' . parseToXML($row['sl_image']) . '" ';
				  echo 'tags="' . parseToXML($row['sl_tags']) . '" ';
				  if (!empty($sl_xml_columns)){ 
				  $alrdy_used=array('name', 'address', 'street', 'street2', 'city', 'state', 'zip', 'lat', 'lng', 'distance', 'description', 'url', 'hours', 'phone', 'fax', 'email', 'image', 'tags');
					foreach($sl_xml_columns as $key=>$value) {
						if (!in_array($value, $alrdy_used)) { //can't have duplicate property names in xml
							$row[$value]=(!isset($row[$value]))? "" : $row[$value] ;
							 echo "$value=\"" . parseToXML($row[$value]) . "\" ";
							 $alrdy_used[]=$value;
						}
					}
				  }
				  echo "/>\n";    
		  		  if ( ($count > 25 || $row['sl_distance'] >100)  && !$load_limit ) break;
				  $count++; 
				}
			// End XML file
		echo "</markers>\n";
		if (empty($_GET['debug'])) {
			ob_end_flush();
		}
		//var_dump($_GET);
		//print_r($sl_xml_columns); die();
		//die($query);
		//var_dump($sl_param_where_clause); die;
}else{

	$creNewQuery = explode("WHERE sl_store<>''", $query);
	$searchArrx = explode(',', $_REQUEST['address']);
	/*$newSqlx = $creNewQuery['0']."  WHERE sl_city LIKE '%".trim($searchArrx[0])."%' AND sl_store<>'' AND sl_longitude<>'' AND sl_latitude<>'' ". $sel_where ."  GROUP BY `sl_state` ORDER BY sl_city ASC LIMIT $num_initial_displayed";
	$resultx = mysql_query($newSqlx);
	$searchArr = array();
	$i = 0;
	if(mysql_num_rows($resultx) > 0){
		while ($row = @mysql_fetch_array($resultx)){
			$searchStr = str_replace(' ', '', strtolower($row['sl_city'].'_'.$row['sl_state'].'_'.$row['sl_zip']));
			$searchArr[$searchStr]= array('city' => $row['sl_city'], 
								 'province' => $row['sl_state'],
								 'country' => 'USA',
								 'postcode' => $row['sl_zip']
							);
			$i++;
		}
	}*/

	$markerAddressArr = Get_Address_From_Google_Maps(esc_sql($center_lat), esc_sql($center_lng), esc_sql(trim($searchArrx[0])));

	if(count($markerAddressArr) > 0){
		foreach($markerAddressArr as $markerAddress){
			$seaAddr = array_reverse(explode(',', $markerAddress['formatted_address']));
			$provi = trim(str_replace($markerAddress['postal_code'], '',  $seaAddr['1']));
			$searchStr = str_replace(' ', '', strtolower($markerAddress['city'].'_'.$provi.'_'.$markerAddress['postal_code']));
			$searchArr[$searchStr] = array('city' => $markerAddress['city'], 
							 'province' => $provi,
							 'country' => $seaAddr['0'],
							 'postcode' => $markerAddress['postal_code']
						);
			$i++;
		}

	}
	/*echo "<pre>";
	print_r($searchArr);
	echo "</pre>";
	searchArr = array_unique($searchArr);*/
//	if(count($searchArr) > 1){
//			$storeAdd = '<div class="arohamajxcont">';
//			$storeAdd .= '<p class="heading">Please Choose location you intended:<p>';
//			foreach($searchArr as $rows){
//				$searchStr = ucwords($rows['city'].', '.$rows['province'].', '.$rows['country']);
//				$storeAdd.= '<p><a href="clinics-near-you/?docname='.$rows['city'].', '.$rows['province'].'&cws-stafftreatments='.$_REQUEST['radius'].'">'.$searchStr.' </a></p>';
//			}
//			$storeAdd.= '</div>';
//			echo $storeAdd;
//	}
}


function Get_Address_From_Google_Maps($lat, $lon, $city) {
	$url = "http://maps.googleapis.com/maps/api/geocode/json?address=$city&sensor=false";
	$data = @file_get_contents($url);
	$jsondataArr = json_decode($data,true);
	if (!check_status($jsondataArr))   return array();
	$ix = 0;
	foreach($jsondataArr['results'] as $jsondata){
			$address[$ix] = array(
			'country' => google_getCountry($jsondata),
			'province' => google_getProvince($jsondata),
			'city' => google_getCity($jsondata),
			'street' => google_getStreet($jsondata),
			'postal_code' => google_getPostalCode($jsondata),
			'country_code' => google_getCountryCode($jsondata),
			'formatted_address' => google_getAddress($jsondata),
		);
		$ix++;
	}
	return $address;
}

function check_status($jsondata) {
    if ($jsondata["status"] == "OK") return true;
    return false;
}

function google_getCountry($jsondata) {
    return Find_Long_Name_Given_Type("country", $jsondata["address_components"]);
}
function google_getProvince($jsondata) {
    return Find_Long_Name_Given_Type("administrative_area_level_1", $jsondata["address_components"], true);
}
function google_getCity($jsondata) {
    return Find_Long_Name_Given_Type("locality", $jsondata["address_components"]);
}
function google_getStreet($jsondata) {
    return Find_Long_Name_Given_Type("street_number", $jsondata["address_components"]) . ' ' . Find_Long_Name_Given_Type("route", $jsondata["address_components"]);
}
function google_getPostalCode($jsondata) {
    return Find_Long_Name_Given_Type("postal_code", $jsondata["address_components"]);
}
function google_getCountryCode($jsondata) {
    return Find_Long_Name_Given_Type("country", $jsondata["address_components"], true);
}
function google_getAddress($jsondata) {
    return $jsondata["formatted_address"];
}

function Find_Long_Name_Given_Type($type, $array, $short_name = false) {
    foreach( $array as $value) {
        if (in_array($type, $value["types"])) {
            if ($short_name)    
                return $value["short_name"];
            return $value["long_name"];
        }
    }
}

?>
