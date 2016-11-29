<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Paygent Payment Gateway
 *
 * Provides a Paygent Multi-currency Credit Card Payment Gateway.
 *
 * @class 			WC_Paygent
 * @extends		WC_Gateway_Paygent_MCCC
 * @version		1.1.0
 * @package		WooCommerce/Classes/Payment
 * @author			Artisan Workshop
 */
class WC_Gateway_Paygent_MCCC extends WC_Payment_Gateway {


	/**
	 * Constructor for the gateway.
	 *
	 * @access public
	 * @return void
	 */
	public function __construct() {

		$this->id                = 'paygent_mccc';
		$this->has_fields        = false;
		$this->order_button_text = __( 'Proceed to Paygent Multi-currency Credit Card', 'woocommerce-for-paygent-payment-main' );
		$this->method_title      = __( 'Paygent Multi-currency Credit Card', 'woocommerce-for-paygent-payment-main' );
		
        // Create plugin fields and settings
		$this->init_form_fields();
		$this->init_settings();
		$this->method_title       = __( 'Paygent Multi-currency Credit Card Payment Gateway', 'woocommerce-for-paygent-payment-main' );
		$this->method_description = __( 'Allows payments by Paygent Multi-currency Credit Card in Japan.', 'woocommerce-for-paygent-payment-main' );
		$this->supports = array('products','refunds','default_credit_card_form');

		// Get setting values
		foreach ( $this->settings as $key => $val ) $this->$key = $val;

		// Load plugin checkout icon
//		$this->icon = WP_CONTENT_DIR . '/plugins/woocommerce-paygent-main/images/paygent-cards.png';
		$this->icon = plugins_url( 'images/paygent-cards.png' , __FILE__ );
		// Logs
		if ( 'yes' == $this->debug ) {
			$this->log = new WC_Logger();
		}

		// Actions
		add_action( 'woocommerce_receipt_paygent_mccc',                         array( $this, 'receipt_page' ) );
		add_action( 'woocommerce_update_options_payment_gateways',              array( $this, 'process_admin_options' ) );
		add_action( 'woocommerce_update_options_payment_gateways_' . $this->id, array( $this, 'process_admin_options' ) );
		add_action( 'wp_enqueue_scripts',                                       array( $this, 'add_paygent_mccc_scripts' ) );
	}

	/**
	 * Initialize Gateway Settings Form Fields.
	*/
	function init_form_fields() {

		$this->form_fields = array(
			'enabled'     => array(
				'title'       => __( 'Enable/Disable', 'woocommerce-for-paygent-payment-main' ),
				'label'       => __( 'Enable paygent Multi-currency Credit Card Payment', 'woocommerce-for-paygent-payment-main' ),
				'type'        => 'checkbox',
				'description' => '',
				'default'     => 'no'
			),
			'title'       => array(
				'title'       => __( 'Title', 'woocommerce-for-paygent-payment-main' ),
				'type'        => 'text',
				'description' => __( 'This controls the title which the user sees during checkout.', 'woocommerce-for-paygent-payment-main' ),
				'default'     => __( 'Multi-currency Credit Card (Paygent)', 'woocommerce-for-paygent-payment-main' )
			),
			'description' => array(
				'title'       => __( 'Description', 'woocommerce-for-paygent-payment-main' ),
				'type'        => 'textarea',
				'description' => __( 'This controls the description which the user sees during checkout.', 'woocommerce-for-paygent-payment-main' ),
				'default'     => __( 'Pay with your credit card via Paygent.', 'woocommerce-for-paygent-payment-main' )
			),
			'order_button_text' => array(
				'title'       => __( 'Order Button', 'woocommerce-for-paygent-payment-main' ),
				'type'        => 'textarea',
				'description' => __( 'This controls the order button which the user sees during checkout.', 'woocommerce-for-paygent-payment-main' ),
				'default'     => __( 'Proceed to Paygent Multi-currency Credit Card', 'woocommerce-for-paygent-payment-main' )
			),
			'security_check' => array(
				'title'       => __( 'Security Check Code', 'woocommerce-for-paygent-payment-main' ),
				'type'        => 'checkbox',
				'label'       => __( 'Enable Security Check Code', 'woocommerce-for-paygent-payment-main' ),
				'default'     => 'no',
				'description' => sprintf( __( 'Require customer to enter credit card CVV code (Security Check Code).', 'woocommerce-for-paygent-payment-main' )),
			),
			'test_mode' => array(
				'title'       => __( 'Test Mode', 'woocommerce-for-paygent-payment-main' ),
				'type'        => 'checkbox',
				'label'       => __( 'Enable Test Mode', 'woocommerce-for-paygent-payment-main' ),
				'default'     => 'no',
				'description' => sprintf( __( 'If you use test-mode, please check it.', 'woocommerce-for-paygent-payment-main' )),
			),
			'store_card_info' => array(
				'title'       => __( 'Store Card Infomation', 'woocommerce-for-paygent-payment-main' ),
				'type'        => 'checkbox',
				'label'       => __( 'Enable Store Card Infomation', 'woocommerce-for-paygent-payment-main' ),
				'default'     => 'no',
				'description' => sprintf( __( 'Store user Credit Card information in Paygent Server.(Option)', 'woocommerce-for-paygent-payment-main' )),
			)
		);
	}


