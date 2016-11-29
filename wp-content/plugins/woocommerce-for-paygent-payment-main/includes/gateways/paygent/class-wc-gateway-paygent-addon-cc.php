<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Paygent Payment Gateway
 *
 * Provides a Paygent Credit Card Payment Gateway.
 *
 * @class 		WC_Paygent
 * @extends		WC_Gateway_Paygent_CC
 * @version		1.1.0
 * @package		WooCommerce/Classes/Payment
 * @author		Artisan Workshop
 */
class WC_Gateway_Paygent_CC_Addons extends WC_Gateway_Paygent_CC {

	/**
	 * Constructor for the gateway.
	 *
	 * @access public
	 * @return void
	 */
	public function __construct() {
		parent::__construct();

		if ( class_exists( 'WC_Subscriptions_Order' ) ) {
			add_action( 'woocommerce_scheduled_subscription_payment_' . $this->id, array( $this, 'scheduled_subscription_payment' ), 10, 2 );
		}
	}
	/**
	 * Check if order contains subscriptions.
	 *
	 * @param  int $order_id
	 * @return bool
	 */
	protected function order_contains_subscription( $order_id ) {
		return function_exists( 'wcs_order_contains_subscription' ) && ( wcs_order_contains_subscription( $order_id ) || wcs_order_contains_renewal( $order_id ) );
	}

	/**
	 * Is $order_id a subscription?
	 * @param  int  $order_id
	 * @return boolean
	 */
	protected function is_subscription( $order_id ) {
		return ( function_exists( 'wcs_order_contains_subscription' ) && ( wcs_order_contains_subscription( $order_id ) || wcs_is_subscription( $order_id ) || wcs_order_contains_renewal( $order_id ) ) );
	}

	/**
	 * Process the subscription.
	 *
	 * @param  WC_Order $order
	 * @param  boolean $subscription
	 * @return
	 */
	protected function process_subscription( $order , $subscription = false) {
		$payment_response = $this->process_subscription_payment( $order, $order->get_total() );
		return;
	}

	/**
	 * Process the payment.
	 *
	 * @param  int $order_id
	 * @param  boolean $subscription
	 * @return array
	 */
	public function process_payment( $order_id , $subscription = false) {
		$order = wc_get_order( $order_id );
		// Processing subscription
		if ( $this->is_subscription( $order_id ) ) {
			// Regular payment with force customer enabled
			return parent::process_payment( $order_id, true );
		} else {
			return parent::process_payment( $order_id, false );
		}
	}
	/**
	 * process_subscription_payment function.
	 *
	 * @param WC_order $order
	 * @param int $amount (default: 0)
	 * @return bool|WP_Error
	 */
	public function process_subscription_payment( $order = '', $amount = 0 ) {
		if ( 0 == $amount ) {
			// Payment complete
			$order->payment_complete();

			return true;
		}
		include_once( 'includes/class-wc-gateway-paygent-request.php' );
		$order = new WC_Order( $order_id );
		$user = wp_get_current_user();
		$customer_id   = $user->ID;
		$send_data = array();
		//Common header
		$telegram_kind = '020';
		$send_data['trading_id'] = 'wc_'.$order->id;
		$send_data['payment_id'] = '';

		$send_data['payment_amount'] = $order->order_total;

		//Check test mode
		$test_mode = get_option('wc-paygent-testmode');

		// Create server request using stored or new payment details
		$card_user_id = 'wc'.$order->user_id;

	  	//Payment times
		$send_data['payment_class'] = 10;//One time payment
		$send_data['3dsecure_ryaku'] = 1;

		$paygent_request = new WC_Gateway_Paygent_Request();
		$response = $paygent_request->send_paygent_request($test_mode, $order, $telegram_kind, $send_data);

		// Check response
		if ( $response['result'] == 0 and $response['result_array']) {
			// Success
			$order->add_order_note( __( 'paygent Payment completed. Transaction ID: ' , 'woocommerce-for-paygent-payment-main' ) .  $response['result_array'][0]['payment_id'] );
			//set transaction id for Paygent Order Number
			update_post_meta( $order->id, '_transaction_id', wc_clean( $response['result_array'][0]['payment_id'] ) );
			$order->payment_complete();
		}else{
			$paygent_request->error_response($response, $order);
		}
	}
	/**
	 * scheduled_subscription_payment function.
	 *
	 * @param float $amount_to_charge The amount to charge.
	 * @param WC_Order $renewal_order A WC_Order object created to record the renewal payment.
	 */
	public function scheduled_subscription_payment( $amount_to_charge, $renewal_order ) {
		$result = $this->process_subscription_payment( $renewal_order, $amount_to_charge );

		if ( is_wp_error( $result ) ) {
			$renewal_order->update_status( 'failed', sprintf( __( 'Paygent Transaction Failed (%s)', 'woocommerce' ), $result->get_error_message() ) );
		}
	}
}
