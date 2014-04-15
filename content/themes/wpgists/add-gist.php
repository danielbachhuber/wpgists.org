<?php

$context = Timber::get_context();

$context['meta_title'] = __( 'Add Gist', 'wpgists' ) . ' | ' . get_bloginfo( 'name' );

$context['form_action'] = add_query_arg( 'action', 'add_gist', admin_url( 'admin-post.php' ) );

Timber::render( 'add-gist.twig', $context );