	/**
	* UI - Admin Panel Options
	*/
	function admin_options() { ?>
		<h3><?php _e( 'Paygent Multi-currency Credit Card','woocommerce-for-paygent-payment-main' ); ?></h3>
		<table class="form-table">
		<?php $this->generate_settings_html(); ?>
		</table>
	<?php }

	/**
	* UI - Payment page fields for paygent Payment.
	*/
	function payment_fields() {
		// Description of payment method from settings
		if ( $this->description ) { ?>
			<p><?php echo $this->description; ?></p>
		<?php } ?>
      	<?php if($this->store_card_info == 'yes'){ ?>
		<fieldset  style="padding-left: 40px;">
		<?php
			$user = wp_get_current_user();
			$card_user_id = 'wc'.$user->ID;
			$customer_check = $this->user_has_stored_data($card_user_id);
			if($this->store_card_info =='yes' ){
				$this->display_stored_user_data($customer_check);
			}?>
		</fieldset>
		<?php }

		if(isset($customer_check) and $customer_check['responseCode']!='P026'){
			?><div id="paygent-new-info" style="display:none"><?php
		}else{
			?><!-- Show input boxes for new data -->
			<div id="paygent-new-info">
		<?php } ?>
		<?PHP $this->credit_card_form( array( 'fields_have_names' => true ) ); ?>
		<?php
    }

