<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Paygent Payment Gateway
 *
 * Provides a Paygent Convenience Store Payment Gateway.
 *
 * @class 			WC_Paygent
 * @extends		WC_Gateway_Paygent_CS
 * @version		1.1.0
 * @package		WooCommerce/Classes/Payment
 * @author			Artisan Workshop
 */
class WC_Gateway_Paygent_CS extends WC_Payment_Gateway {


	/**
	 * Constructor for the gateway.
	 *
	 * @access public
	 * @return void
	 */
	public function __construct() {

		$this->id                = 'paygent_cs';
		$this->has_fields        = false;
		$this->order_button_text = __( 'Proceed to Paygent Convenience Store', 'woocommerce-for-paygent-payment-main' );

		//Paygent Setting IDs
		$this->merchant_id = get_option('wc-paygent-mid');
		$this->connect_id = get_option('wc-paygent-cid');
		$this->connect_password = get_option('wc-paygent-cpass');
		$this->site_id = get_option('wc-paygent-sid');

        // Create plugin fields and settings
		$this->init_form_fields();
		$this->init_settings();
		$this->method_title       = __( 'Paygent Convenience Store Payment Gateway', 'woocommerce-for-paygent-payment-main' );
		$this->method_description = __( 'Allows payments by Paygent Convenience Store in Japan.', 'woocommerce-for-paygent-payment-main' );

        // When no save setting error at chackout page
		if(is_null($this->title)){
			$this->title = __( 'Please set this payment at Control Panel! ', 'woocommerce-for-paygent-payment-main' ).$this->method_title;
		}

		// Get setting values
		foreach ( $this->settings as $key => $val ) $this->$key = $val;

        // Set Convenience Store
		$this->cs_stores = array();
		if(isset($this->setting_cs_se)){
			if($this->setting_cs_se =='yes') $this->cs_stores = array_merge($this->cs_stores, array('00C001' => __( 'Seven Eleven', 'woocommerce-for-paygent-payment-main' )));
			if($this->setting_cs_lm =='yes') $this->cs_stores = array_merge($this->cs_stores, array('00C002' => __( 'Lowson', 'woocommerce-for-paygent-payment-main' ), '00C004' => __( 'Mini Stop', 'woocommerce-for-paygent-payment-main' )));
			if($this->setting_cs_f =='yes') $this->cs_stores = array_merge($this->cs_stores, array('00C005' => __( 'Family Mart', 'woocommerce-for-paygent-payment-main' )));
			if($this->setting_cs_sm =='yes') $this->cs_stores = array_merge($this->cs_stores, array('00C016' => __( 'Seicomart', 'woocommerce-for-paygent-payment-main' )));
			if($this->setting_cs_ctd =='yes') $this->cs_stores = array_merge($this->cs_stores, array('00C006' => __( 'Circle K', 'woocommerce-for-paygent-payment-main' ),'00C007' => __( 'Thanks', 'woocommerce-for-paygent-payment-main' ), '00C014' => __( 'Daily Yamazaki', 'woocommerce-for-paygent-payment-main' )));
		}

		// Actions
		add_action( 'woocommerce_receipt_paygent_cv',                              array( $this, 'receipt_page' ) );
		add_action( 'woocommerce_update_options_payment_gateways',              array( $this, 'process_admin_options' ) );
		add_action( 'woocommerce_update_options_payment_gateways_' . $this->id, array( $this, 'process_admin_options' ) );
		// Customer Emails
		add_action( 'woocommerce_email_before_order_table', array( $this, 'email_instructions' ), 10, 3 );
	}
	/**
	 * Initialize Gateway Settings Form Fields.
	*/
	function init_form_fields() {

		$this->form_fields = array(
			'enabled'     => array(
				'title'       => __( 'Enable/Disable', 'woocommerce-for-paygent-payment-main' ),
				'label'       => __( 'Enable paygent Convenience Store Payment', 'woocommerce-for-paygent-payment-main' ),
				'type'        => 'checkbox',
				'description' => '',
				'default'     => 'no'
			),
			'title'       => array(
				'title'       => __( 'Title', 'woocommerce-for-paygent-payment-main' ),
				'type'        => 'text',
				'description' => __( 'This controls the title which the user sees during checkout.', 'woocommerce-for-paygent-payment-main' ),
				'default'     => __( 'Convenience Store (Paygent)', 'woocommerce-for-paygent-payment-main' )
			),
			'description' => array(
				'title'       => __( 'Description', 'woocommerce-for-paygent-payment-main' ),
				'type'        => 'textarea',
				'description' => __( 'This controls the description which the user sees during checkout.', 'woocommerce-for-paygent-payment-main' ),
				'default'     => __( 'Pay at Convenience Store via Paygent.', 'woocommerce-for-paygent-payment-main' )
			),
			'order_button_text' => array(
				'title'       => __( 'Order Button', 'woocommerce-for-paygent-payment-main' ),
				'type'        => 'textarea',
				'description' => __( 'This controls the order button which the user sees during checkout.', 'woocommerce-for-paygent-payment-main' ),
				'default'     => __( 'Proceed to Paygent Convenience Store', 'woocommerce-for-paygent-payment-main' )
			),
			'setting_cs_se' => array(
				'title'       => __( 'Set Convenience Store', 'woocommerce-for-paygent-payment-main' ),
				'id'              => 'wc-paygent-cs-se',
				'type'        => 'checkbox',
				'label'       => __( 'Seven Eleven', 'woocommerce-for-paygent-payment-main' ),
				'default'     => 'yes',
			),
			'setting_cs_lm' => array(
				'id'              => 'wc-paygent-cs-lm',
				'type'        => 'checkbox',
				'label'       => __( 'Lowson & Mini Stop', 'woocommerce-for-paygent-payment-main' ),
				'default'     => 'yes',
			),
			'setting_cs_f' => array(
				'id'              => 'wc-paygent-cs-f',
				'type'        => 'checkbox',
				'label'       => __( 'Family Mart', 'woocommerce-for-paygent-payment-main' ),
				'default'     => 'yes',
			),
			'setting_cs_sm' => array(
				'id'              => 'wc-paygent-cs-sm',
				'type'        => 'checkbox',
				'label'       => __( 'Seicomart', 'woocommerce-for-paygent-payment-main' ),
				'default'     => 'yes',
			),
			'setting_cs_ctd' => array(
				'id'              => 'wc-paygent-cs-ctd',
				'type'        => 'checkbox',
				'label'       => __( 'Circle K & Thanks & Daily Yamazaki', 'woocommerce-for-paygent-payment-main' ),
				'default'     => 'yes',
				'description' => sprintf( __( 'Please check them you are able to use Convenience Store', 'woocommerce-for-paygent-payment-main' )),
			),
		);
	}

