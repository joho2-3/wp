<?php
/**
 * Add custom header.
 * @link		http://welcustom.net/
 * @author		Mamekko
 * @copyright	Copyright (c) 2015 welcustom.net
 */

function blanc_custom_header_setup(){
	$args = array(
		'width' => 1200,
		'height' => 400,
		'header-text' => false,
		'flex-height' => true,
		'flex-width' => true,
	);
	add_theme_support( 'custom-header', $args );
}
add_action( 'after_setup_theme', 'blanc_custom_header_setup' );