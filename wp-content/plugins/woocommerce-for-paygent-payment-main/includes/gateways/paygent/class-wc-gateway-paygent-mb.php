<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Paygent Payment Gateway
 *
 * Provides a Paygent Carrier Payment Gateway.
 *
 * @class 		WC_Paygent
 * @extends		WC_Gateway_Paygent_Carrier
 * @version		1.2.0
 * @package		WooCommerce/Classes/Payment
 * @author		Artisan Workshop
 */
class WC_Gateway_Paygent_Carrier extends WC_Payment_Gateway {

	/**
	 * Constructor for the gateway.
	 *
	 * @access public
	 * @return void
	 */
	public function __construct() {

		$this->id                = 'paygent_mb';
		$this->has_fields        = false;
		$this->order_button_text = sprintf(__( 'Proceed to Paygent %s', 'woocommerce-for-paygent-payment-main' ), __('Carrier Payment', 'woocommerce-for-paygent-payment-main' ));
		
        // Create plugin fields and settings
		$this->init_form_fields();
		$this->init_settings();
		$this->method_title       = sprintf(__( 'Paygent %s Gateway', 'woocommerce-for-paygent-payment-main' ), __('Carrier Payment', 'woocommerce-for-paygent-payment-main' ));
		$this->method_description = sprintf(__( 'Allows payments by Paygent %s in Japan.', 'woocommerce-for-paygent-payment-main' ), __('Carrier Payment', 'woocommerce-for-paygent-payment-main' ));
		$this->supports = array(
			'products',
			'refunds',
		);
        // When no save setting error at chackout page
		if(is_null($this->title)){
			$this->title = __( 'Please set this payment at Control Panel! ', 'woocommerce-for-paygent-payment-main' ).$this->method_title;
		}
		// Get setting values
		foreach ( $this->settings as $key => $val ) $this->$key = $val;


        // Set Carrier type
		$this->carrier_types = array();
		if(isset($this->setting_ct_02)){
			if($this->setting_ct_02 =='yes') $this->carrier_types = array_merge($this->carrier_types, array('02' => __( 'Matomete au Payment', 'woocommerce-for-paygent-payment-main' )));
			if($this->setting_ct_03 =='yes') $this->carrier_types = array_merge($this->carrier_types, array('03' => __( 'S! Matomete Payment', 'woocommerce-for-paygent-payment-main' )));
			if($this->setting_ct_04 =='yes') $this->carrier_types = array_merge($this->carrier_types, array('04' => __( 'au Easy Payment', 'woocommerce-for-paygent-payment-main' )));
			if($this->setting_ct_05 =='yes') $this->carrier_types = array_merge($this->carrier_types, array('05' => __( 'Docomo Payment', 'woocommerce-for-paygent-payment-main' )));
			if($this->setting_ct_06 =='yes') $this->carrier_types = array_merge($this->carrier_types, array('06' => __( 'SoftBank Matomete Payment(B)', 'woocommerce-for-paygent-payment-main' )));
			if($this->setting_ct_07 =='yes') $this->carrier_types = array_merge($this->carrier_types, array('07' => __( 'SoftBank Matomete Payment(A)', 'woocommerce-for-paygent-payment-main' )));
		}
		// Actions
		add_action( 'woocommerce_receipt_' . $this->id,                         array( $this, 'receipt_page' ) );
		add_action( 'woocommerce_update_options_payment_gateways',              array( $this, 'process_admin_options' ) );
		add_action( 'woocommerce_update_options_payment_gateways_' . $this->id, array( $this, 'process_admin_options' ) );
		add_action( 'woocommerce_thankyou_' . $this->id, array( $this, 'thankyou_payment_form' ) );
//		if( version_compare( WOOCOMMERCE_VERSION, '2.6.0', '<' ) ){
//		}
	}
	/**
	* Initialize Gateway Settings Form Fields.
	*/
	function init_form_fields() {

		$this->form_fields = array(
			'enabled'     => array(
				'title'       => __( 'Enable/Disable', 'woocommerce-for-paygent-payment-main' ),
				'label'       => sprintf(__( 'Enable paygent %s Payment', 'woocommerce-for-paygent-payment-main' ), __('Carrier Payment', 'woocommerce-for-paygent-payment-main' )),
				'type'        => 'checkbox',
				'description' => '',
				'default'     => 'no'
			),
			'title'       => array(
				'title'       => __( 'Title', 'woocommerce-for-paygent-payment-main' ),
				'type'        => 'text',
				'description' => __( 'This controls the title which the user sees during checkout.', 'woocommerce-for-paygent-payment-main' ),
				'default'     => __( 'Carrier Payment (Paygent)', 'woocommerce-for-paygent-payment-main' )
			),
			'description' => array(
				'title'       => __( 'Description', 'woocommerce-for-paygent-payment-main' ),
				'type'        => 'textarea',
				'description' => __( 'This controls the description which the user sees during checkout.', 'woocommerce-for-paygent-payment-main' ),
				'default'     => sprintf(__( 'Pay with your %s via Paygent.', 'woocommerce-for-paygent-payment-main' ), __('Carrier Payment', 'woocommerce-for-paygent-payment-main' )),
			),
			'order_button_text' => array(
				'title'       => __( 'Order Button Text', 'woocommerce-for-paygent-payment-main' ),
				'type'        => 'textarea',
				'description' => __( 'This controls the description which the user sees during checkout.', 'woocommerce-for-paygent-payment-main' ),
				'default'     => sprintf(__( 'Proceed to Paygent %s', 'woocommerce-for-paygent-payment-main' ), __('Carrier Payment', 'woocommerce-for-paygent-payment-main' )),
			),
			'payment_detail' => array(
				'title'       => __( 'Invoice detail', 'woocommerce-for-paygent-payment-main' ),
				'type'        => 'text',
				'description' => __( 'This controls the text which the detail at Carrier Payment.', 'woocommerce-for-paygent-payment-main' ),
				'default'     => sprintf(__( 'Shop Title Via %s', 'woocommerce-for-paygent-payment-main' ), __('Carrier Payment', 'woocommerce-for-paygent-payment-main' )),
			),
			'payment_detail_kana' => array(
				'title'       => __( 'Invoice detail (kana)', 'woocommerce-for-paygent-payment-main' ),
				'type'        => 'text',
				'description' => __( 'This controls the text which the detail at ATM.(Kana)', 'woocommerce-for-paygent-payment-main' ),
				'default'     => sprintf(__( 'Shop Title Via %s (kana)', 'woocommerce-for-paygent-payment-main' ), __('Carrier Payment', 'woocommerce-for-paygent-payment-main' )),
			),
			'auth_url' => array(
				'title'       => __( 'Auth URL (Softbank(A) only)', 'woocommerce-for-paygent-payment-main' ),
				'id'              => 'wc-paygent-auth-url',
				'type'        => 'text',
				'description' => __( 'Set Auth URL which setting SoftBank Mobile service.', 'woocommerce-for-paygent-payment-main' ),
			),
			'setting_ct_02' => array(
				'title'       => __( 'Set Carrier type', 'woocommerce-for-paygent-payment-main' ),
				'id'              => 'wc-paygent-ct-02',
				'type'        => 'checkbox',
				'label'       => __( 'Matomete au Payment', 'woocommerce-for-paygent-payment-main' ),
				'default'     => 'yes',
			),
			'setting_ct_03' => array(
				'id'              => 'wc-paygent-ct-03',
				'type'        => 'checkbox',
				'label'       => __( 'S! Matomete Payment', 'woocommerce-for-paygent-payment-main' ),
				'default'     => 'yes',
			),
			'setting_ct_04' => array(
				'id'              => 'wc-paygent-ct-04',
				'type'        => 'checkbox',
				'label'       => __( 'au Easy Payment', 'woocommerce-for-paygent-payment-main' ),
				'default'     => 'yes',
			),
			'setting_ct_05' => array(
				'id'              => 'wc-paygent-ct-05',
				'type'        => 'checkbox',
				'label'       => __( 'Docomo Payment', 'woocommerce-for-paygent-payment-main' ),
				'default'     => 'yes',
			),
			'setting_ct_06' => array(
				'id'              => 'wc-paygent-ct-06',
				'type'        => 'checkbox',
				'label'       => __( 'SoftBank Matomete Payment(B)', 'woocommerce-for-paygent-payment-main' ),
				'default'     => 'yes',
			),
			'setting_ct_07' => array(
				'id'              => 'wc-paygent-ct-07',
				'type'        => 'checkbox',
				'label'       => __( 'SoftBank Matomete Payment(A)', 'woocommerce-for-paygent-payment-main' ),
				'default'     => 'yes',
			),
		);
	}

