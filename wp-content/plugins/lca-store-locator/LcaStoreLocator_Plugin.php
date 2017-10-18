<?php

include_once( 'LcaStoreLocator_LifeCycle.php' );

class LcaStoreLocator_Plugin extends LcaStoreLocator_LifeCycle {

	/**
	 * See: http://plugin.michael-simpson.com/?page_id=31
	 *
	 * @return array of option meta data.
	 */
	public function getOptionMetaData() {
		//  http://plugin.michael-simpson.com/?page_id=31
		return [//'_version' => array('Installed Version'), // Leave this one commented-out. Uncomment to test upgrades.
			'GoogleApiKey' => [ __( 'Google Maps API Key', 'lca-store-locator-plugin' ) ],
		];
	}

	//    protected function getOptionValueI18nString($optionValue) {
	//        $i18nValue = parent::getOptionValueI18nString($optionValue);
	//        return $i18nValue;
	//    }

	protected function initOptions() {
		$options = $this->getOptionMetaData();
		if ( ! empty( $options ) ) {
			foreach ( $options as $key => $arr ) {
				if ( is_array( $arr ) && count( $arr > 1 ) ) {
					$this->addOption( $key, $arr[1] );
				}
			}
		}
	}

	public function getPluginDisplayName() {
		return 'LCA Store Locator';
	}

	protected function getMainPluginFileName() {
		return 'lca-store-locator.php';
	}

	/**
	 * See: http://plugin.michael-simpson.com/?page_id=101
	 * Called by install() to create any database tables if needed.
	 * Best Practice:
	 * (1) Prefix all table names with $wpdb->prefix
	 * (2) make table names lower case only
	 *
	 * @return void
	 */
	protected function installDatabaseTables() {
		//        global $wpdb;
		//        $tableName = $this->prefixTableName('mytable');
		//        $wpdb->query("CREATE TABLE IF NOT EXISTS `$tableName` (
		//            `id` INTEGER NOT NULL");
	}

	/**
	 * See: http://plugin.michael-simpson.com/?page_id=101
	 * Drop plugin-created tables on uninstall.
	 *
	 * @return void
	 */
	protected function unInstallDatabaseTables() {
		//        global $wpdb;
		//        $tableName = $this->prefixTableName('mytable');
		//        $wpdb->query("DROP TABLE IF EXISTS `$tableName`");
	}

	/**
	 * Perform actions when upgrading from version X to version Y
	 * See: http://plugin.michael-simpson.com/?page_id=35
	 *
	 * @return void
	 */
	public function upgrade() {

	}

	public function addActionsAndFilters() {
		add_action( 'wp_enqueue_scripts', [
			&$this,
			'enqueueStylesAndScripts',
		] );
		add_action( 'admin_enqueue_scripts', [
			&$this,
			'enqueueAdminPageStylesAndScripts',
		] );
		add_action( 'admin_menu', [ &$this, 'lcaStoreLocatorOptionsPage' ] );
		add_action( 'wp_ajax_GetLocation', [ &$this, 'ajaxGetLocation' ] );
		add_action( 'wp_ajax_nopriv_GetLocation', [
			&$this,
			'ajaxGetLocation',
		] ); // optional
		add_action( 'wp_ajax_AddOrUpdateLocation', [
			&$this,
			'ajaxAddOrUpdateLocation',
		] );
		add_action( 'wp_ajax_nopriv_AddOrUpdateLocation', [
			&$this,
			'ajaxAddOrUpdateLocation',
		] ); // optional
		add_action( 'wp_ajax_DeleteLocation', [
			&$this,
			'ajaxDeleteLocation',
		] );
		add_action( 'wp_ajax_nopriv_DeleteLocation', [
			&$this,
			'ajaxDeleteLocation',
		] ); // optional
		add_action( 'wp_ajax_KeepDuplicate', [ &$this, 'ajaxKeepDuplicate' ] );
		add_action( 'wp_ajax_nopriv_KeepDuplicate', [
			&$this,
			'ajaxKeepDuplicate',
		] ); // optional

		include_once( 'LcaStoreLocator_GetLocationsShortCode.php' );
		$sc = new LcaStoreLocator_GetLocationsShortCode();
		$sc->register( 'get-locations' );
	}

	public function enqueueAdminPageStylesAndScripts() {
		wp_enqueue_script( 'jquery' );
		if ( strpos( $_SERVER['REQUEST_URI'], $this->getSettingsSlug() ) !== FALSE ) {
			wp_enqueue_style( 'jquery-ui', plugins_url( '/css/jquery-ui.css', __FILE__ ) );
			wp_enqueue_style( 'jquery-ui-theme', plugins_url( '/css/jquery-ui.theme.css', __FILE__ ) );
			wp_enqueue_style( 'bootstrap', plugins_url( '/css/bootstrap.css', __FILE__ ) );
			wp_enqueue_style( 'bootstrap-toggle', plugins_url( '/css/bootstrap-toggle.css', __FILE__ ) );
			wp_enqueue_style( 'fileinput', plugins_url( '/css/fileinput.css', __FILE__ ) );
			wp_enqueue_style( 'fileinput-rtl', plugins_url( '/css/fileinput-rtl.css', __FILE__ ) );
			wp_enqueue_style( 'all-locations', plugins_url( '/css/all-locations.css', __FILE__ ) );

			wp_enqueue_script( 'jquery-ui', plugins_url( '/js/jquery-ui.js', __FILE__ ) );
			wp_enqueue_script( 'papaparse', plugins_url( '/js/papaparse.js', __FILE__ ) );
			wp_enqueue_script( 'bootstrap', plugins_url( '/js/bootstrap.js', __FILE__ ) );
			wp_enqueue_script( 'list', plugins_url( '/js/list.min.js', __FILE__ ) );
			wp_enqueue_script( 'bootstrap-toggle', plugins_url( '/js/bootstrap-toggle.js', __FILE__ ) );
			wp_enqueue_script( 'fileinput', plugins_url( '/js/fileinput.js', __FILE__ ) );
			wp_enqueue_script( 'fileinput', plugins_url( '/js/plugins/piexif.js', __FILE__ ) );
			wp_enqueue_script( 'fileinput', plugins_url( '/js/plugins/purify.js', __FILE__ ) );
			wp_enqueue_script( 'fileinput', plugins_url( '/js/plugins/sortable.js', __FILE__ ) );
			wp_enqueue_script( 'google-maps', 'https://maps.googleapis.com/maps/api/js?key=' . $this->getOption( 'GoogleApiKey' ), NULL, NULL, FALSE );
			wp_enqueue_script( 'gmaps', plugins_url( '/js/gmaps.js', __FILE__ ) );
			wp_enqueue_script( 'all-locations', plugins_url( '/js/all-locations.js', __FILE__ ) );
			wp_enqueue_script( 'locations-modal', plugins_url( '/js/locations-modal.js', __FILE__ ) );
			wp_enqueue_script( 'countries-filter', plugins_url( '/js/countries-filter.js', __FILE__ ) );
			wp_enqueue_script( 'import-csv', plugins_url( '/js/import-csv.js', __FILE__ ) );
			wp_enqueue_script( 'export-csv', plugins_url( '/js/export-csv.js', __FILE__ ) );
			wp_enqueue_script( 'ungeocoded', plugins_url( '/js/ungeocoded.js', __FILE__ ) );
			wp_enqueue_script( 'duplicates', plugins_url( '/js/duplicates.js', __FILE__ ) );
		}
	}

