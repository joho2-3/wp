<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Paygent Payment Gateway
 *
 * Provides a Paygent ATM Payment Gateway.
 *
 * @class 		WC_Paygent
 * @extends		WC_Gateway_Paygent_ATM
 * @version		1.2.0
 * @package		WooCommerce/Classes/Payment
 * @author		Artisan Workshop
 */
class WC_Gateway_Paygent_ATM extends WC_Payment_Gateway {

	/**
	 * Constructor for the gateway.
	 *
	 * @access public
	 * @return void
	 */
	public function __construct() {

		$this->id                = 'paygent_atm';
		$this->has_fields        = false;
		$this->order_button_text = sprintf(__( 'Proceed to Paygent %s', 'woocommerce-for-paygent-payment-main' ), __('ATM Payment', 'woocommerce-for-paygent-payment-main' ));
		
        // Create plugin fields and settings
		$this->init_form_fields();
		$this->init_settings();
		$this->method_title       = sprintf(__( 'Paygent %s Gateway', 'woocommerce-for-paygent-payment-main' ), __('ATM Payment', 'woocommerce-for-paygent-payment-main' ));
		$this->method_description = sprintf(__( 'Allows payments by Paygent %s in Japan.', 'woocommerce-for-paygent-payment-main' ), __('ATM Payment', 'woocommerce-for-paygent-payment-main' ));
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

		// Actions
		add_action( 'woocommerce_receipt_paygent_atm',                              array( $this, 'receipt_page' ) );
		add_action( 'woocommerce_update_options_payment_gateways',              array( $this, 'process_admin_options' ) );
		add_action( 'woocommerce_update_options_payment_gateways_' . $this->id, array( $this, 'process_admin_options' ) );
		add_action( 'woocommerce_thankyou_' . $this->id, array( $this, 'paymnet_detail' ) );
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
				'label'       => sprintf(__( 'Enable paygent %s Payment', 'woocommerce-for-paygent-payment-main' ), __('ATM Payment', 'woocommerce-for-paygent-payment-main' )),
				'type'        => 'checkbox',
				'description' => '',
				'default'     => 'no'
			),
			'title'       => array(
				'title'       => __( 'Title', 'woocommerce-for-paygent-payment-main' ),
				'type'        => 'text',
				'description' => __( 'This controls the title which the user sees during checkout.', 'woocommerce-for-paygent-payment-main' ),
				'default'     => __( 'ATM Payment (Paygent)', 'woocommerce-for-paygent-payment-main' )
			),
			'description' => array(
				'title'       => __( 'Description', 'woocommerce-for-paygent-payment-main' ),
				'type'        => 'textarea',
				'description' => __( 'This controls the description which the user sees during checkout.', 'woocommerce-for-paygent-payment-main' ),
				'default'     => sprintf(__( 'Pay with your %s via Paygent.', 'woocommerce-for-paygent-payment-main' ), __('ATM Payment', 'woocommerce-for-paygent-payment-main' )),
			),
			'order_button_text' => array(
				'title'       => __( 'Order Button Text', 'woocommerce-for-paygent-payment-main' ),
				'type'        => 'textarea',
				'description' => __( 'This controls the description which the user sees during checkout.', 'woocommerce-for-paygent-payment-main' ),
				'default'     => sprintf(__( 'Proceed to Paygent %s', 'woocommerce-for-paygent-payment-main' ), __('ATM Payment', 'woocommerce-for-paygent-payment-main' )),
			),
			'payment_detail' => array(
				'title'       => __( 'Invoice detail', 'woocommerce-for-paygent-payment-main' ),
				'type'        => 'text',
				'description' => __( 'This controls the text which the detail at ATM.', 'woocommerce-for-paygent-payment-main' ),
				'default'     => sprintf(__( 'Shop Title Via %s', 'woocommerce-for-paygent-payment-main' ), __('ATM Payment', 'woocommerce-for-paygent-payment-main' )),
			),
			'payment_detail_kana' => array(
				'title'       => __( 'Invoice detail (kana)', 'woocommerce-for-paygent-payment-main' ),
				'type'        => 'text',
				'description' => __( 'This controls the text which the detail at ATM.(Kana)', 'woocommerce-for-paygent-payment-main' ),
				'default'     => sprintf(__( 'Shop Title Via %s (kana)', 'woocommerce-for-paygent-payment-main' ), __('ATM Payment', 'woocommerce-for-paygent-payment-main' )),
			),
			'payment_limit_date' => array(
				'title'       => __( 'Payment Limit date', 'woocommerce-for-paygent-payment-main' ),
				'id'              => 'wc-paygent-atm-limit',
				'type'        => 'text',
				'default'     => '30',
				'description' => __( 'Set Payment limit date.', 'woocommerce-for-paygent-payment-main' ),
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
	<?php }

