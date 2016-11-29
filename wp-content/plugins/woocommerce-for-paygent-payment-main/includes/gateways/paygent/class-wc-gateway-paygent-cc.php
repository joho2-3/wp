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
class WC_Gateway_Paygent_CC extends WC_Payment_Gateway {

	/**
	 * Constructor for the gateway.
	 *
	 * @access public
	 * @return void
	 */
	public function __construct() {

		$this->id                = 'paygent_cc';
		$this->has_fields        = false;
		$this->order_button_text = __( 'Proceed to Paygent Credit Card', 'woocommerce-for-paygent-payment-main' );
		
        // Create plugin fields and settings
		$this->init_form_fields();
		$this->init_settings();
		$this->method_title       = __( 'Paygent Credit Card Payment Gateway', 'woocommerce-for-paygent-payment-main' );
		$this->method_description = __( 'Allows payments by Paygent Credit Card in Japan.', 'woocommerce-for-paygent-payment-main' );
		$this->supports = array(
			'subscriptions',
			'products',
			'subscription_cancellation',
			'subscription_reactivation',
			'subscription_suspension',
			'subscription_amount_changes',
			'subscription_payment_method_change_customer',
			'subscription_payment_method_change_admin',
			'subscription_date_changes',
			'multiple_subscriptions',
//			'tokenization',
			'refunds',
			'default_credit_card_form'
		);
        // When no save setting error at chackout page
		if(is_null($this->title)){
			$this->title = __( 'Please set this payment at Control Panel! ', 'woocommerce-for-paygent-payment-main' ).$this->method_title;
		}
		// Get setting values
		foreach ( $this->settings as $key => $val ) $this->$key = $val;

		// Load plugin checkout credit Card icon
		if(isset($this->setting_card_vm)){
			if($this->setting_card_vm =='yes' and $this->setting_card_d =='yes' and $this->setting_card_aj =='yes'){
				$this->icon = plugins_url( 'images/paygent-cards.png' , __FILE__ );
			}elseif($this->setting_card_vm =='yes' and $this->setting_card_d =='no' and $this->setting_card_aj =='no'){
				$this->icon = plugins_url( 'images/paygent-cards-v-m.png' , __FILE__ );
			}elseif($this->setting_card_vm =='yes' and $this->setting_card_d =='yes' and $this->setting_card_aj =='no'){
				$this->icon = plugins_url( 'images/paygent-cards-v-m-d.png' , __FILE__ );
			}elseif($this->setting_card_vm =='yes' and $this->setting_card_d =='no' and $this->setting_card_aj =='yes'){
				$this->icon = plugins_url( 'images/paygent-cards-v-m-a-j.png' , __FILE__ );
			}elseif($this->setting_card_vm =='no' and $this->setting_card_d =='no' and $this->setting_card_aj =='yes'){
				$this->icon = plugins_url( 'images/paygent-cards-a-j.png' , __FILE__ );
			}elseif($this->setting_card_vm =='no' and $this->setting_card_d =='yes' and $this->setting_card_aj =='no'){
				$this->icon = plugins_url( 'images/paygent-cards-d.png' , __FILE__ );
			}elseif($this->setting_card_vm =='no' and $this->setting_card_d =='yes' and $this->setting_card_aj =='yes'){
				$this->icon = plugins_url( 'images/paygent-cards-d-a-j.png' , __FILE__ );
			}
		}

		// Actions
		add_action( 'woocommerce_update_options_payment_gateways',              array( $this, 'process_admin_options' ) );
		add_action( 'woocommerce_update_options_payment_gateways_' . $this->id, array( $this, 'process_admin_options' ) );
		if( version_compare( WOOCOMMERCE_VERSION, '2.6.0', '<' ) ){
			add_action( 'wp_enqueue_scripts',                                       array( $this, 'add_paygent_cc_scripts' ) );
		}
	}
	/**
	* Initialize Gateway Settings Form Fields.
	*/
	function init_form_fields() {

		$this->form_fields = array(
			'enabled'     => array(
				'title'       => __( 'Enable/Disable', 'woocommerce-for-paygent-payment-main' ),
				'label'       => __( 'Enable paygent Credit Card Payment', 'woocommerce-for-paygent-payment-main' ),
				'type'        => 'checkbox',
				'description' => '',
				'default'     => 'no'
			),
			'title'       => array(
				'title'       => __( 'Title', 'woocommerce-for-paygent-payment-main' ),
				'type'        => 'text',
				'description' => __( 'This controls the title which the user sees during checkout.', 'woocommerce-for-paygent-payment-main' ),
				'default'     => __( 'Credit Card (Paygent)', 'woocommerce-for-paygent-payment-main' )
			),
			'description' => array(
				'title'       => __( 'Description', 'woocommerce-for-paygent-payment-main' ),
				'type'        => 'textarea',
				'description' => __( 'This controls the description which the user sees during checkout.', 'woocommerce-for-paygent-payment-main' ),
				'default'     => __( 'Pay with your credit card via Paygent.', 'woocommerce-for-paygent-payment-main' )
			),
			'order_button_text' => array(
				'title'       => __( 'Order Button Text', 'woocommerce-for-paygent-payment-main' ),
				'type'        => 'textarea',
				'description' => __( 'This controls the description which the user sees during checkout.', 'woocommerce-for-paygent-payment-main' ),
				'default'     => __( 'Proceed to Paygent Credit Card', 'woocommerce-for-paygent-payment-main' )
			),
			'store_card_info' => array(
				'title'       => __( 'Store Card Infomation', 'woocommerce-for-paygent-payment-main' ),
				'type'        => 'checkbox',
				'label'       => __( 'Enable Store Card Infomation', 'woocommerce-for-paygent-payment-main' ),
				'default'     => 'no',
				'description' => sprintf( __( 'Store user Credit Card information in Paygent Server.(Option)', 'woocommerce-for-paygent-payment-main' )),
			),
			'setting_card_vm' => array(
				'title'       => __( 'Set Credit Card', 'woocommerce-for-paygent-payment-main' ),
				'id'              => 'wc-paygent-cc-vm',
				'type'        => 'checkbox',
				'label'       => __( 'VISA & MASTER', 'woocommerce-for-paygent-payment-main' ),
				'default'     => 'yes',
			),
			'setting_card_d' => array(
				'id'              => 'wc-paygent-cc-d',
				'type'        => 'checkbox',
				'label'       => __( 'DINNERS', 'woocommerce-for-paygent-payment-main' ),
				'default'     => 'yes',
			),
			'setting_card_aj' => array(
				'id'              => 'wc-paygent-cc-aj',
				'type'        => 'checkbox',
				'label'       => __( 'AMEX & JCB', 'woocommerce-for-paygent-payment-main' ),
				'default'     => 'yes',
				'description' => sprintf( __( 'Please check them you are able to use Credit Card', 'woocommerce-for-paygent-payment-main' )),
			),
		);
	}