	/**
	 * Process the payment and return the result.
	 */
	function process_payment( $order_id ) {
		include_once( 'includes/class-wc-gateway-paygent-request.php' );

		global $woocommerce;

		$order = new WC_Order( $order_id );
		$user = new WP_User( $order->user_id );
		if($order->user_id){
			$customer_id   = $order->user_id;
		}else{
			$customer_id   = $order->id.'-user';
		}

		//Common header
		$telegram_kind = '180';//Multi-currency Card Payment
		$send_data['trading_id'] = 'wc_'.$order->id;
		$send_data['payment_id'] = '';

		$send_data['payment_amount'] = $order->order_total;
		
		//Edit Expire Data
		$card_valid_term = str_replace(array(" ","/" ), "", $this->get_post( 'paygent_mccc-card-expiry' ) );
		$card_number = str_replace( array( ' ', '-' ), '', $this->get_post( 'paygent_mccc-card-number' ));

		//Get Currency infomation
		if ( ! $currency ) {
			$currency = get_woocommerce_currency();
		}
		$send_data['currency_code'] = $currency;

		// Create server request using stored or new payment details
		$card_user_id = 'wc'.$order->user_id;
		if ( $this->store_card_info == 'yes'){
			if($this->get_post( 'paygent-use-stored-payment-info' )=='yes'){
				$customer_check = $this->user_has_stored_data($card_user_id);
				$send_data['stock_card_mode'] = 1;
				$send_data['customer_id'] = $card_user_id;
				$send_data['customer_card_id'] = $customer_check['result_array'][$this->get_post( 'stored-info' )]['customer_card_id'];
			}else{
				$send_data['stock_card_mode'] = 1;
				$send_data['customer_id'] = $card_user_id;
				$stored_user_card_data = $this->add_stored_user_data($card_user_id, $card_number, $card_valid_term);
				if($stored_user_card_data['result']){
					$order->add_order_note( __( 'Fault to stored your card info.', 'woocommerce-for-paygent-payment-main' ). $stored_user_card_data['responseCode'] .':'. mb_convert_encoding($stored_user_card_data['responseDetail'],"UTF-8","SJIS" ).':'.$order->user_id );
        				wc_add_notice(__( 'Fault to stored your card info.', 'woocommerce-for-paygent-payment-main' ), $notice_type = 'error' );
				}else{
					$send_data['customer_card_id'] = $stored_user_card_data['result_array'][0]['customer_card_id'];
					$order->add_order_note( __( 'Stored card info.', 'woocommerce-for-paygent-payment-main' ). ' Customer Card Id : '.$stored_user_card_data['result_array'][0]['customer_card_id'] );
				}
			}
		}else{
			//Credit Card Infomation
			$send_data['card_number'] = $card_number;
			$send_data['card_valid_term'] = $card_valid_term;
    	    // Using Security Check
    	    if ( $this->security_check == 'yes' ) {
				$send_data['card_conf_number'] = $this->get_post( 'paygent_mccc-card-cvc' );
			}
      	}

	  	//Payment times
		$send_data['payment_class'] = 10;//One time payment
		
		//3D Secure Setting
		$send_data['3dsecure_ryaku'] = 1;
		$send_data['http_accept'] = $_SERVER['HTTP_ACCEPT'];
		$send_data['http_user_agent'] = $_SERVER['HTTP_USER_AGENT'];

		$paygent_request = new WC_Gateway_Paygent_Request();
		$response = $paygent_request->send_paygent_request($this->test_mode, $order, $telegram_kind, $send_data);

		// Check response
		if ( $response['result'] == 0 ) {
			// Success
			$order->add_order_note( __( 'paygent Payment completed. Transaction ID: ' , 'woocommerce-for-paygent-payment-main' ) . $response['result_array'][0]['payment_id'] );
			//set transaction id for Paygent Order Number
			update_post_meta( $order->id, '_transaction_id', wc_clean( $response['result_array'][0]['payment_id'] ) );
			$order->payment_complete();

			// Return thank you redirect
			return array (
				'result'   => 'success',
				'redirect' => $this->get_return_url( $order ),
			);

		} elseif ( $response['result'] == 7 ) {//3DS

		} elseif ( $response['result'] == 1 ) {//System Error
			// Other transaction error
			$order->add_order_note( __( 'paygent Payment failed. Sysmte Error: ', 'woocommerce-for-paygent-payment-main' ) . $response['responseCode'] .':'. mb_convert_encoding($response['responseDetail'],"UTF-8","SJIS" ).':'.'wc_'.$order->id );
			wc_add_notice(__( 'Sorry, there was an error: ', 'woocommerce-for-paygent-payment-main' ) . $response['responseCode'], $notice_type = 'error' );
		} else {
			// No response or unexpected response
			$order->add_order_note( __( "paygent Payment failed. Some trouble happened.", 'woocommerce-for-paygent-payment-main' ). $response['result'] .':'.$response['responseCode'] .':'. mb_convert_encoding($response['responseDetail'],"UTF-8","SJIS").':'.'wc_'.$order->id );
			wc_add_notice( __( 'No response from payment gateway server. Try again later or contact the site administrator.', 'woocommerce-for-paygent-payment-main' ). $response['responseCode'], $notice_type = 'error' );
		}
	}