	public function enqueueStylesAndScripts() {
		wp_enqueue_style( 'lca-store-locator', plugins_url( '/css/LcaStoreLocator.css', __FILE__ ) );
		wp_localize_script( 'getLocations', 'lcaAjax', [ 'ajax_url' => admin_url( 'admin-ajax.php' ) ] );
		wp_enqueue_script( 'google-maps', 'https://maps.googleapis.com/maps/api/js?key=' . $this->getOption( 'GoogleApiKey' ), NULL, NULL, FALSE );
		wp_enqueue_script( 'jquery-ui', plugins_url( '/js/jquery-ui.js', __FILE__ ) );
		wp_enqueue_script( 'list', plugins_url( '/js/list.min.js', __FILE__ ) );
		wp_enqueue_script( 'gmaps', plugins_url( '/js/gmaps.js', __FILE__ ), null, '1.0.0', true);
		wp_enqueue_script( 'lca-store-locator', plugins_url( '/js/lca-store-locator.js', __FILE__ ) );
	}

	public function ajaxGetLocation() {
		// Don't let IE cache this request
		header( "Pragma: no-cache" );
		header( "Cache-Control: no-cache, must-revalidate" );
		header( "Expires: Thu, 01 Jan 1970 00:00:00 GMT" );
		header( "Content-type: application/json" );

		$json          = new stdClass();
		$json->success = FALSE;

		$username = DB_USER;
		$password = DB_PASSWORD;
		$database = DB_NAME;
		$host     = DB_HOST;
		$charset  = 'utf8';
		$dsn      = "mysql:host=$host;dbname=$database;charset=$charset";
		$opt      = [
			PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
			PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
		];
		$pdo      = new PDO( $dsn, $username, $password, $opt );

		if ( ! empty( $_GET ) ) {
			extract( $_GET );
			unset( $_GET['id'] );
		}

		$_GET     = array_filter( $_GET );
		$identity = intval( $id );
		$sth      = $pdo->prepare( 'SELECT * FROM wp_store_locator WHERE sl_id=:identity' );
		$sth->bindParam( ':identity', $identity, PDO::PARAM_INT );
		try {
			$sth->execute();
			$location       = $sth->fetch();
			$json->location = $location;
			$json->success  = TRUE;
			echo json_encode( $json );
			die();
		} catch ( Exception $e ) {
			$json->result = [ "message" => $e ];
			echo json_encode( $json );
			die();
		}
	}

	private function check_clinic_name_match( $name ) {
		global $wpdb;
		$table = $wpdb->prefix . "store_locator";

		$row_count = $wpdb->query($wpdb->prepare(
			  "SELECT COUNT(*) FROM " . $table . " WHERE sl_store = '%s'",
              $name
          ));

		if ( $row_count === 0 ) {
			return FALSE; // No matches found.
		} else {
			return TRUE; // Matches found.
		}
	}

    private function get_clinic_name_match_id($name){
	    global $wpdb;
	    $table = $wpdb->prefix . "store_locator";

	    $clinic_id = $wpdb->query($wpdb->prepare(
		    "SELECT sl_id FROM " . $table . " WHERE sl_store = '%s'",
		    $name
	    ));

        return $clinic_id;
    }

	private function exists_in_db( $id ) {
		global $wpdb;

		$query    = "SELECT  sl_id FROM " . $wpdb->prefix . "store_locator WHERE sl_id = %d";
		$id_check = $wpdb->get_var( $wpdb->prepare( $query, $id ) );

		if ( $id_check ) {
			return TRUE;
		} else {
			return FALSE;
		}

	}