	/**
	* UI - Admin Panel Options
	*/
	function admin_options() { ?>
		<h3><?php _e( 'Paygent Credit Card','woocommerce-for-paygent-payment-main' ); ?></h3>
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
		<?PHP if( version_compare( WOOCOMMERCE_VERSION, '2.6.0', '<' ) ){
			$this->credit_card_form( array( 'fields_have_names' => true ) );			
		}else{
			$payment_gateway_cc = new WC_Payment_Gateway_CC();
			$payment_gateway_cc->form();
		}
		?>
		</div>
	<?php }

	/**
	 * Process the payment and return the result.
	 */
	function process_payment( $order_id , $subscription = false) {
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
		$telegram_kind = '020';
		$send_data['trading_id'] = 'wc_'.$order->id;
		$send_data['payment_id'] = '';

		$send_data['payment_amount'] = $order->order_total;

		//Edit Expire Data
		if( version_compare( WOOCOMMERCE_VERSION, '2.6.0', '<' ) ){
			$card_valid_term = str_replace(array(" ","/" ), "", $this->get_post( 'paygent_cc-card-expiry' ) );
			$card_number = str_replace( array( ' ', '-' ), '', $this->get_post( 'paygent_cc-card-number' ));
			$card_conf_number = $this->get_post( 'paygent_cc-card-cvc' );
		}else{
			$card_valid_term = str_replace(array(" ","/" ), "", $this->get_post( '-card-expiry' ) );
			$card_number = str_replace( array( ' ', '-' ), '', $this->get_post( '-card-number' ));
			$card_conf_number = $this->get_post( '-card-cvc' );
		}
		// Create server request using stored or new payment details
		$card_user_id = 'wc'.$order->user_id;
		if ( $this->store_card_info == 'yes' or $subscription == true){
			if($this->get_post( 'paygent-use-stored-payment-info' ) == 'yes'){
				$customer_check = $this->user_has_stored_data($card_user_id);
				$send_data['stock_card_mode'] = 1;
				$send_data['customer_id'] = $card_user_id;
				$send_data['customer_card_id'] = $customer_check['result_array'][$this->get_post( 'stored-info' )]['customer_card_id'];
			}else{
				$send_data['stock_card_mode'] = 1;
				$send_data['customer_id'] = $card_user_id;
				$stored_user_card_data = $this->add_stored_user_data($card_user_id, $card_number, $card_valid_term);
				if($stored_user_card_data['result']==1){
					$order->add_order_note( __( 'Fault to stored your card info.', 'woocommerce-for-paygent-payment-main' ). $stored_user_card_data['responseCode'] .':'. mb_convert_encoding($stored_user_card_data['responseDetail'],"UTF-8","SJIS" ).':'.$order->user_id );
        			wc_add_notice( __( 'Fault to stored your card info.', 'woocommerce-for-paygent-payment-main' ), $notice_type = 'error' );
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
			$send_data['card_conf_number'] = $card_conf_number;
      	}

	  	//Payment times
		$send_data['payment_class'] = 10;//One time payment

		//3D Secure Setting
/*		if($this->tds_check=='no'){
		$order_process->reqPut('3dsecure_ryaku',1);
		}elseif($this->tds_check=='yes'){
		$order_process->reqPut('3dsecure_use_type',1);
		$order_process->reqPut('http_accept',$_SERVER['HTTP_ACCEPT']);
		$order_process->reqPut('http_user_agent',$_SERVER['HTTP_USER_AGENT']);
		$order_key = get_post_meta($order->id, '_order_key', true);
		$current_url = (empty($_SERVER['HTTPS']) ? 'http://' : 'https://') . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
		$term_url = $current_url.'/order-received/'.$order->id.'/?key='.$order_key;
		$order_process->reqPut('term_url',$term_url);
		}
*/
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

			// Return thank you redirect
			return array (
				'result'   => 'success',
				'redirect' => $this->get_return_url( $order ),
			);

		} elseif ( $response['result'] == 7 ) {//3DS
			if($response['result_array']['attempt_kbn']=1){
				//3Dセキュア未対応カードなどのエラー
				$order->add_order_note( __( 'Your card is not adapt 3D Secure ', 'woocommerce-for-paygent-payment-main' ). $response['responseCode'] .':'. mb_convert_encoding($response['responseDetail'],"UTF-8","SJIS" ) );
				wc_add_notice( __( 'Your card is not adapt 3D Secure ', 'woocommerce-for-paygent-payment-main' ), $notice_type = 'error' );
			}elseif($response['result_array']['attempt_kbn']=0){
				//3Dセキュア未加入カード
				$test_del = implode(",", $response['result_array']);
				$order->add_order_note( __( 'Your card is not Non-participation 3D Secure card.', 'woocommerce-for-paygent-payment-main' ). $response['responseCode'] .':'. $test_del .':'. mb_convert_encoding($response['responseDetail'],"UTF-8","SJIS" ) );
				wc_add_notice( __( 'Your card is not Non-participation 3D Secure card.', 'woocommerce-for-paygent-payment-main' ), $notice_type = 'error' );
			}else{
				// Mark as on-hold (we're awaiting the payment)
				$order->update_status( 'on-hold', __( '3DSecure Payment Processing.', 'woocommerce-4jp' ) );

				// Reduce stock levels
				$order->reduce_order_stock();

				// Remove cart
				WC()->cart->empty_cart();

				// Return thankyou redirect
				return array(
					'result' 	=> 'success',
					'redirect'	=> $response['result_array']['out_acs_html']
				);
			}

		} else {//System Error
			$paygent_request->error_response($response, $order);
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
		$site_id = get_option('wc-paygent-sid');
		if($site_id!=1)$send_data['site_id'] = $site_id;
		$order = wc_get_order();

		//Check test mode
		$test_mode = get_option('wc-paygent-testmode');

		$paygent_request = new WC_Gateway_Paygent_Request( $this );

		$result = $paygent_request->send_paygent_request($test_mode, $order, $telegram_kind, $send_data);
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
		<?php print_r($customer_check['result_array']);} 
	}
    /**
     * Add User card info to Paygent server
     */
    function add_stored_user_data( $user_id, $card_number, $card_valid_term) {
		include_once( 'includes/class-wc-gateway-paygent-request.php' );
	    $telegram_kind = '025';
		$send_data = array(
			'trading_id' => '',
			'customer_id'=> $user_id,
			'card_number' => $card_number,
			'card_valid_term' => $card_valid_term,
		);
		//Check test mode
		$test_mode = get_option('wc-paygent-testmode');
		$site_id = get_option('wc-paygent-sid');

		if($site_id!=1)$send_data['site_id'] = $site_id;
		$user_request = new WC_Gateway_Paygent_Request( $this );
		$result = $user_request->send_paygent_request($test_mode, $order, $telegram_kind, $send_data);
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

		$cardNumber		= $this->get_post( 'paygent_cc-card-number' );
		$cardCVC		= $this->get_post( 'paygent_cc-card-cvc' );
		$cardExpiration	= $this->get_post( 'paygent_cc-card-expiry' );

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
	
    /**
     * Include jQuery and our scripts
     */
    function add_paygent_cc_scripts() {

      if ( ! $this->user_has_stored_data( wp_get_current_user()->ID ) ) return;

      wp_enqueue_script( 'jquery' );
      wp_enqueue_script( 'edit_billing_details', plugin_dir_path( __FILE__ ) . 'js/edit_billing_details.js', array( 'jquery' ), 1.0 );

      if ( isset($this->security_check) and $this->security_check == 'yes' ) wp_enqueue_script( 'check_cvv', plugin_dir_path( __FILE__ ) . 'js/check_cvv.js', array( 'jquery' ), 1.0 );

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

	function paygent_cc_stored(){
		include_once( 'includes/class-wc-gateway-paygent-request.php' );
		$user = wp_get_current_user();

		//Check test mode
		$test_mode = get_option('wc-paygent-testmode');
		$site_id = get_option('wc-paygent-sid');

		if(isset($_POST['customer_id'])){
			$telegram_kind_del = '026';
			$send_data_del = array(
				'customer_id' => $_POST['customer_id'],
				'customer_card_id'=> $_POST['customer_card_id'],
			);
			if($site_id!=1)$send_data_del['site_id'] = $site_id;
			$user_delete = new WC_Gateway_Paygent_Request($this);
			$delete_result = $user_delete->send_paygent_request($test_mode, $order, $telegram_kind_del, $send_data_del);
		}

	    $telegram_kind = '027';
		$send_data = array(
			'trading_id' => '',
			'customer_id'=> 'wc'.$user->ID,
		);
		$woocommerce_paygent_cc = get_option('woocommerce_paygent_cc_settings');
		if($site_id!=1)$send_data['site_id'] = $site_id;
		$user_request = new WC_Gateway_Paygent_Request();
		$order = null;
		$result = $user_request->send_paygent_request($test_mode, $order, $telegram_kind, $send_data);
		echo '<h2>'.__('Stored Credit Card', 'woocommerce-for-paygent-payment-main' ).'</h2>';
		echo '<table><tr><th>No.</th><th>'.__('The last six digits', 'woocommerce-for-paygent-payment-main' ).'</th><th>'.__('Expire(mm/yy)', 'woocommerce-for-paygent-payment-main' ).'</th><th>'.__('Card Brand', 'woocommerce-for-paygent-payment-main' ).'</th><th>'.__('Delete', 'woocommerce-for-paygent-payment-main' ).'</th></tr>';
		$i = 1;
		foreach($result['result_array'] as $arr){
			echo '<tr><td>'.$i.'</td><td>'.$arr['card_number'].'</td><td>'.substr($arr['card_valid_term'],0,2).'/'.substr($arr['card_valid_term'],-2).'</td><td>'.$arr['card_brand'].'</td><td>'.subimit_button($arr['customer_id'], $arr['customer_card_id']).'</td></tr>';
			$i++;
		}
		echo '</table>';
	}

	function subimit_button($customer_id, $customer_card_id){
		$page_url_get = "http://" . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"];
		$doc = '<form action="'.$page_url_get.'" method="post">';
		$doc .= '<input type="hidden" name="customer_id" value="'.$customer_id.'">';
		$doc .= '<input type="hidden" name="customer_card_id" value="'.$customer_card_id.'">';
		$doc .= '<input type="submit" value="削除する">';
		$doc .= '</form>';
		return $doc;
	}
	add_action( 'woocommerce_after_my_account', 'paygent_cc_stored', 1);

	/**
	 * Add the gateway to woocommerce
	 */
function add_wc_paygent_cc_gateway( $methods ) {
	$subscription_support_enabled = false;
	if ( class_exists( 'WC_Subscriptions_Order' ) && function_exists( 'wcs_create_renewal_order' ) ) {
		$subscription_support_enabled = true;
	}
	if ( $subscription_support_enabled ) {
		$methods[] = 'WC_Gateway_Paygent_CC_Addons';
	} else {
		$methods[] = 'WC_Gateway_Paygent_CC';
	}
	return $methods;
}

add_filter( 'woocommerce_payment_gateways', 'add_wc_paygent_cc_gateway' );

/**
 * Edit the available gateway to woocommerce
 */
function edit_available_gateways( $methods ) {
	if ( isset($currency) ) {
	}else{
		$currency = get_woocommerce_currency();
	}
	if($currency !='JPY'){
		unset($methods['paygent_cc']);
	}
	return $methods;
}

	add_filter( 'woocommerce_available_payment_gateways', 'edit_available_gateways' );