    /**
     * UI - Payment page fields for paygent Payment.
    */
	function payment_fields() {
		// Description of payment method from settings
		if ( $this->description ) { ?>
		<p><?php echo $this->description; ?></p>
		<?php } ?>
		<fieldset  style="padding-left: 40px;">
		<p><?php _e( 'Please select carrier type where you want to pay', 'woocommerce-for-paygent-payment-main' );?></p>
		<?php $this->carrier_type_select(); ?>
		</fieldset>
	<?php }

	function carrier_type_select(){
		?><select name="career_type">
		<?php foreach($this->carrier_types as $num => $value){?>
		<option value="<?php echo $num; ?>"><?php echo $value;?></option>
	<?php }?>
		</select><?php 
	}

	function is_device(){
		$device_info = '';
		// ユーザーエージェントを変数に格納する。
		$ua = $_SERVER['HTTP_USER_AGENT'];
		//  スマートフォンで判定したい端末のUAを配列に入れる
		$spes = array(
			'iPhone',         // Apple iPhone
			'iPod',           // Apple iPod touch
			'Android',        // Android
			'dream',          // Pre 1.5 Android
			'CUPCAKE',        // 1.5+ Android
			'blackberry',     // blackberry
			'webOS',          // Palm Pre Experimental
			'incognito',      // Other iPhone browser
			'webmate'         // Other iPhone browser
		);
		// タブレットで判定したい端末のUAを配列に入れる
		$tabs = array(
			'iPad',
			'Android'
		);
		// ガラケーで判定したい端末のUAを配列に入れる。
		$mbes = array(
			'DoCoMo',
			'KDDI',
			'DDIPOKET',
			'UP.Browser',
			'J-PHONE',
			'Vodafone',
			'SoftBank',
		);
 
		// デバイス変数が空だったら判定する
		if(empty($device_info)){
			// タブレット判定
			foreach($tabs as $tab){
				$str = "/".$tab."/i";
				// ユーザーエージェントにstrが含まれていたら実行する
				if (preg_match($str,$ua)){
					// strがAndroidだったらのモバイル判定を行う。
					if ($str === '/Android/i'){
						// ユーザーエージェントにMobileが含まれていなかったらタブレット
						if (!preg_match("/Mobile/i", $ua)) {
							$device_info = 'tab';
						}else{
						// ユーザーエージェントにMobileが含まれていたらスマートフォン
							$device_info = 'sp';
 						}
 					}else{
 						// Android以外はそのまま結果を返す
 						$device_info = 'tab';
 					}
				}
 			}
 		}

		// デバイス変数が空だったら判定する
		if(empty($device_info)){
			// スマートフォン判定
			foreach($spes as $sp){
				$str = "/".$sp."/i";
				// ユーザーエージェントにstrが含まれていたらスマートフォン
				if (preg_match($str,$ua)){
					$device_info = 'sp';
				}
			}
		}

		// デバイス変数が空だったら判定する
 		if(empty($device_info)){
 			// ガラケー判定
 			foreach($mbes as $mb){
	 			$str = "/".$mb."/i";
	 			// ユーザーエージェントにstrが含まれていたらガラケー
				if (preg_match($str,$ua)){
					if($mb == 'DoCoMo'){
						$device_info = 'mb-docomo';
					}elseif($mb == 'KDDI'){
						$device_info = 'mb-au';
					}elseif($mb == 'SoftBank' or $mb == 'Vodafone' or $mb == 'J-PHONE'){
						$device_info = 'mb-softbank';
					}
	 				
				}
 			}
		}
 
		// どの判定にも引っかからなかった場合はPCとする
		if(empty($device_info)){
			$device_info = 'pc';
		}
		return $device_info;
	}
	/**
	 * Process the payment and return the result.
	 */
	function process_payment( $order_id ) {
		include_once( 'includes/class-wc-gateway-paygent-request.php' );

		$paygent_request = new WC_Gateway_Paygent_Request();
		global $woocommerce;
		global $wpdb;

		$order = new WC_Order( $order_id );
		$user = wp_get_current_user();
		if($order->user_id){
			$customer_id   = $user->ID;
		}else{
			$customer_id   = $order->id.'-user';
		}
		$send_data = array();

		//Check test mode
		$test_mode = get_option('wc-paygent-testmode');

		//Common header
		$telegram_kind = '100';
		$send_data['trading_id'] = 'wc_'.$order->id;
		$send_data['payment_id'] = '';

		$send_data['amount'] = $order->order_total;
		$career_type = get_post_meta($order->id, 'career_type', true);
		if(empty($career_type)){
			$send_data['career_type'] = intval($this->get_post( 'career_type' ));
		}else{
			$send_data['career_type'] = $career_type;
		}
		update_post_meta($order->id, 'career_type', $send_data['career_type']);

		if($send_data['career_type'] == 3){
			preg_match("/^.+ser([0-9a-zA-Z]+).*$/", $_SERVER['HTTP_USER_AGENT'], $ua_match);
			$send_data['uid'] = $ua_match[1];
		}
		$send_data['return_url'] = $this->get_return_url( $order );
		$send_data['cancel_url'] = $woocommerce->cart->get_cart_url();
		$send_data['other_url'] = $woocommerce->cart->get_cart_url();
		if($this->is_device()=='mb-docomo'){
			$send_data['pc_mobile_type'] = '1';
		}elseif($this->is_device()=='mb-au'){
			$send_data['pc_mobile_type'] = '2';
		}elseif($this->is_device()=='mb-softbank'){
			$send_data['pc_mobile_type'] = '3';
		}elseif($this->is_device()=='sp'){
			$send_data['pc_mobile_type'] = '4';
		}else{
			$send_data['pc_mobile_type'] = '0';
		}
//$order->add_order_note( 'test00-'.$send_data['career_type'] );

		$order_open_id = get_post_meta($order->id, 'open_id', true);
		if($send_data['career_type']=='5' or $send_data['career_type']=='4' or $send_data['career_type']=='7'){
			if(isset($order_open_id) and !empty($order_open_id)){
				$send_data['open_id'] = $order_open_id;
			}else{
				$send_data['redirect_url'] = $this->get_return_url( $order );
				$telegram_kind = '104';
				if(isset($this->auth_url) and $send_data['career_type']=='7'){ $send_data['pc_mobile_type'] = $this->auth_url; }
				$response = $paygent_request->send_paygent_request($test_mode, $order, $telegram_kind, $send_data);
				if($response['result'] == 0 and $response['result_array']){
					$order->update_status( 'pending', sprintf(__( 'Pending %s', 'woocommerce-for-paygent-payment-main' ), __('Carrier Payment', 'woocommerce-for-paygent-payment-main' ) ) );
					if($send_data['career_type']=='5'){
						update_post_meta( $order->id, '_open_id_redirect_html', mb_convert_encoding($response['result_array'][0]['redirect_html'] ,"UTF-8","SJIS" ) );
						return array (
							'result'   => 'success',
							'redirect' => $this->get_return_url( $order ),
						);
					}else{
						update_post_meta( $order->id, '_open_id_redirect_url', wc_clean($response['result_array'][0]['redirect_url'] ) );
						return array (
							'result'   => 'success',
							'redirect' => $response['result_array'][0]['redirect_url'],
						);
					}
					
				}else{
					$paygent_request->error_response($response, $order);
					return ;
				}
			}
		}
		$response = $paygent_request->send_paygent_request($test_mode, $order, $telegram_kind, $send_data);

		// Check response
		if ( $response['result'] == 0 and $response['result_array']) {
			// Success
			$order->add_order_note( __( 'paygent Payment completed. Transaction ID: ' , 'woocommerce-for-paygent-payment-main' ) .  $response['result_array'][0]['payment_id'] );
			//set transaction id for Paygent Order Number
			update_post_meta( $order->id, '_transaction_id', wc_clean( $response['result_array'][0]['payment_id'] ) );
			update_post_meta( $order->id, '_trade_generation_date', wc_clean( $response['result_array'][0]['trade_generation_date'] ) );
			if(isset($response['result_array'][0]['redirect_url'])){
				update_post_meta( $order->id, '_redirect_url', wc_clean($response['result_array'][0]['redirect_url'] ) );
			}else{
				update_post_meta( $order->id, '_redirect_html', mb_convert_encoding($response['result_array'][0]['redirect_html'],"UTF-8","SJIS" ) );
			}
			// Mark as on-hold (we're awaiting the payment)
			$order->update_status( 'on-hold', sprintf(__( 'Awaiting %s', 'woocommerce-for-paygent-payment-main' ), __('Carrier Payment', 'woocommerce-for-paygent-payment-main' ) ) );

			// Return thank you redirect
			if(isset($response['result_array'][0]['redirect_url'])){
				return array (
					'result'   => 'success',
					'redirect' => $response['result_array'][0]['redirect_url'],
				);
			}else{
				return array (
					'result'   => 'success',
					'redirect' => $this->get_return_url( $order ),
				);
			}

		}else{
			$paygent_request->error_response($response, $order);
		}
	}
    /**
     * Pay Form for thank you page
     */
	function thankyou_payment_form($order_id) {
		$open_id = get_post_meta($order_id, 'open_id', true);
		if(isset($_GET['open_id']) and empty($open_id)){
			update_post_meta( $order_id, 'open_id', wc_clean( $_GET['open_id'] ) );
			$this->process_payment( $order_id );
			$order = new WC_Order( $order_id );
			header("Location: " . $this->get_return_url( $order ));
		}
		$status = get_post_status( $order_id );
		if(isset($_GET['payment_id']) and isset($_GET['trading_id']) and isset($_GET['career_payment_id']) and isset($_GET['accept_date'])){
			$order = new WC_Order( $order_id );
			$order->update_status( 'processing', sprintf(__( 'processing this order via %s', 'woocommerce-for-paygent-payment-main' ), __('Carrier Payment', 'woocommerce-for-paygent-payment-main' ) ) );
			
		}elseif($status == 'wc-on-hold'){
			$form = get_post_meta($order_id, '_redirect_html', true);
			echo $form;
			echo __('This order was not complete. Please pay from here to Carrier payment', 'woocommerce-for-paygent-payment-main');
		}elseif($status == 'wc-pending'){
			$form = get_post_meta($order_id, '_open_id_redirect_html', true);
			echo $form;
			echo __('This order was not complete. Please authorised from here to Carrier site', 'woocommerce-for-paygent-payment-main');
		}
	}
    /**
     * Check payment details for valid format
     */
	function validate_fields() {

		global $woocommerce;

		return true;
	}

