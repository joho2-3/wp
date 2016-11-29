<?php
/**
 * Paygent Payment Gateway
 *
 * Provides a Paygent Convenience Store Payment Gateway.
 * Admin Page control
 *
 * @class 		WC_Admin_Screen_Paygent
 * @version		1.2.0
 * @author		Artisan Workshop
 */

global $_SESSION;
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class WC_Admin_Screen_Paygent {

	/**
	 * Constructor
	 */
	public function __construct() {
		add_action( 'admin_menu', array( $this, 'wc_admin_paygent_menu' ) ,55 );
		add_action( 'admin_notices', array( $this, 'paygent_file_check' ) );
		add_action( 'admin_notices', array( $this, 'paygent_ssl_check' ) );
		add_action( 'admin_init', array( $this, 'wc_setting_paygent_init') );
	}
	/**
	 * Admin Menu
	 */
	public function wc_admin_paygent_menu() {
		$page = add_submenu_page( 'woocommerce', __( 'Paygent Setting', 'woocommerce-for-paygent-payment-main' ), __( 'Paygent Setting', 'woocommerce-for-paygent-payment-main' ), 'manage_woocommerce', 'wc4jp-paygent-output', array( $this, 'wc_paygent_output' ) );
	}

	/**
	 * Admin Screen output
	 */
	public function wc_paygent_output() {
		$tab = ! empty( $_GET['tab'] ) && $_GET['tab'] == 'info' ? 'info' : 'setting';
		include( 'views/html-admin-screen.php' );
	}

	/**
	 * Admin page for Setting
	 */
	public function admin_paygent_setting_page() {
		include( 'views/html-admin-setting-screen.php' );
	}

	/**
	 * Admin page for infomation
	 */
	public function admin_paygent_info_page() {
		include( 'views/html-admin-info-screen.php' );
	}
	
      /**
       * Check require files set in this site and notify the user.
       */
	public function paygent_file_check(){
       // * Check if Client Cert file and CA Cert file and notify the user.
		if (!file_exists(CLIENT_FILE_PATH) or !file_exists(CA_FILE_PATH)){
			if(!file_exists(CLIENT_FILE_PATH)) $cilent_msg = __('Client Cert File do not exist. ', 'woocommerce-for-paygent-payment-main' );
			if(!file_exists(CA_FILE_PATH)) $ca_msg = __('CA Cert File do not exist. ', 'woocommerce-for-paygent-payment-main' );
			echo '<div class="error"><ul><li>' . __('Paygent Cert File do not exist. Please put Cert files.', 'woocommerce-for-paygent-payment-main' ) .$cilent_msg.$ca_msg. '</li></ul></div>';
		}
       // * Check if Client Cert file and CA Cert file uploaded files is fault.
		if (isset($this->pem_error_message) or isset($this->crt_error_message)){
			if($this->pem_error_message) $cilent_msg = $this->pem_error_message;
			if($this->crt_error_message) $ca_msg = $this->crt_error_message;
			echo '<div class="error"><ul><li>' . __('Mistake your uploaded file.', 'woocommerce-for-paygent-payment-main' ) .$cilent_msg.$ca_msg. '</li></ul></div>';
		}
	}

      /**
       * Check if SSL is enabled and notify the user.
       */
      function paygent_ssl_check() {
		  if(isset($this->enabled)){
              if ( get_option( 'woocommerce_force_ssl_checkout' ) == 'no' && $this->enabled == 'yes' ) {
              echo '<div class="error"><p>' . sprintf( __('Paygent Commerce is enabled and the <a href="%s">force SSL option</a> is disabled; your checkout is not secure! Please enable SSL and ensure your server has a valid SSL certificate.', 'woocommerce-for-paygent-payment-main' ), admin_url( 'admin.php?page=wc-settings&tab=checkout' ) ) . '</p></div>';
            }
		  }
	  }

	function wc_setting_paygent_init(){
		global $woocommerce;

		if( isset( $_POST['wc-paygent-setting'] ) && $_POST['wc-paygent-setting'] ){
			if( check_admin_referer( 'my-nonce-key', 'wc-paygent-setting')){
				$paygent_auths = array('mid','cid','cpass');
				foreach($paygent_auths as $paygent_auth){
					$post_auth = 'paygent_'.$paygent_auth;
					$option_auth = 'wc-paygent-'.$paygent_auth;
					if(isset($_POST[$post_auth]) && $_POST[$post_auth]){
						update_option( $option_auth, $_POST[$post_auth]);
					}else{
						update_option( $option_auth, '');
					}
					$post_test_auth = 'paygent_test_'.$paygent_auth;
					$option_test_auth = 'wc-paygent-test-'.$paygent_auth;
					if(isset($_POST[$post_test_auth]) && $_POST[$post_test_auth]){
						update_option( $option_test_auth, $_POST[$post_test_auth]);
					}else{
						update_option( $option_test_auth, '');
					}
				}
				//Site ID Setting
				if(isset($_POST['paygent_sid']) && $_POST['paygent_sid']){
					update_option( 'wc-paygent-sid', $_POST['paygent_sid']);
				}
				//Client Cert File upload
				if(substr($_FILES["clientc_file"]["name"], strrpos($_FILES["clientc_file"]["name"], '.') + 1)=='pem'){
					if (move_uploaded_file($_FILES["clientc_file"]["tmp_name"], WP_CONTENT_DIR.'/uploads/wc-paygent/client_cert.pem')) {
					    chmod(WP_CONTENT_DIR.'/uploads/wc-paygent/client_cert.pem' , 0644);
					} else {
						//error_log
					}
				}else{
					if($_FILES["clientc_file"]["name"]){
					//error_message
						$this->pem_error_message = __('Uploaded flie is not Client Cert File. Please check .pem file.', 'woocommerce-for-paygent-payment-main' );
					}
				}
				//Test Client Cert File upload
				if(substr($_FILES["clientc_test_file"]["name"], strrpos($_FILES["clientc_test_file"]["name"], '.') + 1)=='pem'){
					if (move_uploaded_file($_FILES["clientc_test_file"]["tmp_name"], WP_CONTENT_DIR.'/uploads/wc-paygent/test_client_cert.pem')) {
					    chmod(WP_CONTENT_DIR.'/uploads/wc-paygent/test_client_cert.pem' , 0644);
					} else {
						//error_log
					}
				}else{
					if($_FILES["clientc_test_file"]["name"]){
					//error_message
						$this->pem_error_message = __('Uploaded flie is not Test Client Cert File. Please check .pem file.', 'woocommerce-for-paygent-payment-main' );
					}
				}
				//CA Cert File upload
				if(substr($_FILES["cac_file"]["name"], strrpos($_FILES["cac_file"]["name"], '.') + 1)=='crt'){
					if (move_uploaded_file($_FILES["cac_file"]["tmp_name"], WP_CONTENT_DIR.'/uploads/wc-paygent/curl-ca-bundle.crt')) {
					    chmod(WP_CONTENT_DIR.'/uploads/wc-paygent/curl-ca-bundle.crt', 0644);
					} else {
						//error_log
					}
				}else{
					if($_FILES["cac_file"]["name"]){
					//error_message
						$this->crt_error_message = __('Uploaded flie is not CA Cert File. Please check .crt file.', 'woocommerce-for-paygent-payment-main' );
					}
				}
				$paygent_methods = array('cc','cs','mccc','bn','atm', 'mb');//cc: Credit Card,cs: Convenience store, mccc: Multi-currency Credit Card, bn: Bank Net, atm: ATM 
				foreach($paygent_methods as $paygent_method){
					$option_method = 'wc-paygent-'.$paygent_method;
					$post_paygent = 'paygent_'.$paygent_method;
					$setting_method = 'woocommerce_paygent_'.$paygent_method.'_settings';

					$woocommerce_paygent_setting = get_option($setting_method);
					if(isset($_POST[$post_paygent]) && $_POST[$post_paygent]){
						update_option( $option_method, $_POST[$post_paygent]);
						if(isset($woocommerce_paygent_setting)){
							$woocommerce_paygent_setting['enabled'] = 'yes';
							update_option( $setting_method, $woocommerce_paygent_setting);
						}
					}else{
						update_option( $option_method, '');
						if(isset($woocommerce_paygent_setting)){
							$woocommerce_paygent_setting['enabled'] = 'no';
							update_option( $setting_method, $woocommerce_paygent_setting);
						}
					}
				}
				//Test Mode Setting
				if(isset($_POST['paygent_testmode']) && $_POST['paygent_testmode']){
					update_option( 'wc-paygent-testmode', $_POST['paygent_testmode']);
				}else{
					update_option( 'wc-paygent-testmode', '');
				}
			}
		}elseif( isset( $_POST['check-test-sha2'] ) && $_POST['check-test-sha2'] ){
			if( check_admin_referer( 'my-nonce-key', 'check-test-sha2')){
				if(isset($_POST['check-test']) && $_POST['check-test']){
					include_once(ABSPATH.'/wp-load.php');
					include_once(plugin_dir_path( __FILE__ ).'gateways/paygent/includes/class-wc-gateway-paygent-request.php');
					$telegram_kind = '020';
					$send_data['trading_id'] = 'wc_test';
					$send_data['payment_id'] = '';

					$send_data['payment_amount'] = '100';
					//Test Credit Card Infomation
					$send_data['card_number'] = '4023123456780000';
					$send_data['card_valid_term'] = '1224';
					//Test Payment times
					$send_data['payment_class'] = 10;//One time payment
					$send_data['3dsecure_ryaku'] = 1;

					$paygent_check = new WC_Gateway_Paygent_Request('paygent_cc');
					$_SESSION['response'] = null;
					$order = null;
					$_SESSION['response'] = $paygent_check->send_paygent_request('connect_test', $order, $telegram_kind, $send_data);
					add_action( 'admin_notices', 'my_admin_error_notice' ); 
				}
			}
		}
	}
}
	function my_admin_error_notice() {
		$response = $_SESSION['response'];
		if(isset($response)){
		if( $response['result'] == 0 and $response['result_array']){
			$message = __( 'Success SHA-2 Check.', 'woocommerce-for-paygent-payment-main' );
			$class = "updated";
		} elseif ( $response['result'] == 1 ){
			$message = __( 'Failed Check Test. Error ID : ', 'woocommerce-for-paygent-payment-main' ). $response['responseCode'] .':'. mb_convert_encoding($response['responseDetail'],"UTF-8","SJIS" );
			$class = "error";
		}else{
			$message = __( 'Failed Check Test.', 'woocommerce-for-paygent-payment-main' );
			$class = "error";
		}
		echo "<br /><div class=\"$class\"> <p>$message</p></div>";
		}
	}

new WC_Admin_Screen_Paygent();