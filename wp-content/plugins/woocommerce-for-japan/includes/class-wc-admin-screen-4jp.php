<?php
/**
 * Plugin Name: WooCommerce For Japan

 * @author 		ArtisanWorkshop
 * @package 	Admin Screen
 * @version     1.0.11
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class WC_4JP_Admin_Screen {

	/**
	 * Constructor
	 */
	public function __construct() {
		add_action( 'admin_menu', array( $this, 'wc4jp_admin_menu' ) ,60 );
		add_action( 'admin_init', array( $this, 'wc4jp_setting_init') );
	}
	/**
	 * Admin Menu
	 */
	public function wc4jp_admin_menu() {
		$page = add_submenu_page( 'woocommerce', __( 'For Japanese', 'woocommerce-for-japan' ), __( 'For Japanese', 'woocommerce-for-japan' ), 'manage_woocommerce', 'wc4jp-output', array( $this, 'wc4jp_output' ) );
	}

	/**
	 * Admin Screen output
	 */
	public function wc4jp_output() {
		$tab = ! empty( $_GET['tab'] ) && $_GET['tab'] == 'info' ? 'info' : 'setting';
		include( 'views/html-admin-screen.php' );
	}

	/**
	 * Admin page for Setting
	 */
	public function admin_setting_page() {
		include( 'views/html-admin-setting-screen.php' );
	}

	/**
	 * Admin page for infomation
	 */
	public function admin_info_page() {
		include( 'views/html-admin-info-screen.php' );
	}
	
	function wc4jp_setting_init(){
		if( isset( $_POST['wc4jp-setting'] ) ){
			if( check_admin_referer( 'my-nonce-key', 'wc4jp-setting')){
				$add_methods = array('yomigana', 'company-name', 'free-shipping');
				foreach($add_methods as $add_method){
					if(isset($_POST[$add_method]) && $_POST[$add_method]){
						update_option( 'wc4jp-'.$add_method, $_POST[$add_method]);
					}else{
						update_option( 'wc4jp-'.$add_method, '');
					}
				}
				$payment_methods = array('bankjp','postofficebank','atstore');
				foreach($payment_methods as $payment_method){
					$woocommerce_settings = get_option('woocommerce_'.$payment_method.'_settings');
					if(isset($_POST[$payment_method]) && $_POST[$payment_method]){
						update_option( 'wc4jp-'.$payment_method, $_POST[$payment_method]);
						if(isset($woocommerce_settings)){
							$woocommerce_settings['enabled'] = 'yes';
							update_option( 'woocommerce_'.$payment_method.'_settings', $woocommerce_settings);
						}
					}else{
						update_option( 'wc4jp-'.$payment_method, '');
						if(isset($woocommerce_settings)){
							$woocommerce_settings['enabled'] = 'no';
							update_option( 'woocommerce_'.$payment_method.'_settings', $woocommerce_settings);
						}
					}
				}
			}
		}
	}
}

new WC_4JP_Admin_Screen();