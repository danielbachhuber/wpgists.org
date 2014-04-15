<?php

namespace WP_Gists;

class Gist extends \WordPress_Objects\Post {

	/**
	 * Get the description for the gist
	 *
	 * @return string
	 */
	public function get_description() {
		return $this->get_excerpt();
	}

	/**
	 * Set the description for the gist
	 *
	 * @param string $description
	 */
	public function set_description( $description ) {
		$this->set_excerpt( $description );
	}

	/**
	 * Get the content of the gist without applying filters
	 *
	 * @return string
	 */
	public function get_content() {
		return $this->get_field( 'post_content' );
	}

	/**
	 * Get the link to edit the gist
	 *
	 * @return string
	 */
	public function get_edit_link() {
		return rtrim( $this->get_permalink(), '/' ) . '/edit/';
	}

}