	/**
	 * Process a payment for an ongoing subscription.
	 */
	function process_scheduled_subscription_payment( $amount_to_charge, $order, $product_id ) {
	}

    /**
     * Check if the user has any billing records in the Customer Vault
     */
    function user_has_stored_data( $user_id, $customer_card_id=null ) {
		include_once( 'includes/class-wc-gateway-paygent-request.php' );
	    $telegram_kind = '027';
		$send_data = array(
			'trading_id' => '',
			'customer_id'=> $user_id,
		);
		if($this->site_id!=1)$send_data['site_id'] = $this->site_id;

		$paygent_request = new WC_Gateway_Paygent_Request( $this );

		$result = $paygent_request->send_paygent_request($this->test_mode, $order, $telegram_kind, $send_data);
		return $result;
    }

	/**
	* Display payment method in Payment page when user have stored card data
	 */
	function display_stored_user_data( $customer_check) {
		if (!$customer_check['result'] and $customer_check['responseCode']!='P026') { ?>
		<fieldset>
		<input type="radio" name="paygent-use-stored-payment-info" id="paygent-use-stored-payment-info-yes" value="yes" checked="checked" onclick="document.getElementById('paygent-new-info').style.display='none'; document.getElementById('paygent-stored-info').style.display='block'"; />
		<label for="paygent-use-stored-payment-info-yes" style="display: inline;"><?php _e( 'Use a stored credit card from Paygent', 'woocommerce-for-paygent-payment-main' ) ?></label>
		<div id="paygent-stored-info" style="padding: 10px 0 0 40px; clear: both;">
		<p>
		<?php if(!$customer_check['result']):?>
			<?php $card_qty = count($customer_check['result_array'])-1;
			for($i=0; $i <= $card_qty; $i++) { ?>
				<input type="radio" name="stored-info" value="<?php echo $i;?>" id="stored-info">
				<?php _e( 'credit card last 4 numbers: ', 'woocommerce-for-paygent-payment-main' ) ?><?php echo substr($customer_check['result_array'][$i]['card_number'],-4); ?> (<?php echo $customer_check['result_array'][$i]['card_brand']; ?>)<br />
			<?php }
		endif;?>
		</p>
		</fieldset>
		<fieldset>
		<input type="radio" name="paygent-use-stored-payment-info" id="paygent-use-stored-payment-info-no" value="no" onclick="document.getElementById('paygent-stored-info').style.display='none'; document.getElementById('paygent-new-info').style.display='block'"; />
		<label for="paygent-use-stored-payment-info-no"  style="display: inline;"><?php _e( 'Use a new payment method', 'woocommerce-for-paygent-payment-main' ) ?></label>
		</fieldset>
		<?php } elseif($customer_check['responseCode'] and $customer_check['responseCode']!='P026') { ?>
		<fieldset>
		<div id="error"><?php echo __( 'Error! ', 'woocommerce-for-paygent-payment-main' ).$customer_check['responseCode'].":".mb_convert_encoding($customer_check['responseDetail'],"UTF-8","SJIS");?></div>
		<!-- Show input boxes for new data -->
		</fieldset>
		<?php } 
	}

