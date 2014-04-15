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
			self::$instance->register_routes();
			self::$instance->setup_actions();
			self::$instance->setup_filters();
		}

		return self::$instance;
	}

	/**
	 * Load required files
	 */
	private function require_files() {

		require_once dirname( __FILE__ ) . '/php/class-gist.php';

	}

	/**
	 * Register our custom Timber routes
	 */
	private function register_routes() {

		Timber::add_route( 'gist/add', function( $params ) {
			Timber::load_template( 'add-gist.php', null, 200 );
		} );

		Timber::add_route( 'gist/:id/edit', function( $params ) {
			$query = "p={$params['id']}&post_type=gist";
			Timber::load_template( 'edit-gist.php', $query, 200 );
		} );

	}

	/**
	 * Set up necessary WordPress actions
	 */
	private function setup_actions() {

		add_action( 'init', array( $this, 'action_init_register_post_types' ) );

		add_action( 'init', function(){

			if ( ! is_admin() ) {
				show_admin_bar( false );
			}

		});

		add_action( 'admin_init', function() {
			global $pagenow;

			if ( ! current_user_can( 'manage_options' ) && 'admin-post.php' != $pagenow ) {
				wp_safe_redirect( home_url() );
				exit;
			}

		});

		add_action( 'wp_enqueue_scripts', array( $this, 'action_wp_enqueue_scripts' ) );

		add_action( 'admin_post_add_gist', array( $this, 'handle_add_gist' ) );
		add_action( 'admin_post_edit_gist', array( $this, 'handle_edit_gist' ) );

	}

	/**
	 * Set up necessary WordPress filters
	 */
	private function setup_filters() {

		add_filter( 'timber_context', array( $this, 'filter_timber_context' ) );

	}

	/**
	 * Register our post types
	 */
	public function action_init_register_post_types() {

			register_post_type( 'gist', array(
				'hierarchical'      => false,
				'public'            => true,
				'show_in_nav_menus' => true,
				'show_ui'           => true,
				'supports'          => array( 'title', 'editor', 'revisions' ),
				'has_archive'       => 'gists',
				'query_var'         => true,
				'rewrite'           => array(
					'with_front'    => false,
					'slug'          => 'gist',
					),
				'labels'            => array(
					'name'                => __( 'Gists', 'wpgists' ),
					'singular_name'       => __( 'Gist', 'wpgists' ),
					'all_items'           => __( 'Gists', 'wpgists' ),
					'new_item'            => __( 'New Gist', 'wpgists' ),
					'add_new'             => __( 'Add New', 'wpgists' ),
					'add_new_item'        => __( 'Add New Gist', 'wpgists' ),
					'edit_item'           => __( 'Edit Gist', 'wpgists' ),
					'view_item'           => __( 'View Gist', 'wpgists' ),
					'search_items'        => __( 'Search Gists', 'wpgists' ),
					'not_found'           => __( 'No Gists found', 'wpgists' ),
					'not_found_in_trash'  => __( 'No Gists found in trash', 'wpgists' ),
					'parent_item_colon'   => __( 'Parent Gist', 'wpgists' ),
					'menu_name'           => __( 'Gists', 'wpgists' ),
				),
			) );

	}

	/**
	 * Enqueue our scripts and styles
	 */
	public function action_wp_enqueue_scripts() {

		// Foundation
		wp_enqueue_script( 'foundation', get_stylesheet_directory_uri() . '/lib/foundation/foundation.min.js', array( 'jquery' ), '5.2.2' );
		wp_enqueue_style( 'foundation', get_stylesheet_directory_uri() . '/lib/foundation/foundation.min.css', false, '5.2.2' );

		// Ace Editor
		wp_enqueue_script( 'ace', get_stylesheet_directory_uri() . '/lib/ace/ace.js', false, '04.11.2014' );

		wp_enqueue_script( 'wpgists', get_stylesheet_directory_uri() . '/js/wpgists.js', array( 'jquery' ) );
		wp_enqueue_style( 'wpgists', get_stylesheet_uri() );

	}

	/**
	 * Handle the action to add a new gist
	 */
	public function handle_add_gist() {

		if ( empty( $_POST['content'] ) || empty( $_POST['description'] ) ) {
			wp_safe_redirect( home_url( 'gist/add/' ) );
			exit;
		}

		$gist = \WP_Gists\Gist::create( array( 'post_status' => 'publish' ) );

		$gist->set_description( wp_filter_nohtml_kses( $_POST['description'] ) );
		$gist->set_content( $_POST['content'] ); // No sanitization necessary

		wp_safe_redirect( $gist->get_permalink() );
		exit;
	}

	/**
	 * Handle the action to edit an existing gist
	 */
	public function handle_edit_gist() {

		$gist_id = (int) $_POST['gist-id'];
		$post = get_post( $gist_id );
		if ( ! $post || 'gist' !== $post->post_type ) {
			wp_safe_redirect( home_url() );
			exit;
		}

		$gist = new \WP_Gists\Gist( $gist_id );
		if ( ! current_user_can( 'edit_post', $gist->get_id() ) || ! wp_verify_nonce( $_POST['nonce'], 'edit-gist-' . $gist->get_id() ) ) {
			wp_safe_redirect( $gist->get_permalink() );
			exit;
		}

		if ( empty( $_POST['content'] ) || empty( $_POST['description'] ) ) {
			wp_safe_redirect( $gist->get_edit_link() );
			exit;
		}

		$gist->set_description( wp_filter_nohtml_kses( $_POST['description'] ) );
		$gist->set_content( stripslashes( $_POST['content'] ) ); // No sanitization necessary

		wp_safe_redirect( $gist->get_permalink() );
		exit;
	}

	/**
	 * Filter Timber's context
	 */
	public function filter_timber_context( $context ) {

		$context['is_user_logged_in'] = is_user_logged_in();

		$context['meta_title'] = get_bloginfo( 'name' );
		$context['meta_description'] = get_bloginfo( 'description' );

		return $context;
	}

}

/**
 * Load the theme
 */
function WP_Gists() {
	return WP_Gists::get_instance();
}
add_action( 'after_setup_theme', 'WP_Gists' );