	public function ajaxAddOrUpdateLocation() {
		header( 'Access-Control-Allow-Origin: *' );
		$post                          = $_POST['row'];
		$row                           = new stdClass();
		$row->sl_id                    = intval( $post["sl_id"] );
		$row->sl_store                 = $post["sl_store"];
		$row->sl_address               = $post["sl_address"];
		$row->sl_address2              = $post["sl_address2"];
		$row->sl_city                  = $post["sl_city"];
		$row->sl_state                 = $post["sl_state"];
		$row->sl_country               = $post["sl_country"];
		$row->sl_zip                   = $post["sl_zip"];
		$row->sl_latitude              = $post["sl_latitude"];
		$row->sl_longitude             = $post["sl_longitude"];
		$row->sl_tags                  = $post["sl_tags"];
		$row->sl_description           = $post["sl_description"];
		$row->sl_url                   = $post["sl_url"];
		$row->sl_hours                 = $post["sl_hours"];
		$row->sl_phone                 = $post["sl_phone"];
		$row->sl_fax                   = $post["sl_fax"];
		$row->sl_email                 = $post["sl_email"];
		$row->sl_image                 = $post["sl_image"];
		$row->sl_private               = $post["sl_private"];
		$row->sl_neat_title            = $post["sl_neat_title"];
		$row->sl_linked_postid         = intval( $post["sl_linked_postid"] );
		$row->sl_pages_url             = $post["sl_pages_url"];
		$row->sl_pages_on              = $post["sl_pages_on"];
		$row->sl_option_value          = $post["sl_option_value"];
		$row->sl_type                  = $post["sl_type"];
		$row->sl_facebook_phone_number = $post["sl_facebook_phone_number"];
		$row->sl_website               = $post["sl_website"];
		$row->sl_seo_landing_page      = $post["sl_seo_landing_page"];
		$row->sl_real_phone            = $post["sl_real_phone"];
		$row->sl_ppc_landing_page      = $post["sl_ppc_landing_page"];
		$row->sl_landing_page          = $post["sl_landing_page"];
		$row->sl_first_name            = $post["sl_first_name"];
		$row->sl_last_name             = $post["sl_last_name"];
		$row->sl_category              = $post["sl_category"];
		$row->sl_action                = $post["sl_action"];
		$row->sl_label                 = $post["sl_label"];
		$row->sl_youtube_phone_number  = $post["sl_youtube_phone_number"];
		$row->sl_category_facebook     = $post["sl_category_facebook"];
		$row->sl_category_youtube      = $post["sl_category_youtube"];
		$row->sl_lcoa_locator          = $post["sl_lcoa_locator"];
		$row->sl_book_appointment      = $post["sl_book_appointment"];
		$row->sl_treatment_center      = intval( $post["sl_treatment_center"] );
		$row->sl_formatted_address     = intval( $post["sl_formatted_address"] );
		$row->sl_hasoffer_name         = $post["sl_hasoffer_name"];
        $row->sl_hasoffer_id           = $post["sl_hasoffer_id"];
        $row->sl_hasoffer_pixel        = $post["sl_hasoffer_pixel"];
        $row->sl_hasoffer_test_link    = $post["sl_hasoffer_test_link"];
        $row->sl_hasoffer_lca_link     = $post["sl_hasoffer_lca_link"];
        $row->sl_booking_tool_code     = $post["sl_booking_tool_code"];

		$result          = new stdClass();
		$result->success = FALSE;
		$result->row     = $row;
		$username        = DB_USER;
		$password        = DB_PASSWORD;
		$database        = DB_NAME;
		$host            = DB_HOST;
		$charset         = 'utf8';
		$dsn             = "mysql:host=$host;dbname=$database;charset=$charset";
		$opt             = [
			PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
			PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
		];
		$pdo             = new PDO( $dsn, $username, $password, $opt );

		try {
            if (( $result->row->sl_lcoa_locator == "No" ) || ( $result->row->sl_lcoa_locator == "no" )) {
				if ( $result->row->sl_id != 0 ) {
					$sth1 = $pdo->prepare( "SELECT * FROM wp_store_locator WHERE sl_id = :id1" );
					$sth1->bindParam( ':id1', $result->row->sl_id );
					$sth1->execute();
				}
			}

			if ( $result->row->sl_id != 0 ) {
				$sth1 = $pdo->prepare( "SELECT * FROM wp_store_locator WHERE sl_id = :id1" );
				$sth1->bindParam( ':id1', $result->row->sl_id );
				$sth1->execute();
				$sth1Result = $sth1->fetch();
				if ( $sth1Result ) {
					if ( $result->row->sl_store == "" ) {
						$result->row->sl_store = $this->checkNullInt( $sth1Result["sl_store"] );
					}
					if ( $result->row->sl_address == "" ) {
						$result->row->sl_address = $this->checkNull( $sth1Result["sl_address"] );
					}
					if ( $result->row->sl_address2 == "" ) {
						$result->row->sl_address2 = $this->checkNull( $sth1Result["sl_address2"] );
					}
					if ( $result->row->sl_city == "" ) {
						$result->row->sl_city = $this->checkNull( $sth1Result["sl_city"] );
					}
					if ( $result->row->sl_state == "" ) {
						$result->row->sl_state = $this->checkNull( $sth1Result["sl_state"] );
					}
					if ( $result->row->sl_country == "" ) {
						$result->row->sl_country = $this->checkNull( $sth1Result["sl_country"] );
					}
					if ( $result->row->sl_zip == "" ) {
						$result->row->sl_zip = $this->checkNull( $sth1Result["sl_zip"] );
					}
					if ( $result->row->sl_latitude == "" ) {
						$result->row->sl_latitude = $this->checkNull( $sth1Result["sl_latitude"] );
					}
					if ( $result->row->sl_longitude == "" ) {
						$result->row->sl_longitude = $this->checkNull( $sth1Result["sl_longitude"] );
					}
					if ( $result->row->sl_tags == "" ) {
						$result->row->sl_tags = $this->checkNull( $sth1Result["sl_tags"] );
					}
					if ( $result->row->sl_description == "" ) {
						$result->row->sl_description = $this->checkNull( $sth1Result["sl_description"] );
					}
					if ( $result->row->sl_url == "" ) {
						$result->row->sl_url = $this->checkNull( $sth1Result["sl_url"] );
					}
					if ( $result->row->sl_hours == "" ) {
						$result->row->sl_hours = $this->checkNull( $sth1Result["sl_hours"] );
					}
					if ( $result->row->sl_phone == "" ) {
						$result->row->sl_phone = $this->checkNull( $sth1Result["sl_phone"] );
					}
					if ( $result->row->sl_fax == "" ) {
						$result->row->sl_fax = $this->checkNull( $sth1Result["sl_fax"] );
					}
					if ( $result->row->sl_email == "" ) {
						$result->row->sl_email = $this->checkNull( $sth1Result["sl_email"] );
					}
					if ( $result->row->sl_image == "" ) {
						$result->row->sl_image = $this->checkNull( $sth1Result["sl_image"] );
					}
					if ( $result->row->sl_private == "" ) {
						$result->row->sl_private = $this->checkNull( $sth1Result["sl_private"] );
					}
					if ( $result->row->sl_neat_title == "" ) {
						$result->row->sl_neat_title = $this->checkNull( $sth1Result["sl_neat_title"] );
					}
					if ( $result->row->sl_linked_postid == 0 ) {
						$result->row->sl_linked_postid = $this->checkNullInt( $sth1Result["sl_linked_postid"] );
					}
					if ( $result->row->sl_pages_url == "" ) {
						$result->row->sl_pages_url = $this->checkNull( $sth1Result["sl_pages_url"] );
					}
					if ( $result->row->sl_pages_on == "" ) {
						$result->row->sl_pages_on = $this->checkNull( $sth1Result["sl_pages_on"] );
					}
					if ( $result->row->sl_option_value == "" ) {
						$result->row->sl_option_value = $this->checkNull( $sth1Result["sl_option_value"] );
					}
					if ( $result->row->sl_type == "" ) {
						$result->row->sl_type = $this->checkNull( $sth1Result["sl_type"] );
					}
					if ( $result->row->sl_facebook_phone_number == "" ) {
						$result->row->sl_facebook_phone_number = $this->checkNull( $sth1Result["sl_facebook_phone_number"] );
					}
					if ( $result->row->sl_website == "" ) {
						$result->row->sl_website = $this->checkNull( $sth1Result["sl_website"] );
					}
					if ( $result->row->sl_seo_landing_page == "" ) {
						$result->row->sl_seo_landing_page = $this->checkNull( $sth1Result["sl_seo_landing_page"] );
					}
					if ( $result->row->sl_real_phone == "" ) {
						$result->row->sl_real_phone = $this->checkNull( $sth1Result["sl_real_phone"] );
					}
					if ( $result->row->sl_ppc_landing_page == "" ) {
						$result->row->sl_ppc_landing_page = $this->checkNull( $sth1Result["sl_ppc_landing_page"] );
					}
					if ( $result->row->sl_landing_page == "" ) {
						$result->row->sl_landing_page = $this->checkNull( $sth1Result["sl_landing_page"] );
					}
					if ( $result->row->sl_first_name == "" ) {
						$result->row->sl_first_name = $this->checkNull( $sth1Result["sl_first_name"] );
					}
					if ( $result->row->sl_last_name == "" ) {
						$result->row->sl_last_name = $this->checkNull( $sth1Result["sl_last_name"] );
					}
					if ( $result->row->sl_category == "" ) {
						$result->row->sl_category = $this->checkNull( $sth1Result["sl_category"] );
					}
					if ( $result->row->sl_action == "" ) {
						$result->row->sl_action = $this->checkNull( $sth1Result["sl_action"] );
					}
					if ( $result->row->sl_label == "" ) {
						$result->row->sl_label = $this->checkNull( $sth1Result["sl_label"] );
					}
					if ( $result->row->sl_youtube_phone_number == "" ) {
						$result->row->sl_youtube_phone_number = $this->checkNull( $sth1Result["sl_youtube_phone_number"] );
					}
					if ( $result->row->sl_category_facebook == "" ) {
						$result->row->sl_category_facebook = $this->checkNull( $sth1Result["sl_category_facebook"] );
					}
					if ( $result->row->sl_category_youtube == "" ) {
						$result->row->sl_category_youtube = $this->checkNull( $sth1Result["sl_category_youtube"] );
					}
					if ( $result->row->sl_lcoa_locator == "" ) {
						$result->row->sl_lcoa_locator = $this->checkNull( $sth1Result["sl_lcoa_locator"] );
					}
					if ( $result->row->sl_book_appointment == 0 ) {
						$result->row->sl_book_appointment = $this->checkNullInt( $sth1Result["sl_book_appointment"] );
					}
					if ( $result->row->sl_treatment_center == 0 ) {
						$result->row->sl_treatment_center = $this->checkNullInt( $sth1Result["sl_treatment_center"] );
					}
					if ( $result->row->sl_formatted_address == "" ) {
						$result->row->sl_formatted_address = $this->checkNull( $sth1Result["sl_formatted_address"] );
					}


					if ($result->row->sl_hasoffer_name == "" ) {
						$result->row->sl_hasoffer_name = $this->checkNull( $sth1Result["sl_hasoffer_name"] );
					}

					if ($result->row->sl_hasoffer_id == 0 ) {
					    $result->row->sl_hasoffer_id = $this->checkNullInt( $sth1Result["sl_hasoffer_id"] );
					}

					if ($result->row->sl_hasoffer_pixel == "" ) {
						$result->row->sl_hasoffer_pixel = $this->checkNull( $sth1Result["sl_hasoffer_pixel"] );
					}

					if ($result->row->sl_hasoffer_test_link == "" ) {
						$result->row->sl_hasoffer_test_link = $this->checkNull( $sth1Result["sl_hasoffer_test_link"] );
					}

					if ($result->row->sl_hasoffer_lca_link == "" ) {
						$result->row->sl_hasoffer_lca_link = $this->checkNull( $sth1Result["sl_hasoffer_lca_link"] );
					}

					if ($result->row->sl_booking_tool_code == 0 ) {
					    $result->row->sl_booking_tool_code = $this->checkNullInt( $sth1Result["sl_booking_tool_code"] );
					}
				};
			}

			if ( $result->row->sl_latitude == "" ||
			     $result->row->sl_longitude == "" ) {

                if ( $result->row->sl_formatted_address == "" ) {
					$address = "";
					if ( ! empty( $result->row->sl_address ) ) {
						$address .= $result->row->sl_address;
					}
					if ( ! empty( $result->row->sl_address2 ) ) {
						$address .= " " . $result->row->sl_address2;
					}
					if ( ! empty( $result->row->sl_city ) ) {
						$address .= " " . $result->row->sl_city;
					}
					if ( ! empty( $result->row->sl_state ) ) {
						$address .= " " . $result->row->sl_state;
					}
					if ( ! empty( $result->row->sl_zip ) ) {
						$address .= " " . $result->row->sl_zip;
					}
					if ( ! empty( $result->row->sl_country ) ) {
						$address .= " " . $result->row->sl_country;
					}
					$formattedAddress                  = trim( $address );
					$result->row->sl_formatted_address = $formattedAddress;
				}
				$geocodeResult = $this->Geocode( $result->row );
				if ( ! $geocodeResult->success ) {
					$result->message = $geocodeResult->message;
					echo json_encode( $result );
					die();
				}
				$result->row = $geocodeResult->row;
			}

			$dbNames = "sl_store, ";
			$dbNames .= "sl_address, ";
			$dbNames .= "sl_address2, ";
			$dbNames .= "sl_city, ";
			$dbNames .= "sl_state, ";
			$dbNames .= "sl_country, ";
			$dbNames .= "sl_zip, ";
			$dbNames .= "sl_latitude, ";
			$dbNames .= "sl_longitude, ";
			$dbNames .= "sl_tags, ";
			$dbNames .= "sl_description, ";
			$dbNames .= "sl_url, ";
			$dbNames .= "sl_hours, ";
			$dbNames .= "sl_phone, ";
			$dbNames .= "sl_fax, ";
			$dbNames .= "sl_email, ";
			$dbNames .= "sl_image, ";
			$dbNames .= "sl_private, ";
			$dbNames .= "sl_neat_title, ";
			$dbNames .= "sl_linked_postid, ";
			$dbNames .= "sl_pages_url, ";
			$dbNames .= "sl_pages_on, ";
			$dbNames .= "sl_option_value, ";
			$dbNames .= "sl_type, ";
			$dbNames .= "sl_facebook_phone_number, ";
			$dbNames .= "sl_website, ";
			$dbNames .= "sl_seo_landing_page, ";
			$dbNames .= "sl_real_phone, ";
			$dbNames .= "sl_ppc_landing_page, ";
			$dbNames .= "sl_landing_page, ";
			$dbNames .= "sl_first_name, ";
			$dbNames .= "sl_last_name, ";
			$dbNames .= "sl_category, ";
			$dbNames .= "sl_action, ";
			$dbNames .= "sl_label, ";
			$dbNames .= "sl_youtube_phone_number, ";
			$dbNames .= "sl_category_facebook, ";
			$dbNames .= "sl_category_youtube, ";
			$dbNames .= "sl_lcoa_locator, ";
			$dbNames .= "sl_book_appointment, ";
			$dbNames .= "sl_treatment_center, ";
			$dbNames .= "sl_formatted_address, ";
			$dbNames .= "sl_hasoffer_name, ";
			$dbNames .= "sl_hasoffer_id, ";
			$dbNames .= "sl_hasoffer_pixel, ";
			$dbNames .= "sl_hasoffer_test_link, ";
			$dbNames .= "sl_hasoffer_lca_link, ";
			$dbNames .= "sl_booking_tool_code";

			$valueNames = ":slstore, ";
			$valueNames .= ":sladdress, ";
			$valueNames .= ":sladdress2, ";
			$valueNames .= ":slcity, ";
			$valueNames .= ":slstate, ";
			$valueNames .= ":slcountry, ";
			$valueNames .= ":slzip, ";
			$valueNames .= ":sllatitude, ";
			$valueNames .= ":sllongitude, ";
			$valueNames .= ":sltags, ";
			$valueNames .= ":sldescription, ";
			$valueNames .= ":slurl, ";
			$valueNames .= ":slhours, ";
			$valueNames .= ":slphone, ";
			$valueNames .= ":slfax, ";
			$valueNames .= ":slemail, ";
			$valueNames .= ":slimage, ";
			$valueNames .= ":slprivate, ";
			$valueNames .= ":slneat_title, ";
			$valueNames .= ":sllinked_postid, ";
			$valueNames .= ":slpages_url, ";
			$valueNames .= ":slpages_on, ";
			$valueNames .= ":sloption_value, ";
			$valueNames .= ":sltype, ";
			$valueNames .= ":slfacebook_phone_number, ";
			$valueNames .= ":slwebsite, ";
			$valueNames .= ":slseo_landing_page, ";
			$valueNames .= ":slreal_phone, ";
			$valueNames .= ":slppc_landing_page, ";
			$valueNames .= ":sllanding_page, ";
			$valueNames .= ":slfirst_name, ";
			$valueNames .= ":sllast_name, ";
			$valueNames .= ":slcategory, ";
			$valueNames .= ":slaction, ";
			$valueNames .= ":sllabel, ";
			$valueNames .= ":slyoutube_phone_number, ";
			$valueNames .= ":slcategory_facebook, ";
			$valueNames .= ":slcategory_youtube, ";
			$valueNames .= ":sllcoa_locator, ";
			$valueNames .= ":slbook_appointment, ";
			$valueNames .= ":sltreatment_center, ";
			$valueNames .= ":slformatted_address, ";
			$valueNames .= ":sl_hasoffer_name, ";
			$valueNames .= ":sl_hasoffer_id, ";
			$valueNames .= ":sl_hasoffer_pixel, ";
			$valueNames .= ":sl_hasoffer_test_link, ";
			$valueNames .= ":sl_hasoffer_lca_link, ";
			$valueNames .= ":sl_booking_tool_code";

			if (( $result->row->sl_lcoa_locator === "Yes" ) || ( $result->row->sl_lcoa_locator === "yes" )) {
				if ( ! is_null( $result->row->sl_id )) {
					$replaceDbNames    = "sl_id, " . $dbNames;
					$replaceValueNames = ":slid, " . $valueNames;
					$sth               = $pdo->prepare( "REPLACE INTO wp_store_locator (" . $replaceDbNames . ") VALUES (" . $replaceValueNames . ")" );
					$sth->bindParam( ':slid', $result->row->sl_id );
				} else {
					if($this->check_clinic_name_match($result->row->sl_store)) {
						$replaceDbNames    = "sl_id, " . $dbNames;
						$replaceValueNames = ":slid, " . $valueNames;
						$sth               = $pdo->prepare( "REPLACE INTO wp_store_locator (" . $replaceDbNames . ") VALUES (" . $replaceValueNames . ")" );
						$sth->bindParam( ':slid', $this->get_clinic_name_match_id($result->row->sl_store));
                    } else {
						$sth = $pdo->prepare( "INSERT INTO wp_store_locator (" . $dbNames . ") VALUES (" . $valueNames . ")" );
                    }
				}
			} else { // sl_lcoa_locator === "No"
				if ( ! is_null( $result->row->sl_id )) {
					$identity = intval( $result->row->sl_id );
					$sth      = $pdo->prepare( 'DELETE FROM wp_store_locator WHERE sl_id=:identity' );
					$sth->bindParam( ':identity', $identity, PDO::PARAM_INT );
				} else {
					if($this->check_clinic_name_match($result->row->sl_store)) {
						$replaceDbNames    = "sl_id, " . $dbNames;
						$replaceValueNames = ":slid, " . $valueNames;
						$identity = intval( $result->row->sl_id );
						$sth      = $pdo->prepare( 'DELETE FROM wp_store_locator WHERE sl_id=:identity' );
						$sth->bindParam( ':slid', $this->get_clinic_name_match_id($result->row->sl_store));
					} else {
						// Was labelled as "No" and does not have a name match.
						$result->success = TRUE;
					}
				}
			}

			if ( $result->success !== TRUE ) {
				$sth->bindParam( ':slstore', $result->row->sl_store );
				$sth->bindParam( ':sladdress', $result->row->sl_address );
				$sth->bindParam( ':sladdress2', $result->row->sl_address2 );
				$sth->bindParam( ':slcity', $result->row->sl_city );
				$sth->bindParam( ':slstate', $result->row->sl_state );
				$sth->bindParam( ':slcountry', $result->row->sl_country );
				$sth->bindParam( ':slzip', $result->row->sl_zip );
				$sth->bindParam( ':sllatitude', $result->row->sl_latitude );
				$sth->bindParam( ':sllongitude', $result->row->sl_longitude );
				$sth->bindParam( ':sltags', $result->row->sl_tags );
				$sth->bindParam( ':sldescription', $result->row->sl_description );
				$sth->bindParam( ':slurl', $result->row->sl_url );
				$sth->bindParam( ':slhours', $result->row->sl_hours );
				$sth->bindParam( ':slphone', $result->row->sl_phone );
				$sth->bindParam( ':slfax', $result->row->sl_fax );
				$sth->bindParam( ':slemail', $result->row->sl_email );
				$sth->bindParam( ':slimage', $result->row->sl_image );
				$sth->bindParam( ':slprivate', $result->row->sl_private );
				$sth->bindParam( ':slneat_title', $result->row->sl_neat_title );
				$sth->bindParam( ':sllinked_postid', $result->row->sl_linked_postid );
				$sth->bindParam( ':slpages_url', $result->row->sl_pages_url );
				$sth->bindParam( ':slpages_on', $result->row->sl_pages_on );
				$sth->bindParam( ':sloption_value', $result->row->sl_pages_on );
				$sth->bindParam( ':sltype', $result->row->sl_type );
				$sth->bindParam( ':slfacebook_phone_number', $result->row->sl_facebook_phone_number );
				$sth->bindParam( ':slwebsite', $result->row->sl_website );
				$sth->bindParam( ':slseo_landing_page', $result->row->sl_seo_landing_page );
				$sth->bindParam( ':slreal_phone', $result->row->sl_real_phone );
				$sth->bindParam( ':slppc_landing_page', $result->row->sl_ppc_landing_page );
				$sth->bindParam( ':sllanding_page', $result->row->sl_landing_page );
				$sth->bindParam( ':slfirst_name', $result->row->sl_first_name );
				$sth->bindParam( ':sllast_name', $result->row->sl_last_name );
				$sth->bindParam( ':slcategory', $result->row->sl_category );
				$sth->bindParam( ':slaction', $result->row->sl_action );
				$sth->bindParam( ':sllabel', $result->row->sl_label );
				$sth->bindParam( ':slyoutube_phone_number', $result->row->sl_youtube_phone_number );
				$sth->bindParam( ':slcategory_facebook', $result->row->sl_category_facebook );
				$sth->bindParam( ':slcategory_youtube', $result->row->sl_category_youtube );
				$sth->bindParam( ':sllcoa_locator', $result->row->sl_lcoa_locator );
				$sth->bindParam( ':slbook_appointment', $result->row->sl_book_appointment );
				$sth->bindParam( ':sltreatment_center', $result->row->sl_treatment_center );
				$sth->bindParam( ':slformatted_address', $result->row->sl_formatted_address );
				$sth->bindParam( ":sl_hasoffer_name", $result->row->sl_hasoffer_name);
				$sth->bindParam( ":sl_hasoffer_id", $result->row->sl_hasoffer_id);
				$sth->bindParam( ":sl_hasoffer_pixel", $result->row->sl_hasoffer_pixel);
				$sth->bindParam( ":sl_hasoffer_test_link", $result->row->sl_hasoffer_test_link);
				$sth->bindParam( ":sl_hasoffer_lca_link", $result->row->sl_hasoffer_lca_link);
				$sth->bindParam( ":sl_booking_tool_code", $result->row->sl_booking_tool_code);
				$sth->execute();
				$result->success = TRUE;
			}
		} catch ( Exception $e ) {
			$result->message = $e;
		}

		// force closing connection.
		$sth = null;
		$pdo = null;

		echo json_encode( $result );
		die();
	}