    function add_stored_user_data( $user_id, $card_number, $card_valid_term ) {
		include_once( 'includes/class-wc-gateway-paygent-request.php' );
	    $telegram_kind = '025';
		$send_data = array(
			'trading_id' => '',
			'customer_id'=> $user_id,
			'card_number' => $card_number,
			'card_valid_term' => $card_valid_term,
		);
		if($this->site_id!=1)$send_data['site_id'] = $this->site_id;
		$user_request = new WC_Gateway_Paygent_Request( $this );
		$result = $user_request->send_paygent_request($this->test_mode, $order, $telegram_kind, $send_data);
		return $result;
	}
    /**
     * Check payment details for valid format
     */
	function validate_fields() {
		if ( $this->get_post( 'paygent-use-stored-payment-info' ) == 'yes' ) return true;

		global $woocommerce;

		// Check for saving payment info without having or creating an account
		if ( $this->get_post( 'saveinfo' )  && ! is_user_logged_in() && ! $this->get_post( 'createaccount' ) ) {
        	wc_add_notice( __( 'Sorry, you need to create an account in order for us to save your payment information.', 'woocommerce-for-paygent-payment-main'), $notice_type = 'error' );
			return false;
		}

		$cardNumber		= $this->get_post( 'paygent_mccc-card-number' );
		$cardCVC		= $this->get_post( 'paygent_mccc-card-cvc' );
		$cardExpiration	= $this->get_post( 'paygent_mccc-card-expiry' );

		// Strip spaces and dashes
		$cardNumber = str_replace( array( ' ', '-' ), '', $cardNumber );

		// Check card number
		if ( empty( $cardNumber ) || ! ctype_digit( $cardNumber ) ) {
			wc_add_notice( __( 'Card number is invalid.', 'woocommerce-for-paygent-payment-main' ), $notice_type = 'error' );
			return false;
		}

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
		$order_check = new WC_Gateway_Paygent_Request();
		$order_result = $order_check->send_paygent_request($this->test_mode, $order, $telegram_kind_check, $send_data_check);
		if($amount == $order->order_total ){
			if($order_result['result_array'][0]['payment_status']==20){
				$telegram_kind_del = '181';//Authory Cansel
			}elseif($order_result['result_array'][0]['payment_status']==30){
				$telegram_kind_del = '183';//Sales Cansel
			}
			$order_del = new WC_Gateway_Paygent_Request();
			$del_result = $order_del->send_paygent_request($this->test_mode, $order, $telegram_kind_del, $send_data_check);
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
				$telegram_kind_refund = '184';//Authory Change
			}elseif($order_result['result_array'][0]['payment_status']==30){
				$telegram_kind_refund = '185';//Sales Change
			}
			$send_data_refund = array(
				'payment_id' => $transaction_id,
				'payment_amount' => $transaction_id,
				'reduction_flag' => 1,
			);
			$order_refund = new WC_Gateway_Paygent_Request();
			$refund_result = $order_del->send_paygent_request($this->test_mode, $order, $telegram_kind_refund, $send_data_refund);
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
	* Include jQuery and our scripts
	*/
	function add_paygent_mccc_scripts() {

		if ( ! $this->user_has_stored_data( wp_get_current_user()->ID ) ) return;

		wp_enqueue_script( 'jquery' );
		wp_enqueue_script( 'edit_billing_details', PLUGIN_DIR . 'js/edit_billing_details.js', array( 'jquery' ), 1.0 );

		if ( $this->security_check == 'yes' ) wp_enqueue_script( 'check_cvv', PLUGIN_DIR . 'js/check_cvv.js', array( 'jquery' ), 1.0 );

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

	/**
	* Check whether an order is a subscription
	*/
	private function is_subscription( $order ) {
		return class_exists( 'WC_Subscriptions_Order' ) && WC_Subscriptions_Order::order_contains_subscription( $order );
	}
}

/**
 * Add the gateway to woocommerce
 */
function add_wc_paygent_mccc_gateway( $methods ) {
	$methods[] = 'WC_Gateway_Paygent_MCCC';
	return $methods;
}

add_filter( 'woocommerce_payment_gateways', 'add_wc_paygent_mccc_gateway' );


/**
 * Edit the available gateway to woocommerce
 */
function edit_available_gateways_mccc( $methods ) {

	if ( ! $currency ) {
		$currency = get_woocommerce_currency();
	}
	if($currency =='JPY'){
	unset($methods['paygent_mccc']);
	}
	return $methods;
}

add_filter( 'woocommerce_available_payment_gateways', 'edit_available_gateways_mccc' ,9);
