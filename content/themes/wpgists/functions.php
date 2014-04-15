<?php

/**
 * A singleton class representing wpgists.org
 */
class WP_Gists {

	private static $instance;

	public static function get_instance() {

		if ( ! isset( self::$instance ) ) {
			self::$instance = new WP_Gists;
			self::$instance->require_files();
		}

		return self::$instance;
	}

	/**
	 * Load required files
	 */
	private function require_files() {



	}

}

/**
 * Load the theme
 */
function WP_Gists() {
	return WP_Gists::get_instance();
}
add_action( 'after_setup_theme', 'WP_Gists' );