	public function ajaxDeleteLocation() {
		header( 'Access-Control-Allow-Origin: *' );

		$id            = $_POST['id'];
		$json          = new stdClass();
		$json->success = FALSE;

		$username = DB_USER;
		$password = DB_PASSWORD;
		$database = DB_NAME;
		$host     = DB_HOST;
		$charset  = 'utf8';
		$dsn      = "mysql:host=$host;dbname=$database;charset=$charset";
		$opt      = [
			PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
			PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
		];
		$pdo      = new PDO( $dsn, $username, $password, $opt );


		$identity = intval( $id );
		$sth      = $pdo->prepare( 'DELETE FROM wp_store_locator WHERE sl_id=:identity' );
		$sth->bindParam( ':identity', $identity, PDO::PARAM_INT );
		try {
			$sth->execute();
			$json->success = TRUE;
			$json->message = "Successfully deleted store";
			echo json_encode( $json );
			die();
		} catch ( Exception $e ) {
			$json->message = [ "message" => $e ];
			echo json_encode( $json );
			die();
		}
	}

	public function ajaxKeepDuplicate() {
		header( 'Access-Control-Allow-Origin: *' );

		$id            = $_POST['id'];
		$json          = new stdClass();
		$json->success = FALSE;

		$username = DB_USER;
		$password = DB_PASSWORD;
		$database = DB_NAME;
		$host     = DB_HOST;
		$charset  = 'utf8';
		$dsn      = "mysql:host=$host;dbname=$database;charset=$charset";
		$opt      = [
			PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
			PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
		];
		$pdo      = new PDO( $dsn, $username, $password, $opt );


		$identity = intval( $id );
		$sth      = $pdo->prepare( 'UPDATE wp_store_locator SET sl_duplicate_id = 0 WHERE sl_id=:identity' );
		$sth->bindParam( ':identity', $identity, PDO::PARAM_INT );
		try {
			$sth->execute();
			$json->success = TRUE;
			$json->message = "Successfully kept store";
			echo json_encode( $json );
			die();
		} catch ( Exception $e ) {
			$json->message = [ "message" => $e ];
			echo json_encode( $json );
			die();
		}
	}

