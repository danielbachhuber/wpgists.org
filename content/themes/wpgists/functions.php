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
			self::$instance->setup_actions();
		}

		return self::$instance;
	}

	/**
	 * Load required files
	 */
	private function require_files() {



	}

	/**
	 * Set up necessary WordPress actions
	 */
	private function setup_actions() {

		add_action( 'wp_enqueue_scripts', array( $this, 'action_wp_enqueue_scripts' ) );
	}

	/**
	 * Enqueue our scripts and styles
	 */
	public function action_wp_enqueue_scripts() {

		// Foundation
		wp_enqueue_script( 'foundation', get_stylesheet_directory_uri() . '/lib/foundation/foundation.min.js', array( 'jquery' ), '5.2.2' );
		wp_enqueue_style( 'foundation', get_stylesheet_directory_uri() . '/lib/foundation/foundation.min.css', false, '5.2.2' );

	}

}

/**
 * Load the theme
 */
function WP_Gists() {
	return WP_Gists::get_instance();
}
add_action( 'after_setup_theme', 'WP_Gists' );
