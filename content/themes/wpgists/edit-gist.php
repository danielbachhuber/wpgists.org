<?php

$context = Timber::get_context();

$context['meta_title'] = __( 'Edit Gist', 'wpgists' ) . ' | ' . get_bloginfo( 'name' );

$gist = new \WP_Gists\Gist( get_queried_object() );

if ( ! current_user_can( 'edit_post', $gist->get_id() ) ) {
	wp_safe_redirect( $gist->get_permalink() );
	exit;
}

$context['gist'] = $gist;
$context['form_action'] = add_query_arg( 'action', 'edit_gist', admin_url( 'admin-post.php' ) );
$context['nonce'] = wp_create_nonce( 'edit-gist-' . $gist->get_id() );

Timber::render( 'edit-gist.twig', $context );