	public function settingsPage() {
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( __( 'You do not have sufficient permissions to access this page . ', 'TEXT - DOMAIN' ) );
		}
		?>
        <div>
            <h2>Location</h2>
        </div>

        <script type="text/javascript">
            jQuery(function () {
                jQuery("#plugin_config_tabs").tabs();
            });
        </script>

		<?php
		$ungeocoded = self::GetUngeocodedLocations();
		$duplicates = self::GetDuplicateLocations();
		?>
        <div class="plugin_config">
            <div id="plugin_config_tabs">
                <ul>
                    <li><a href="#allLocations">All Locations</a></li>
					<?php if ( count( $ungeocoded ) > 0 ) : ?>
                        <li><a href="#ungeocoded">Needs Geocoding</a></li>
					<?php endif ?>
					<?php if ( count( $duplicates ) > 0 ) : ?>
                        <li><a href="#duplicates">Duplicates</a></li>
					<?php endif ?>
                    <li><a href="#mainSettings">Options</a></li>
                </ul>
                <div id="allLocations">
					<?php include_once( 'pages/all_locations.php' ); ?>
                </div>
				<?php if ( count( $ungeocoded ) > 0 ) : ?>
                    <div id="ungeocoded">
						<?php include_once( 'pages/ungeocoded.php' ); ?>
                    </div>
				<?php endif ?>
				<?php if ( count( $duplicates ) > 0 ) : ?>
                    <div id="duplicates">
						<?php include_once( 'pages/duplicates.php' ); ?>
                    </div>
				<?php endif ?>
                <div id="mainSettings">
					<?php parent::settingsPage(); ?>
                </div>
            </div>

