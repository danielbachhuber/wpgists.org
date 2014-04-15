<?php

$context = Timber::get_context();

$context['gist'] = new \WP_Gists\Gist( get_queried_object_id() );

Timber::render( 'single-gist.twig', $context );
