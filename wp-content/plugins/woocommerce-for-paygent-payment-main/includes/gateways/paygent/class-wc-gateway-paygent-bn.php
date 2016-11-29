<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Paygent Payment Gateway
 *
 * Provides a Paygent Bank Net Payment Gateway.
 *
 * @class 		WC_Paygent
 * @extends		WC_Gateway_Paygent_BN
 * @version		1.1.0
 * @package		WooCommerce/Classes/Payment
 * @author		Artisan Workshop
 */
class WC_Gateway_Paygent_BN extends WC_Payment_Gateway {

	/**
	 * Constructor for the gateway.
	 *
	 * @access public
	 * @return void
	 */
	public function __construct() {

		$this->id                = 'paygent_bn';
		$this->has_fields        = false;
		$this->order_button_text = __( 'Proceed to Paygent Bank Net', 'woocommerce-for-paygent-payment-main' );
		
        // Create plugin fields and settings
		$this->init_form_fields();
		$this->init_settings();
		$this->method_title       = __( 'Paygent Bank Net Payment Gateway', 'woocommerce-for-paygent-payment-main' );
		$this->method_description = __( 'Allows payments by Paygent Bnak Net in Japan.', 'woocommerce-for-paygent-payment-main' );
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


        // Set Convenience Store
		$this->banks = array();
		if(isset($this->setting_bn_08)){
			if($this->setting_bn_08 =='yes') $this->banks = array_merge($this->banks, array('D008' => __( 'Mitsubishi Tokyo UFJ Bank', 'woocommerce-for-paygent-payment-main' )));
			if($this->setting_bn_09 =='yes') $this->banks = array_merge($this->banks, array('D009' => __( 'Mitsui Sumitomo Bank', 'woocommerce-for-paygent-payment-main' )));
			if($this->setting_bn_33 =='yes') $this->banks = array_merge($this->banks, array('D033' => __( 'Japan Net Bank', 'woocommerce-for-paygent-payment-main' )));
			if($this->setting_bn_34 =='yes') $this->banks = array_merge($this->banks, array('D034' => __( 'Seven Bank', 'woocommerce-for-paygent-payment-main' )));
			if($this->setting_bn_36 =='yes') $this->banks = array_merge($this->banks, array('D036' => __( 'Rakuten Bank', 'woocommerce-for-paygent-payment-main' )));
			if($this->setting_bn_38 =='yes') $this->banks = array_merge($this->banks, array('D038' => __( 'Jushin SBI Net Bank', 'woocommerce-for-paygent-payment-main' )));
			if($this->setting_bn_39 =='yes') $this->banks = array_merge($this->banks, array('D039' => __( 'Jibun Bank', 'woocommerce-for-paygent-payment-main' )));
		}
		// Actions
		add_action( 'woocommerce_receipt_paygent_bn',                              array( $this, 'receipt_page' ) );
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
				'label'       => sprintf(__( 'Enable paygent %s Payment', 'woocommerce-for-paygent-payment-main' ), __('Bank Net', 'woocommerce-for-paygent-payment-main' )),
				'type'        => 'checkbox',
				'description' => '',
				'default'     => 'no'
			),
			'title'       => array(
				'title'       => __( 'Title', 'woocommerce-for-paygent-payment-main' ),
				'type'        => 'text',
				'description' => __( 'This controls the title which the user sees during checkout.', 'woocommerce-for-paygent-payment-main' ),
				'default'     => __( 'Bank Net (Paygent)', 'woocommerce-for-paygent-payment-main' )
			),
			'description' => array(
				'title'       => __( 'Description', 'woocommerce-for-paygent-payment-main' ),
				'type'        => 'textarea',
				'description' => __( 'This controls the description which the user sees during checkout.', 'woocommerce-for-paygent-payment-main' ),
				'default'     => sprintf(__( 'Pay with your %s via Paygent.', 'woocommerce-for-paygent-payment-main' ), __('Bank Net', 'woocommerce-for-paygent-payment-main' )),
			),
			'order_button_text' => array(
				'title'       => __( 'Order Button Text', 'woocommerce-for-paygent-payment-main' ),
				'type'        => 'textarea',
				'description' => __( 'This controls the description which the user sees during checkout.', 'woocommerce-for-paygent-payment-main' ),
				'default'     => sprintf(__( 'Proceed to Paygent %s', 'woocommerce-for-paygent-payment-main' ), __('Bank Net', 'woocommerce-for-paygent-payment-main' )),
			),
			'claim_kanji_text' => array(
				'title'       => __( 'Invoice detail (Kana)', 'woocommerce-for-paygent-payment-main' ),
				'type'        => 'text',
				'description' => __( 'This controls the description which invoice detail.', 'woocommerce-for-paygent-payment-main' ),
				'default'     => sprintf(__( 'invoice detail Via %s (kanji)', 'woocommerce-for-paygent-payment-main' ), __('Bank Net', 'woocommerce-for-paygent-payment-main' )),
			),
			'claim_kana_text' => array(
				'title'       => __( 'Invoice detail (kanji)', 'woocommerce-for-paygent-payment-main' ),
				'type'        => 'text',
				'description' => __( 'This controls the description which invoice detail.', 'woocommerce-for-paygent-payment-main' ),
				'default'     => sprintf(__( 'invoice detail Via %s (kana)', 'woocommerce-for-paygent-payment-main' ), __('Bank Net', 'woocommerce-for-paygent-payment-main' )),
			),
			'setting_bn_08' => array(
				'title'       => __( 'Set Bank Net', 'woocommerce-for-paygent-payment-main' ),
				'id'              => 'wc-paygent-bn-08',
				'type'        => 'checkbox',
				'label'       => __( 'Mitsubishi Tokyo UFJ Bank', 'woocommerce-for-paygent-payment-main' ),
				'default'     => 'yes',
			),
			'setting_bn_09' => array(
				'id'              => 'wc-paygent-bn-09',
				'type'        => 'checkbox',
				'label'       => __( 'Mitsui Sumitomo Bank', 'woocommerce-for-paygent-payment-main' ),
				'default'     => 'yes',
			),
			'setting_bn_33' => array(
				'id'              => 'wc-paygent-bn-33',
				'type'        => 'checkbox',
				'label'       => __( 'Japan Net Bank', 'woocommerce-for-paygent-payment-main' ),
				'default'     => 'yes',
			),
			'setting_bn_34' => array(
				'id'              => 'wc-paygent-bn-34',
				'type'        => 'checkbox',
				'label'       => __( 'Seven Bank', 'woocommerce-for-paygent-payment-main' ),
				'default'     => 'yes',
			),
			'setting_bn_36' => array(
				'id'              => 'wc-paygent-bn-36',
				'type'        => 'checkbox',
				'label'       => __( 'Rakuten Bank', 'woocommerce-for-paygent-payment-main' ),
				'default'     => 'yes',
			),
			'setting_bn_38' => array(
				'id'              => 'wc-paygent-bn-38',
				'type'        => 'checkbox',
				'label'       => __( 'Jushin SBI Net Bank', 'woocommerce-for-paygent-payment-main' ),
				'default'     => 'yes',
			),
			'setting_bn_39' => array(
				'id'              => 'wc-paygent-bn-39',
				'type'        => 'checkbox',
				'label'       => __( 'Jibun Bank', 'woocommerce-for-paygent-payment-main' ),
				'default'     => 'yes',
				'description' => sprintf( __( 'Please check them you are able to use %s', 'woocommerce-for-paygent-payment-main' ), __('Bank Net', 'woocommerce-for-paygent-payment-main' )),
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
		<p><?php _e( 'Please select Bank Net where you want to pay', 'woocommerce-for-paygent-payment-main' );?></p>
		<?php $this->bank_select(); ?>
		</fieldset>
	<?php }
    /**
     * Select Bank user want to pay
    */
	function bank_select() {
		?><select name="bank_code">
		<?php foreach($this->banks as $num => $value){?>
		<option value="<?php echo $num; ?>"><?php echo $value;?></option>
	<?php }?>
		</select><?php 
	}

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
		$telegram_kind = '050';
		$send_data['trading_id'] = 'wc_'.$order->id;
		$send_data['payment_id'] = '';