			<?php
			include_once( 'pages/layouts/location_modal.php' );
			include_once( 'pages/layouts/filter_modal.php' );
			include_once( 'pages/layouts/import_modal.php' );
			include_once( 'pages/layouts/export_modal.php' );
			?>
        </div>
		<?php
	}

	public static function BuildLocationJson( $location ) {
		$loc                    = new stdClass();
		$loc->id                = $location["sl_id"];
		$loc->name              = $location["sl_store"];
		$loc->lastupdated       = $location["sl_lastupdated"];
		$loc->formatted_address = $location["sl_formatted_address"];
		$loc->duplicate_id      = $location["sl_duplicate_id"];

		$loc->street1   = $location["sl_address"];
		$loc->street2   = $location["sl_address2"];
		$loc->street    = $loc->street2 != "" ? $loc->street1 . " " . $loc->street2 : $loc->street1;
		$loc->city      = $location["sl_city"];
		$loc->state     = $location["sl_state"];
		$loc->country   = $location["sl_country"];
		$loc->zip       = $location["sl_zip"];
		$loc->latitude  = $location["sl_latitude"];
		$loc->longitude = $location["sl_longitude"];

		$loc->first_name = $location["sl_first_name"];
		$loc->last_name  = $location["sl_last_name"];
		$loc->phone      = $location["sl_phone"];
		$loc->email      = $location["sl_email"];
		$loc->real_phone = $location["sl_real_phone"];
		$loc->website    = $location["sl_website"];
		$loc->fax        = $location["sl_fax"];
		$loc->url        = $location["sl_url"];

		$loc->bookable         = $location["sl_book_appointment"] == 1 && $location["sl_treatment_center"] == 1;
		$loc->treatment_center = $location["sl_treatment_center"] == 1;
		$loc->tags             = $location["sl_tags"];

		$loc->facebook_phone_number = $location["sl_facebook_phone_number"];
		$loc->category_facebook     = $location["sl_category_facebook"];
		$loc->youtube_phone_number  = $location["sl_youtube_phone_number"];
		$loc->category_youtube      = $location["sl_category_youtube"];
		$loc->landing_page          = $location["sl_landing_page"];
		$loc->seo_landing_page      = $location["sl_seo_landing_page"];
		$loc->ppc_landing_page      = $location["sl_ppc_landing_page"];

		$loc->category = $location["sl_category"];
		$loc->action   = $location["sl_action"];
		$loc->label    = $location["sl_label"];

		$loc->description   = $location["sl_description"];
		$loc->image         = $location["sl_image"];
		$loc->hours         = $location["sl_hours"];
		$loc->neat_title    = $location["sl_neat_title"];
		$loc->linked_postid = $location["sl_linked_postid"];
		$loc->pages_url     = $location["sl_pages_url"];
		$loc->pages_on      = $location["sl_pages_on"];
		$loc->option_value  = $location["sl_option_value"];
		$loc->private       = $location["sl_private"] == 1;

		return $loc;
	}

	public static function GetUngeocodedLocations() {
		$username = DB_USER;
		$password = DB_PASSWORD;
		$database = DB_NAME;
		$host     = DB_HOST;
		$charset  = 'utf8';

		$dsn = "mysql:host=$host;dbname=$database;charset=$charset";
		$opt = [
			PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
			PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
		];
		$pdo = new PDO( $dsn, $username, $password, $opt );

		$sth = $pdo->prepare( 'SELECT * FROM wp_store_locator WHERE sl_latitude IS NULL OR sl_latitude = "" OR sl_longitude IS NULL OR sl_longitude = "" OR sl_formatted_address IS NULL ORDER BY sl_id' );

		$sth->execute();
		$locations = $sth->fetchAll();

		return $locations;
	}

	public static function GetDuplicateLocations() {
		$username = DB_USER;
		$password = DB_PASSWORD;
		$database = DB_NAME;
		$host     = DB_HOST;
		$charset  = 'utf8';

		$dsn = "mysql:host=$host;dbname=$database;charset=$charset";
		$opt = [
			PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
			PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
		];
		$pdo = new PDO( $dsn, $username, $password, $opt );

		$sth = $pdo->prepare( 'SELECT * FROM wp_store_locator WHERE sl_duplicate_id != 0' );
		$sth->execute();
		$locations = $sth->fetchAll();

		return $locations;
	}

	function Geocode( $row ) {
		$result          = new stdClass();
		$result->success = FALSE;
		$result->row     = $row;
		$result->message = "";
		try {

			$encodedAddress = urlencode( $row->sl_formatted_address );
			$url            = "https://maps.googleapis.com/maps/api/geocode/json?address=={$encodedAddress}&key={$this->getOption('GoogleApiKey')}";

			$resp_json = file_get_contents( $url );
			$resp      = json_decode( $resp_json, TRUE );
			if ( $resp['status'] == 'OK' ) {
				$address_components = $resp['results'][0]["address_components"];

				$geocodeResult                              = new stdClass();
				$geocodeResult->street_address              = NULL;
				$geocodeResult->route                       = NULL;
				$geocodeResult->intersection                = NULL;
				$geocodeResult->country                     = NULL;
				$geocodeResult->administrative_area_level_1 = NULL;
				$geocodeResult->administrative_area_level_2 = NULL;
				$geocodeResult->administrative_area_level_3 = NULL;
				$geocodeResult->administrative_area_level_4 = NULL;
				$geocodeResult->administrative_area_level_5 = NULL;
				$geocodeResult->locality                    = NULL;
				$geocodeResult->postal_code                 = NULL;
				$geocodeResult->street_number               = NULL;
				$geocodeResult->floor                       = NULL;
				$geocodeResult->room                        = NULL;

				foreach ( $address_components as $address_component ) {
					$long  = $address_component["long_name"];
					$short = $address_component["short_name"];
					$name  = $short != "" ? $short : $long;

					$types = $address_component["types"];
					foreach ( $types as $type ) {
						if ( $type == 'street_address' && $geocodeResult->street_address == NULL ) {
							$geocodeResult->street_address = $name;
						} else if ( $type == 'route' && $geocodeResult->route == NULL ) {
							$geocodeResult->route = $name;
						} else if ( $type == 'intersection' && $geocodeResult->intersection == NULL ) {
							$geocodeResult->intersection = $name;
						} else if ( $type == 'country' && $geocodeResult->country == NULL ) {
							$geocodeResult->country = $name;
						} else if ( $type == 'administrative_area_level_1' && $geocodeResult->administrative_area_level_1 == NULL ) {
							$geocodeResult->administrative_area_level_1 = $name;
						} else if ( $type == 'administrative_area_level_2' && $geocodeResult->administrative_area_level_2 == NULL ) {
							$geocodeResult->administrative_area_level_2 = $name;
						} else if ( $type == 'administrative_area_level_3' && $geocodeResult->administrative_area_level_3 == NULL ) {
							$geocodeResult->administrative_area_level_3 = $name;
						} else if ( $type == 'administrative_area_level_4' && $geocodeResult->administrative_area_level_4 == NULL ) {
							$geocodeResult->administrative_area_level_4 = $name;
						} else if ( $type == 'administrative_area_level_5' && $geocodeResult->administrative_area_level_5 == NULL ) {
							$geocodeResult->administrative_area_level_5 = $name;
						} else if ( $type == 'locality' && $geocodeResult->locality == NULL ) {
							$geocodeResult->locality = $name;
						} else if ( $type == 'postal_code' && $geocodeResult->postal_code == NULL ) {
							$geocodeResult->postal_code = $name;
						} else if ( $type == 'street_number' && $geocodeResult->street_number == NULL ) {
							$geocodeResult->street_number = $name;
						} else if ( $type == 'floor' && $geocodeResult->floor == NULL ) {
							$geocodeResult->floor = $name;
						} else if ( $type == 'room' && $geocodeResult->room == NULL ) {
							$geocodeResult->room = $name;
						}
					}
				}

				$geocodedResult                   = new stdClass();
				$geocodedResult->Street1          = NULL;
				$geocodedResult->Street2          = NULL;
				$geocodedResult->City             = NULL;
				$geocodedResult->State            = NULL;
				$geocodedResult->Zip              = NULL;
				$geocodedResult->Country          = NULL;
				$geocodedResult->Latitude         = NULL;
				$geocodedResult->Longitude        = NULL;
				$geocodedResult->FormattedAddress = NULL;
				$street1                          = "";
				if ( $geocodeResult->street_number != NULL ) {
					$street1 .= trim( $geocodeResult->street_number ) . " ";
				}
				if ( $geocodeResult->street_address != NULL ) {
					$street1 .= trim( $geocodeResult->street_address );
				} else if ( $geocodeResult->route != NULL ) {
					$street1 .= trim( $geocodeResult->route );
				} else if ( $geocodeResult->intersection != NULL ) {
					$street1 .= trim( $geocodeResult->intersection );
				} else {
					$street1 = trim( $street1 );
				}
				$geocodedResult->Street1 = $street1 == "" ? NULL : $street1;

				$street2 = "";
				if ( $geocodeResult->floor != NULL ) {
					$street2 .= $geocodeResult->floor;
				}
				if ( $geocodeResult->room != NULL ) {
					$street2 .= " " . $geocodeResult->floor;
				}
				$street2 = trim( $street2 );

				$geocodedResult->Street2 = $street2 == "" ? NULL : $street2;

				$geocodedResult->City = $geocodeResult->locality == NULL ? NULL : trim( $geocodeResult->locality );

				if ( $geocodeResult->administrative_area_level_1 != NULL ) {
					$geocodedResult->State = trim( $geocodeResult->administrative_area_level_1 );
				} else if ( $geocodeResult->administrative_area_level_2 != NULL ) {
					$geocodedResult->State = trim( $geocodeResult->administrative_area_level_2 );
				} else if ( $geocodeResult->administrative_area_level_3 != NULL ) {
					$geocodedResult->State = trim( $geocodeResult->administrative_area_level_3 );
				} else if ( $geocodeResult->administrative_area_level_4 != NULL ) {
					$geocodedResult->State = trim( $geocodeResult->administrative_area_level_4 );
				} else if ( $geocodeResult->administrative_area_level_5 != NULL ) {
					$geocodedResult->State = trim( $geocodeResult->administrative_area_level_5 );
				} else {
					$geocodedResult->State = NULL;
				}

				$geocodedResult->Zip = $geocodeResult->postal_code == NULL ? NULL : trim( $geocodeResult->postal_code );

				$geocodedResult->Country = $geocodeResult->country == NULL ? NULL : trim( $geocodeResult->country );

				$geocodedResult->Latitude         = $resp['results'][0]['geometry']['location']['lat'];
				$geocodedResult->Longitude        = $resp['results'][0]['geometry']['location']['lng'];
				$geocodedResult->FormattedAddress = $resp['results'][0]['formatted_address'];

				if ( $geocodedResult->Street1 != NULL ) {
					$row->sl_address = $geocodedResult->Street1;
				}
				if ( $geocodedResult->Street2 != NULL ) {
					$row->sl_address2 = $geocodedResult->Street2;
				}
				if ( $geocodedResult->City != NULL ) {
					$row->sl_city = $geocodedResult->City;
				}
				if ( $geocodedResult->State != NULL ) {
					$row->sl_state = $geocodedResult->State;
				}
				if ( $geocodedResult->Zip != NULL ) {
					$row->sl_zip = $geocodedResult->Zip;
				}
				if ( $geocodedResult->Country != NULL ) {
					$row->sl_country = $geocodedResult->Country;
				}
				if ( $geocodedResult->Latitude != NULL ) {
					$row->sl_latitude = $geocodedResult->Latitude;
				}
				if ( $geocodedResult->Longitude != NULL ) {
					$row->sl_longitude = $geocodedResult->Longitude;
				}
				if ( $geocodedResult->FormattedAddress != NULL ) {
					$row->sl_formatted_address = $geocodedResult->FormattedAddress;
				}
				$result->row     = $row;
				$result->success = TRUE;
			} else {
				$result->message = $resp['status'];
			}
		} catch ( Exception $e ) {
			$result->message = $e;
		}

		return $result;

	}

	function checkNull( $val ) {
		if ( is_null( $val ) ) {
			return "";
		}

		return $val;
	}

	function checkNullInt( $val ) {
		if ( is_null( $val ) ) {
			return 0;
		}

		return $val;
	}
}