	function cs_select() {
		?><select name="cvs_company_id">
		<?php foreach($this->cs_stores as $num => $value){?>
		<option value="<?php echo $num; ?>"><?php echo $value;?></option>
	<?php }?>
		</select><?php 
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
		<p><?php _e( 'Please select Convenience Store where you want to pay', 'woocommerce-for-paygent-payment-main' );?></p>
		<?php $this->cs_select(); ?>
		</fieldset>
	<?php }

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

		$send_data = array();

		//Check test mode
		$test_mode = get_option('wc-paygent-testmode');

		// Send request and get response from server
		//Common header
		$telegram_kind = '030';//Conviniense Store Payment
		$send_data['trading_id'] = 'wc_'.$order->id;
		$send_data['payment_id'] = '';

		$send_data['payment_amount'] = $order->order_total;
		// Customer Name
		$send_data['customer_family_name'] = mb_convert_encoding($order->billing_last_name, "SJIS", "UTF-8");
		$send_data['customer_name'] = mb_convert_encoding($order->billing_first_name, "SJIS", "UTF-8");
		$send_data['customer_tel'] = str_replace("-","",$order->billing_phone);

		$send_data['cvs_company_id'] = $this->get_post( 'cvs_company_id' );// Convenience Store Company ID
		$send_data['sales_type'] = 1;// Payment before shipping

		$paygent_request = new WC_Gateway_Paygent_Request();
		$response = $paygent_request->send_paygent_request($test_mode, $order, $telegram_kind, $send_data);
		$this->result_array = $response['result_array'];