		$send_data['payment_amount'] = $order->order_total;
		$send_data['bank_code'] = $this->get_post( 'bank_code' );// Bank Net Company ID

		$send_data['claim_kana'] = mb_convert_encoding($this->claim_kana_text, "SJIS", "UTF-8");
		$send_data['claim_kanji'] = mb_convert_encoding($this->claim_kanji_text, "SJIS", "UTF-8");
		//Check carrier
		$ua = $_SERVER['HTTP_USER_AGENT'];
		if (preg_match('/^DoCoMo/', $ua)) {//Docomo
			$send_data['pc_mobile_type'] = '1';
		}elseif (preg_match('/^KDDI-|^UP\.Browser/', $ua)) {//au
			$send_data['pc_mobile_type'] = '2';
		}elseif (preg_match('#^J-(PHONE|EMULATOR)/|^(Vodafone/|MOT(EMULATOR)?-[CV]|SoftBank/|[VS]emulator/)#', $ua)) {//Softbank
			$send_data['pc_mobile_type'] = '3';
		}else{
			$send_data['pc_mobile_type'] = '0';
		}
		$send_data['amount'] = $order->order_total;

		$send_data['return_url'] = $this->get_return_url( $order );
		
		$paygent_request = new WC_Gateway_Paygent_Request();
		$response = $paygent_request->send_paygent_request($test_mode, $order, $telegram_kind, $send_data);

