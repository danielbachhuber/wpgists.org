<?php

$mu_plugins_to_load = array(
	'timber/timber.php',
	'wordpress-objects/wordpress-objects.php',
	);

foreach( $mu_plugins_to_load as $mu_plugin_to_load ) {
	require_once dirname( __FILE__ ) . '/' . $mu_plugin_to_load;
}