	/**
	 * Process the payment and return the result.
	 */
	function process_payment( $order_id ) {
		include_once( 'includes/class-wc-gateway-paygent-request.php' );

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
		$telegram_kind = '010';
		$send_data['trading_id'] = 'wc_'.$order->id;
		$send_data['payment_id'] = '';

		$send_data['payment_amount'] = $order->order_total;

		$send_data['payment_detail'] = mb_convert_encoding($this->payment_detail, "SJIS", "UTF-8");
		$send_data['payment_detail_kana'] = mb_convert_encoding($this->payment_detail_kana, "SJIS", "UTF-8");

		$send_data['payment_limit_date'] = $this->payment_limit_date;
		
		$paygent_request = new WC_Gateway_Paygent_Request();
		$response = $paygent_request->send_paygent_request($test_mode, $order, $telegram_kind, $send_data);

		// Check response
		if ( $response['result'] == 0 and $response['result_array']) {
			// Success
			$order->add_order_note( __( 'paygent Payment completed. Transaction ID: ' , 'woocommerce-for-paygent-payment-main' ) .  $response['result_array'][0]['payment_id'] );
			//set transaction id for Paygent Order Number
			update_post_meta( $order->id, '_transaction_id', wc_clean( $response['result_array'][0]['payment_id'] ) );
			update_post_meta( $order->id, '_pay_center_number', wc_clean($response['result_array'][0]['pay_center_number'] ) );
			update_post_meta( $order->id, '_customer_number', wc_clean($response['result_array'][0]['customer_number'] ) );
			update_post_meta( $order->id, '_conf_number', wc_clean($response['result_array'][0]['conf_number'] ) );
			update_post_meta( $order->id, '_payment_limit_date', wc_clean($response['result_array'][0]['payment_limit_date'] ) );
			// Mark as on-hold (we're awaiting the payment)
			$order->update_status( 'on-hold', sprintf(__( 'Awaiting %s', 'woocommerce-for-paygent-payment-main' ), __('ATM Payment', 'woocommerce-for-paygent-payment-main' ) ) );

			// Return thank you redirect
			return array (
				'result'   => 'success',
				'redirect' => $this->get_return_url( $order ),
			);

		}else{
			$paygent_request->error_response($response, $order);
		}
	}
    /**
     * Pay Form for thank you page
     */
	function paymnet_detail($order_id) {
		$status = get_post_status( $order_id );
		if($status == 'wc-on-hold'){
			$pay_center_number = get_post_meta($order_id, '_pay_center_number', true);
			$customer_number = get_post_meta($order_id, '_customer_number', true);
			$conf_number = get_post_meta($order_id, '_conf_number', true);
			$payment_limit_date = get_post_meta($order_id, '_payment_limit_date', true);
			echo '<ul class="woocommerce-thankyou-order-details order_details">'.PHP_EOL;
			echo '<li class="pay_center_number">'.PHP_EOL;
			echo __( 'Pay Center Number :', 'woocommerce-for-paygent-payment-main' ).'<strong>'.$pay_center_number.'</strong>'.PHP_EOL;
			echo '</li>
			<li class="customer_number">'.PHP_EOL;
			echo __( 'Customer Number :', 'woocommerce-for-paygent-payment-main' ).'<strong>'.$customer_number.'</strong>'.PHP_EOL;
			echo '</li>
			<li class="conf_number">'.PHP_EOL;
			echo __( 'Conf Number :', 'woocommerce-for-paygent-payment-main' ).'<strong>'.$conf_number.'</strong>'.PHP_EOL;
			echo '</li>
			<li class="payment_limit_date">'.PHP_EOL;
			echo __( 'Payment Limit Date :', 'woocommerce-for-paygent-payment-main' ).'<strong>'.$payment_limit_date.'</strong>'.PHP_EOL;
			echo '</li>
			</ul>'.PHP_EOL;
			echo __('This order was not complete. Please pay at ATM.', 'woocommerce-for-paygent-payment-main').PHP_EOL;
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
function add_wc_paygent_atm_gateway( $methods ) {
	$methods[] = 'WC_Gateway_Paygent_ATM';
	return $methods;
}

add_filter( 'woocommerce_payment_gateways', 'add_wc_paygent_atm_gateway' );

/**
 * Edit the available gateway to woocommerce
 */
function edit_available_atm_gateways( $methods ) {
	if ( isset($currency) ) {
	}else{
		$currency = get_woocommerce_currency();
	}
	if($currency !='JPY'){
	unset($methods['paygent_atm']);
	}
	return $methods;
}

add_filter( 'woocommerce_available_payment_gateways', 'edit_available_atm_gateways' );