		// Check response
		if ( $response['result'] == 0 ) {
		// Success
			$order->add_order_note( __( 'Convenience store Payment completed. Transaction ID: ' , 'woocommerce-for-paygent-payment-main' ) . 'wc_'.$order->id . __('. Receipt Number : ', 'woocommerce-for-paygent-payment-main' ) .$this->result_array[0]['receipt_number'].__('. Enable CVS : ', 'woocommerce-for-paygent-payment-main' ) .$this->convinient_store($this->result_array[0]['usable_cvs_company_id']) );

			// Mark as on-hold (we're awaiting the payment)
			$order->update_status( 'on-hold', __( 'Awaiting Convenience store payment', 'woocommerce-4jp' ) );

			// Reduce stock levels
			$order->reduce_order_stock();

			// Remove cart
			WC()->cart->empty_cart();

			//set transaction id for Paygent Order Number
			update_post_meta( $order->id, '_transaction_id', wc_clean( $response['result_array'][0]['payment_id'] ) );
			update_post_meta( $order->id, '_paygent_cvs_id', wc_clean( $response['result_array'][0]['usable_cvs_company_id'] ) );
			update_post_meta( $order->id, '_paygent_receipt_number', wc_clean( $response['result_array'][0]['receipt_number'] ) );

			// Return thank you redirect
			return array (
				'result'   => 'success',
				'redirect' => $this->get_return_url( $order ),
			);

		} else if ( $response['result'] == 1 ) {//System Error
			// Other transaction error
			$order->add_order_note( __( 'Paygent Payment failed. Sysmte Error: ', 'woocommerce-for-paygent-payment-main' ) . $response['responseCode'] .':'. mb_convert_encoding($response['responseDetail'],"UTF-8","SJIS" ).':'.'wc_'.$order->id );
			wc_add_notice( __( 'Sorry, there was an error: ', 'woocommerce-for-paygent-payment-main' ) . $response['responseCode'] , $notice_type = 'error');
		} else {
			// No response or unexpected response
			$order->add_order_note( __( "Paygent Payment failed. Some trouble happened.", 'woocommerce-for-paygent-payment-main' ). $response['result'] .':'.$response['responseCode'] .':'. mb_convert_encoding($response['responseDetail'],"UTF-8","SJIS").':'.'wc_'.$order->id );
			wc_add_notice(__( 'No response from payment gateway server. Try again later or contact the site administrator.', 'woocommerce-for-paygent-payment-main' ). $response['responseCode'] , $notice_type = 'error');
		}
	}

	function receipt_page( $order ) {
		echo '<p>' . __( 'Thank you for your order.', 'woocommerce-for-paygent-payment-main' ) . '</p>';
	}

	/**
	 * Include jQuery and our scripts
	*/
	function add_paygent_cs_scripts() {
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
	public function convinient_store( $usable_cvs_company_id ){
		$csv_ids = explode('-', $usable_cvs_company_id);
		$csv_names = $this->cs_stores;
		$csv_name_list = '';
		foreach($csv_ids as $csv_id){
			$csv_name_list .= $csv_names[$csv_id].' ';
		}
		return $csv_name_list;
	}
    /**
     * Add content to the WC emails For Convenient Infomation.
     *
     * @access public
     * @param WC_Order $order
     * @param bool $sent_to_admin
     * @param bool $plain_text
     * @return void
     */
    public function email_instructions( $order, $sent_to_admin, $plain_text = false ) {
    	if ( ! $sent_to_admin && 'paygent_cs' === $order->payment_method && 'on-hold' === $order->status ) {
			if ( $this->instructions ) {
				echo wpautop( wptexturize( $this->instructions ) ) . PHP_EOL;
			}
			$this->paygent_cs_details( $order->id );
		}
    }

    /**
     * Get Convinience Store Payment details and place into a list format
     */
    private function paygent_cs_details( $order_id = '' ) {
		$cvs_array = $this->cs_stores;
		$res = $this->result_array[0];
		if(strstr($res['usable_cvs_company_id'], '-')){
			$csv_companies = explode("-", $res['usable_cvs_company_id']);
			foreach($csv_companies as $csv_company){
				$usable_cvs_company .= $cvs_array[$csv_company].' ';
			}
		}else{
			$usable_cvs_company = $cvs_array[$res['usable_cvs_company_id']];
		}
		$payment_limit_date = substr($res['payment_limit_date'], 0, 4).'/'.substr($res['payment_limit_date'], 4, 2).'/'.substr($res['payment_limit_date'], -2);
		echo '<h3>' . __( 'Convenience store payment details', 'woocommerce-for-paygent-payment-main' ) . '</h3>' . PHP_EOL;
		if($res['usable_cvs_company_id'] == '00C001'){
			echo '<p>'. __( 'Payment slip number : ', 'woocommerce-for-paygent-payment-main' ) .$res['receipt_number'].'<br />'.PHP_EOL
			. __( 'URL : ', 'woocommerce-for-paygent-payment-main' ).$res['receipt_print_url'].'<br />'.PHP_EOL
			. __( 'Convenience store : ', 'woocommerce-for-paygent-payment-main' ).$usable_cvs_company.'<br />'.PHP_EOL
			. __( 'limit Date : ', 'woocommerce-for-paygent-payment-main') .$payment_limit_date.'<br />'.PHP_EOL
			.'<div style="border:1px solid #737373;padding:0 15px;"><h4>'. __( 'For Seven Eleven Users', 'woocommerce-for-paygent-payment-main' ).'</h4>'.PHP_EOL
			.'<p>'. __( '商品購入時に表示される払込票をプリントアウトし（もしくは「払込票番号」（13桁）をメモして）、セブンイレブン店舗へ行きます。店頭レジにて「インターネット代金の支払い」と伝え、払込票でお支払いいただくか、「払込票番号」を伝えお支払いください。', 'woocommerce-for-paygent-payment-main') .'</p></div>'.PHP_EOL;
		}else{
			echo '<p>'. __( 'Receipt Number : ', 'woocommerce-for-paygent-payment-main' ) .$res['receipt_number'].'<br />'.PHP_EOL
			. __( 'Convenience store : ', 'woocommerce-for-paygent-payment-main' ).$usable_cvs_company.'<br />'.PHP_EOL
			. __( 'limit Date : ', 'woocommerce-for-paygent-payment-main') .$payment_limit_date.'<br />'.PHP_EOL;
		}
		//Lowson & MiniStop Infomation
		if(strstr($res['usable_cvs_company_id'], '00C002') or strstr($res['usable_cvs_company_id'], '00C004')){
			echo '<div style="border:1px solid #737373;padding:0 15px;"><h4>'. __( 'For Lowson & MiniStop Users', 'woocommerce-for-paygent-payment-main' ).'</h4>'.PHP_EOL
			.'<p>'. __( '商品購入時にECサイトより通知される「お客様番号（上記支払い番号）」と「確認番号(400008)」（または「お支払い受付番号」）をメモして、ローソン又はミニストップ店舗へ行きます。店内に設置されているマルチメディア端末Loppii又はMINISTOPLoppiに番号を入力し、 発券される申込券でレジにてお支払いください。', 'woocommerce-for-paygent-payment-main' ).'</p></div>'.PHP_EOL;
		}
		//Family Mart Infomation
		if(strstr($res['usable_cvs_company_id'], '00C005')){
			print '<div style="border:1px solid #737373;padding:0 15px;"><h4>'. __( 'For Family Mart Users', 'woocommerce-for-paygent-payment-main' ).'</h4>'.PHP_EOL
			.'<p>'. __( '商品購入時にECサイトより通知される「収納番号（上記支払い番号）」（または「お客様番号」と「確認番号」）をメモして、ファミリーマート店舗へ行きます。店内に設置されているマルチメディア端末Famiportに番号を入力し、発券される申込券でレジにてお支払いください。', 'woocommerce-for-paygent-payment-main' ).'</p></div>'.PHP_EOL;
		}
		//SeicoMart Infomation
		if(strstr($res['usable_cvs_company_id'], '00C016') or strstr($res['usable_cvs_company_id'], '00C004')){
			print '<div style="border:1px solid #737373;padding:0 15px;"><h4>'. __( 'For Seicomart Users', 'woocommerce-for-paygent-payment-main' ).'</h4>'.PHP_EOL
			.'<p>'. __( '商品購入時にECサイトより通知される「お支払い受付番号（上記支払い番号）」をメモして、セイコーマート店舗へ行きます。店内に設置されているマルチメディア端末クラブステーションに番号を入力し、発券される申込券でレジにてお支払いください。', 'woocommerce-for-paygent-payment-main' ).'</p></div>'.PHP_EOL;
		}
		//Circle K & Thanks & Daily Yamazaki Infomation
		if(strstr($res['usable_cvs_company_id'], '00C06') or strstr($res['usable_cvs_company_id'], '00C007') or strstr($res['usable_cvs_company_id'], '00C014')){
			echo '<div style="border:1px solid #737373;padding:0 15px;"><h4>'. __( 'For Circle K & Thanks & Daily Yamazaki Users', 'woocommerce-for-paygent-payment-main' ).'</h4>'.PHP_EOL
			.'<p>'. __( '商品購入時にECサイトより通知される「オンライン決済番号/決済番号（上記支払い番号）」をメモして、各コンビニ店舗へ行きます。<br /><br />
<b>・サークルK、サンクスでお支払いのお客様</b><br />
店内に設置されているマルチメディア端末Kステーションに番号を入力し、発券される申込券でレジにてお支払いください。<br /><br />
<b>・デイリーヤマザキでお支払いのお客様</b><br />
店頭レジにて「オンライン決済」と伝え、レジのお客様用画面に「決済番号」を入力し、代金をお支払いください。', 'woocommerce-for-paygent-payment-main' ).'</p></div>'.PHP_EOL;
		}
    }

}
/**
 * Add the gateway to woocommerce
 */
function add_wc_paygent_cs_gateway( $methods ) {
	$methods[] = 'WC_Gateway_Paygent_CS';
	return $methods;
}

add_filter( 'woocommerce_payment_gateways', 'add_wc_paygent_cs_gateway' );

/**
 * Edit the available gateway to woocommerce
 */
function edit_available_gateways_cs( $methods ) {
	$currency = get_woocommerce_currency();
	if($currency !='JPY'){
	unset($methods['paygent_cs']);
	}
	return $methods;
}

add_filter( 'woocommerce_available_payment_gateways', 'edit_available_gateways_cs' );

/**
 * Get Convini Payment details and place into a list format
 */
function paygent_cs_myaccount_detail( $order ){
	global $woocommerce;
	global $wpdb;

	$cs_stores = array(
		'00C001' => __( 'Seven Eleven', 'woocommerce-for-paygent-payment-main' ),
		'00C002' => __( 'Lowson', 'woocommerce-for-paygent-payment-main' ),
		'00C004' => __( 'Mini Stop', 'woocommerce-for-paygent-payment-main' ),
		'00C005' => __( 'Family Mart', 'woocommerce-for-paygent-payment-main' ),
		'00C016' => __( 'Seicomart', 'woocommerce-for-paygent-payment-main' ),
		'00C006' => __( 'Circle K', 'woocommerce-for-paygent-payment-main' ),
		'00C007' => __( 'Thanks', 'woocommerce-for-paygent-payment-main' ),
		'00C014' => __( 'Daily Yamazaki', 'woocommerce-for-paygent-payment-main' )
	);

	$payment_setting = get_option('woocommerce_paygent_cs_settings');
	$cvs_id = get_post_meta( $order->id, '_paygent_cvs_id', true );
	$receipt_number = get_post_meta( $order->id, '_paygent_receipt_number', true);
	$transaction_id = get_post_meta( $order->id, '_transaction_id', true);

	if( get_post_meta( $order->id, '_payment_method', true ) == 'paygent_cs' and isset($cvs_id)){
		echo '<header class="title"><h3>'.__('Payment Detail', 'woocommerce-for-paygent-payment-main').'</h3></header>';
		echo '<table class="shop_table order_details">';
		echo '<tr><th>'.__('CVS Payment', 'woocommerce-for-paygent-payment-main').'</th><td>'.$cs_stores[$cvs_id].'</td></tr>'.PHP_EOL;
		echo '<tr><th>'.__( 'Receipt number', 'woocommerce-for-paygent-payment-main' ).'</th><td>'.$receipt_number.'</td></tr>'.PHP_EOL;
		echo '</table>';
	}
}
add_action( 'woocommerce_order_details_after_order_table', 'paygent_cs_myaccount_detail', 10, 1);