		// Check response
		if ( $response['result'] == 0 and $response['result_array']) {
			// Success
			$order->add_order_note( __( 'paygent Payment completed. Transaction ID: ' , 'woocommerce-for-paygent-payment-main' ) .  $response['result_array'][0]['payment_id'] );
			//set transaction id for Paygent Order Number
			update_post_meta( $order->id, '_transaction_id', wc_clean( $response['result_array'][0]['payment_id'] ) );
			update_post_meta( $order->id, '_paygent_bn_form', mb_convert_encoding($response['result_array'][0]['redirect_html'],"UTF-8","SJIS" ) );
			// Mark as on-hold (we're awaiting the payment)
			$order->update_status( 'on-hold', __( 'Awaiting Bank Net payment', 'woocommerce-for-paygent-payment-main' ) );

			// Return thank you redirect
			return array (
				'result'   => 'success',
				'redirect' => $this->get_return_url( $order ),
			);

		}else{
			$paygent_request->error_response($response);
		}
	}
    /**
     * Pay Form for thank you page
     */
	function thankyou_payment_form($order_id) {
		$status = get_post_status( $order_id );
		if($status == 'wc-on-hold'){
			$form = get_post_meta($order_id, '_paygent_bn_form', true);
			echo $form;
			echo __('This order was not complete. Please pay from here to bank payment', 'woocommerce-for-paygent-payment-main');
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
	function add_wc_paygent_bn_gateway( $methods ) {
		$methods[] = 'WC_Gateway_Paygent_BN';
		return $methods;
	}

	add_filter( 'woocommerce_payment_gateways', 'add_wc_paygent_bn_gateway' );

	/**
	 * Edit the available gateway to woocommerce
	 */
	function edit_available_bn_gateways( $methods ) {
		if ( isset($currency) ) {
		}else{
			$currency = get_woocommerce_currency();
		}
		if($currency !='JPY'){
		unset($methods['paygent_bn']);
		}
		return $methods;
	}

	add_filter( 'woocommerce_available_payment_gateways', 'edit_available_bn_gateways' );
