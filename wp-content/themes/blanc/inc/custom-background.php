<?php
/**
 * Add custom background.
 * @link		http://welcustom.net/
 * @author		Mamekko
 * @copyright	Copyright (c) 2015 welcustom.net
 */

function blanc_custom_background_setup(){
	$args = array(
		'default-color' => 'fff',
	);
	add_theme_support( 'custom-background', $args );
}
add_action( 'after_setup_theme', 'blanc_custom_background_setup' );