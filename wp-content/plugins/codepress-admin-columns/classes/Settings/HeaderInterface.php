<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

interface AC_Settings_HeaderInterface {

	/**
	 * @return AC_View|false
	 */
	public function create_header_view();

}
