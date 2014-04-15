<?php

$context = Timber::get_context();

$posts = Timber::get_posts();
$context['gists'] = array_map( function( $post ){
	return new \WP_Gists\Gist( $post );
}, $posts );

Timber::render( 'home.twig', $context );