	/**
	 * Process a refund if supported
	 * @param  int $order_id
	 * @param  float $amount
	 * @param  string $reason
	 * @return  boolean True or false based on success, or a WP_Error object
	 */
	public function process_refund( $order_id, $amount = null, $reason = '' ) {
		$order = wc_get_order( $order_id );
		include_once( 'includes/class-wc-gateway-paygent-request.php' );
		$telegram_kind_check = '094';
		$transaction_id = get_post_meta( $order_id, '_transaction_id', true );;
		$send_data_check = array('payment_id' => $transaction_id,);
		//Check test mode
		$test_mode = get_option('wc-paygent-testmode');

		$order_check = new WC_Gateway_Paygent_Request();
		$order_result = $order_check->send_paygent_request($test_mode, $order, $telegram_kind_check, $send_data_check);
		if($amount == $order->order_total ){
			if($order_result['result_array'][0]['payment_status']==20){
				$telegram_kind_del = '021';//Authory Cansel
			}elseif($order_result['result_array'][0]['payment_status']==30){
				$telegram_kind_del = '023';//Sales Cansel
			}
			$order_del = new WC_Gateway_Paygent_Request();
			$del_result = $order_del->send_paygent_request($test_mode, $order, $telegram_kind_del, $send_data_check);
			if($del_result['result']== 1){
				$order->add_order_note( __( 'Failed Refund. ', 'woocommerce-for-paygent-payment-main' ).__( 'Error Code :', 'woocommerce-for-paygent-payment-main' ).$del_result['responseCode'].__( ' Error message :', 'woocommerce-for-paygent-payment-main' ).$del_result['responseDetail']);
				return false;
			}elseif($del_result['result'] == 0){
				$order->update_status('cancelled');
				$order->update_status('refunded');
				return true;
			}else{
				$order->add_order_note( __( 'Failed Refund.', 'woocommerce-for-paygent-payment-main' ));
				return false;
			}
		}elseif($amount < $order->order_total){
			if($order_result['result_array'][0]['payment_status']==20){
				$telegram_kind_refund = '028';//Authory Change
			}elseif($order_result['result_array'][0]['payment_status']==30){
				$telegram_kind_refund = '029';//Sales Change
			}
			$send_data_refund = array(
				'payment_id' => $transaction_id,
				'payment_amount' => $transaction_id,
				'reduction_flag' => 1,
			);
			$order_refund = new WC_Gateway_Paygent_Request();
			$refund_result = $order_del->send_paygent_request($test_mode, $order, $telegram_kind_refund, $send_data_refund);
			if($refund_result['result']== 1){
				$order->add_order_note( __( 'Failed Refund. ', 'woocommerce-for-paygent-payment-main' ).__( 'Error Code :', 'woocommerce-for-paygent-payment-main' ).$del_result['responseCode'].__( ' Error message :', 'woocommerce-for-paygent-payment-main' ).$del_result['responseDetail']);
				return false;
			}elseif($refund_result['result'] == 0){
				$order->add_order_note( __( 'Partial Refunded.', 'woocommerce-for-paygent-payment-main' ));
				return true;
			}else{
				$order->add_order_note( __( 'Failed Refund.', 'woocommerce-for-paygent-payment-main' ));
				return false;
			}
		}
		return false;
	}
	
	function receipt_page( $order ) {
		echo '<p>' . __( 'Thank you for your order.', 'woocommerce-for-paygent-payment-main' ) . '</p>';
	}

	/**
	 * Get post data if set
	 */
	private function get_post( $name ) {
		if ( isset( $_POST[ $name ] ) ) {
			return $_POST[ $name ];
		}
		return null;
	}
}

/**
 * Add the gateway to woocommerce
 */
function add_wc_paygent_carrier_gateway( $methods ) {
	$methods[] = 'WC_Gateway_Paygent_Carrier';
	return $methods;
}

add_filter( 'woocommerce_payment_gateways', 'add_wc_paygent_carrier_gateway' );

/**
 * Edit the available gateway to woocommerce
 */
function edit_available_carrier_gateways( $methods ) {
	if ( isset($currency) ) {
	}else{
		$currency = get_woocommerce_currency();
	}
	if($currency !='JPY'){
	unset($methods['paygent_mb']);
	}
	return $methods;
}

add_filter( 'woocommerce_available_payment_gateways', 'edit_available_carrier_gateways' );
