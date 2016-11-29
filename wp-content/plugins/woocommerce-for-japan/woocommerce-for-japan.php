<?php
/**
 * Plugin Name: WooCommerce For Japan
 * Plugin URI: http://wordpress.org/plugins/woocommerce-for-japan/
 * Description: Woocommerce toolkit for Japanese use.
 * Version: 1.1.2
 * Author: Artisan Workshop
 * Author URI: http://wc.artws.info/
 * Requires at least: 4.1.0
 * Tested up to: 4.5.1
 *
 * Text Domain: woocommerce-for-japan
 * Domain Path: /i18n/
 *
 * @package woocommerce-for-japan
 * @category Core
 * @author Artisan Workshop
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! class_exists( 'WooCommerce4jp' ) ) :

class WooCommerce4jp{

	/**
	 * WooCommerce Constructor.
	 * @access public
	 * @return WooCommerce
	 */
	public function __construct() {
		// Include required files
		$this->includes();
		$this->init();
		// change paypal bn for japan
		add_filter( 'woocommerce_paypal_args',array( &$this,  'wc4jp_paypal_bn'));
	}
	/**
	 * Include required core files used in admin and on the frontend.
	 */
	private function includes() {
		// Payment Gateway For Bank
		if(get_option('wc4jp-bankjp')) include_once( 'includes/gateways/bank-jp/class-wc-gateway-bank-jp.php' );
		// Payment Gateway For Post Office Bank
		if(get_option('wc4jp-postofficebank')) include_once( 'includes/gateways/postofficebank/class-wc-gateway-postofficebank-jp.php' );
		// Payment Gateway at Real Store
		if(get_option('wc4jp-atstore')) include_once( 'includes/gateways/atstore/class-wc-gateway-atstore-jp.php' );
		// Address field
		include_once( 'includes/class-wc-address-field-4jp.php' );
		// Admin Setting Screen
		include_once( 'includes/class-wc-admin-screen-4jp.php' );
		// ADD COD Fee 
		include_once( 'includes/class-wc-cod-fee-4jp.php' );
		// Add Free Shipping display
		if(get_option('wc4jp-free-shipping')) include_once( 'includes/class-wc-free-shipping-4jp.php' );
	}
	/**
	 * Init WooCommerce when WordPress Initialises.
	 */
	public function init() {
		// Set up localisation
		$this->load_plugin_textdomain();
		// Address Fields Class load
		new AddressField4jp();
		// ADD COD Fee  Class load
		new WooCommerce_Cod_Fee();
	}
	/*
	 * Load Localisation files.
	 *
	 * Note: the first-loaded translation file overrides any following ones if the same translation is present
	 */
	public function load_plugin_textdomain() {
		$locale = apply_filters( 'plugin_locale', get_locale(), 'woocommerce-for-japan' );
		// Global + Frontend Locale
		load_plugin_textdomain( 'woocommerce-for-japan', false, plugin_basename( dirname( __FILE__ ) ) . "/i18n" );
	}
	/**
	 * Init WooCommerce when WordPress Initialises.
	 */
	public function wc4jp_paypal_bn( $fields, $order) {
		$fields['bn'] = 'ArtisanWorkshop_Cart_WPS_JP';
		return $fields;
	}

}

endif;

/**
 * Load plugin functions.
 */
add_action( 'plugins_loaded', 'WooCommerce4jp_plugin');

function wc4jp_fallback_notice() {
	?>
    <div class="error">
        <ul>
            <li><?php echo __( 'WooCommerce for Japanese is enabled but not effective. It requires WooCommerce in order to work.', 'woocommerce-for-japan' );?></li>
        </ul>
    </div>
    <?php
}
/**
 * WC Detection
 */
if ( ! function_exists( 'is_woocommerce_active' ) ) {
	function is_woocommerce_active() {
		if ( ! isset($active_plugins) ) {
			$active_plugins = (array) get_option( 'active_plugins', array() );

			if ( is_multisite() )
				$active_plugins = array_merge( $active_plugins, get_site_option( 'active_sitewide_plugins', array() ) );
		}
		return in_array( 'woocommerce/woocommerce.php', $active_plugins ) || array_key_exists( 'woocommerce/woocommerce.php',$active_plugins );
	}
}

function WooCommerce4jp_plugin() {
    if ( is_woocommerce_active() ) {
        new WooCommerce4jp();
        $postoffice_setting = get_option('woocommerce_postofficebankjp_settings');
        if(!empty($postoffice_setting)){
	        update_option( 'woocommerce_postofficebank_settings', $postoffice_setting);
	        delete_option( 'woocommerce_postofficebankjp_settings' );
        }
    } else {
        add_action( 'admin_notices', 'wc4jp_fallback_notice' );
    }
}
