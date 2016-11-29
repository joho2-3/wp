<?php
/**
 * Plugin Name: WooCommerce For Paygent Payment Gateways
 * Plugin URI: https://wordpress.org/plugins/woocommerce-for-paygent-payment-main/
 * Description: WooCommerce For Paygent Payment Gateways 
 * Version: 1.1.5
 * Author: Artisan Workshop
 * Author URI: https://wc.artws.info/
 * Requires at least: 4.0
 * Tested up to: 4.5.3
 *
 * Text Domain: woocommerce-for-paygent-payment-main
 * Domain Path: /i18n/
 *
 * @package woocommerce-for-paygent-payment-main
 * @category Core
 * @author Artisan Workshop
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! class_exists( 'WooCommercePaygentMain2' ) ) :

/**
 * Load plugin functions.
 */
add_action( 'plugins_loaded', 'WooCommercePaygentMain2_plugin', 0 );
register_activation_hook( __FILE__, array( 'WooCommercePaygentMain2', 'plugin_activation' ) );

class WooCommercePaygentMain2{

	/**
	 * WooCommerce Constructor.
	 * @access public
	 * @return WooCommerce
	 */
	public function __construct() {
		// Include required files
		$this->includes();
		$this->init();
	}
	/**
	 * Include required core files used in admin and on the frontend.
	 */
	private function includes() {
		// Module
		define('CLIENT_TEST_FILE_PATH', WP_CONTENT_DIR.'/uploads/wc-paygent/test_client_cert.pem');
		define('CLIENT_FILE_PATH', WP_CONTENT_DIR.'/uploads/wc-paygent/client_cert.pem');
//		define('CA_TEST_FILE_PATH', plugin_dir_path( __FILE__ ).'/includes/gateways/paygent/asset/curl-ca-test-bundle.crt');
		define('CA_FILE_PATH', WP_CONTENT_DIR.'/uploads/wc-paygent/curl-ca-bundle.crt');
		define('WC_PAYGENT_PLUGIN_PATH',plugin_dir_path( __FILE__ ));
		define('PAYGENT_TIMEOUT_VALUE', 35);//Time out value
		define('PAYGENT_DEBUG_FLG', 0);//Debug Option
		define('PAYGENT_SELCET_MAX_CNT', 2000);//Maximum query Count upto 2000
		define('PAYGENT_TELEGRAM_KIND_REF', '027,090');//Telegram kind reffrence
		include_once('jp/co/ks/merchanttool/connectmodule/entity/ResponseDataFactory.php');
		include_once('jp/co/ks/merchanttool/connectmodule/system/PaygentB2BModule.php');

		include_once('jp/co/ks/merchanttool/connectmodule/exception/PaygentB2BModuleConnectException.php');
		include_once('jp/co/ks/merchanttool/connectmodule/exception/PaygentB2BModuleException.php');

		// 2 Main Payment Gateway
		if(get_option('wc-paygent-cc')){
			include_once( plugin_dir_path( __FILE__ ).'/includes/gateways/paygent/class-wc-gateway-paygent-cc.php' );	// Credit Card
			include_once( plugin_dir_path( __FILE__ ).'/includes/gateways/paygent/class-wc-gateway-paygent-addon-cc.php' );	// Credit Card Addon
		}
		if(get_option('wc-paygent-cs')) include_once( plugin_dir_path( __FILE__ ).'/includes/gateways/paygent/class-wc-gateway-paygent-cs.php' );	// Convenience store
		if(get_option('wc-paygent-mccc')) include_once( plugin_dir_path( __FILE__ ).'/includes/gateways/paygent/class-wc-gateway-paygent-mccc.php' );	// Multi-currency Credit Card
		if(get_option('wc-paygent-bn')) include_once( plugin_dir_path( __FILE__ ).'/includes/gateways/paygent/class-wc-gateway-paygent-bn.php' );	// Bank Net
		if(get_option('wc-paygent-atm')) include_once( plugin_dir_path( __FILE__ ).'/includes/gateways/paygent/class-wc-gateway-paygent-atm.php' );	// ATM Payment
		if(get_option('wc-paygent-mb')) include_once( plugin_dir_path( __FILE__ ).'/includes/gateways/paygent/class-wc-gateway-paygent-mb.php' );	// Carrier Payment

		// Admin Setting Screen 
		include_once( plugin_dir_path( __FILE__ ).'/includes/class-wc-admin-screen-paygent.php' );
	}
	/**
	 * Init WooCommerce when WordPress Initialises.
	 */
	public function init() {
		// Set up localisation
		$this->load_plugin_textdomain();
	}

	/*
	 * Load Localisation files.
	 *
	 * Note: the first-loaded translation file overrides any following ones if the same translation is present
	 */
	public function load_plugin_textdomain() {
		$locale = apply_filters( 'plugin_locale', get_locale(), 'woocommerce-for-paygent-payment-main' );
		// Global + Frontend Locale
		load_plugin_textdomain( 'woocommerce-for-paygent-payment-main', false, plugin_basename( dirname( __FILE__ ) ) . "/i18n" );
	}
	public function plugin_activation(){
		$wc_paybent_dir = WP_CONTENT_DIR.'/uploads/wc-paygent';
		if( !is_dir( $wc_paybent_dir ) ){
		mkdir($wc_paybent_dir, 0755);
		}
	}
}

endif;
//If WooCommerce Plugins is not activate notice
function wcPaygentMain2_fallback_notice(){
	?>
    <div class="error">
        <ul>
            <li><?php echo __( 'WooCommerce for Paygent Main 2 method is enabled but not effective. It requires WooCommerce in order to work.', 'woocommerce-for-paygent-payment-main' );?></li>
        </ul>
    </div>
    <?php
}
function WooCommercePaygentMain2_plugin() {
    if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
        $wcPaygentMain2 = new WooCommercePaygentMain2();
    } else {
        add_action( 'admin_notices', 'wcPaygentMain2_fallback_notice' );
    }
}
