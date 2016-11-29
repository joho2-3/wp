<?php
if (!defined('WP_UNINSTALL_PLUGIN')) {
    exit;
}

$delete_options = array(
	'ccAddonPack_options',
);

foreach ( $delete_options as $opt_name ) {
	delete_option( $opt_name );
}
