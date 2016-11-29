<?php
/*
Plugin Name: CC Addon Pack
Description: This plug-in is an integrated plug-in with a variety of features that make it powerful your web site. Example Print OG Tags,Print Twitter Card Tags,Print Google Analytics tag and more!
Version: 1.0.2
Author: Communitycom
Author URI: http://www.communitycom.jp/
License: GPL2
*/
$data = get_file_data( __FILE__, array( 'version' => 'Version' ) );
global $ccAddonPack_version;
$ccAddonPack_version = $data['version'];

load_plugin_textdomain( 'cc-addon-pack', false, plugin_basename( dirname( __FILE__ ) ) . '/languages' );

function ccAddonPack_get_directory( $path = '' ) {
	return dirname( __FILE__ ) . $path;
}

function ccAddonPack_get_directory_uri( $path = '' ) {
	return plugins_url( $path , __FILE__ );
}

require ccAddonPack_get_directory('/admin/common.php');

require ccAddonPack_get_directory('/includes/cc-recent-widget.php');
require ccAddonPack_get_directory('/includes/cc-footernavi-widget.php');
require ccAddonPack_get_directory('/includes/cc-topicarea-widget.php');
require ccAddonPack_get_directory('/includes/cc-contact-widget.php');
require ccAddonPack_get_directory('/includes/cc-sns-widget.php');
require ccAddonPack_get_directory('/includes/cc-functions.php');


function ccAddonPack_add_menu() {

	$hook = add_menu_page(
				'CC Addon Pack',
				'CC Addon Pack',
				'activate_plugins',
				'cc-adpack-setting',
				'ccAddonPack_add_setting'
			);

	add_action( 'admin_print_scripts-'.$hook, 'ccAddonPack_admin_print_scripts' );

}
add_action( 'admin_menu', 'ccAddonPack_add_menu' );

function ccAddonPack_admin_print_scripts() {
	global $ccAddonPack_version;

	wp_enqueue_style( 'ccAddonPack-admin-bootstrap-style', ccAddonPack_get_directory_uri('/css/bootstrap.min.css'), array(), $ccAddonPack_version );
	wp_enqueue_style( 'ccAddonPack-common-style', ccAddonPack_get_directory_uri('/css/common.css'), array(), $ccAddonPack_version );

	wp_enqueue_script( 'ccAddonPack-admin-bootstrap-js', ccAddonPack_get_directory_uri('/js/bootstrap.min.js'), array( 'jquery' ), $ccAddonPack_version, true );

	if ( function_exists( 'wp_enqueue_media' ) ) {
		wp_enqueue_media();
		wp_enqueue_script( 'ccAddonPack-common-script', ccAddonPack_get_directory_uri('/js/common.js'), array( 'jquery' ), $ccAddonPack_version, true );
	}
}

function ccAddonPack_add_setting() {
	require ccAddonPack_get_directory('/admin/admin.php');
}

function ccAddonPack_print_css() {
	global $ccAddonPack_version;
	$options = ccAddonPack_get_option();

	if ( isset( $options['active_bootstrap'] ) && $options['active_bootstrap'] ) {
		wp_enqueue_style( 'ccAddonPack-bootstrap-style', ccAddonPack_get_directory_uri('/css/bootstrap.min.css'), array(), $ccAddonPack_version );
	}
	if ( isset( $options['active_fontawesome'] ) && $options['active_fontawesome'] ) {
		wp_enqueue_style( 'font-awesome', 'https://maxcdn.bootstrapcdn.com/font-awesome/4.6.1/css/font-awesome.min.css', array(), $ccAddonPack_version );
	}
}
add_action( 'wp_enqueue_scripts','ccAddonPack_print_css' );


function ccAddonPack_installer() {
	$option = get_option( 'ccAddonPack_options' );
	if ( !$option ) {
		add_option( 'ccAddonPack_options', ccAddonPack_get_options_default() );
	}
}
if ( function_exists( 'register_activation_hook' ) ) {
	register_activation_hook( __FILE__ , 'ccAddonPack_installer' );
}