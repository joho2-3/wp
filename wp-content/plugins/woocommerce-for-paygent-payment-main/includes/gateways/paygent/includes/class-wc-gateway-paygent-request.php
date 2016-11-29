<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Generates requests to send to Paygent
 */
class WC_Gateway_Paygent_Request {

	/**
	 * Pointer to gateway making the request
	 * @var WC_Gateway_Paygent
	 */
//	protected $gateway;

	/**
	 * Constructor
	 * @param WC_Gateway_Paygent $gateway
	 */
	public function __construct() {
		//Paygent Setting IDs
		$this->merchant_id = get_option('wc-paygent-mid');
		$this->connect_id = get_option('wc-paygent-cid');
		$this->connect_password = get_option('wc-paygent-cpass');
		$this->merchant_test_id = get_option('wc-paygent-test-mid');
		$this->connect_test_id = get_option('wc-paygent-test-cid');
		$this->connect_test_password = get_option('wc-paygent-test-cpass');
		$this->site_id = get_option('wc-paygent-sid');
	}

	/**
	 * Get the Paygent request Post data for an order
	 * @param  WC_Order  $order
	 * @param  boolean $test_mode
	 * @return string
	 */
	public function send_paygent_request($test_mode, $order, $telegram_kind, $send_data) {
		$data = $this->merchand_data($test_mode);
		$process = new PaygentB2BModule();
		$process->init();
		$process->reqPut('merchant_id',$data['merchant_id']);
		$process->reqPut('connect_id',$data['connect_id']);
		$process->reqPut('connect_password',$data['connect_password']);
		$process->reqPut('telegram_kind',$telegram_kind);
		$process->reqPut('telegram_version','1.0');

		//set send_data to reqPut
		foreach($send_data as $key => $value){
			$process->reqPut($key,$value);
		}
		$result = $process->post();

		$res_array = array();
		while($process->hasResNext()){
			$res_array[] = $process->resNext();
		}

		$result_data = array(
			"result" => $process->getResultStatus(),
			"responseCode" =>$process->getResponseCode(),
			"responseDetail"=> $process->getResponseDetail(),
			"result_array"=> $res_array
		);

      return $result_data;
	}

	/**
	 * Get the Paygent request URL for an order
	 * @param  WC_Order  $order
	 * @param  boolean $sandbox
	 * @return string
	 */
	public function merchand_data($test_mode){
		if($test_mode == '1'){
			$data['merchant_id'] = $this->merchant_test_id;
			$data['connect_id'] = $this->connect_test_id;
			$data['connect_password'] = $this->connect_test_password;
		}elseif($test_mode == 'connect_test'){
			$data['merchant_id'] = '33323';
			$data['connect_id'] = 'test33323';
			$data['connect_password'] = 'H51eYj3AI';
		}else{
			$data['merchant_id'] = $this->merchant_id;
			$data['connect_id'] = $this->connect_id;
			$data['connect_password'] = $this->connect_password;
		}
		return $data;
	}
	/**
	 * Get the Paygent request URL for an order
	 * @param  Response  $response
	 * @return string
	 */
	public function error_response($response, $order){
		if ( $response['result'] == 1 ) {//System Error
			// Other transaction error
			$order->add_order_note( __( 'paygent Payment failed. Sysmte Error: ', 'woocommerce-for-paygent-payment-main' ) . $response['responseCode'] .':'. mb_convert_encoding($response['responseDetail'],"UTF-8","SJIS" ).':'.'wc_'.$order->id );
			if(is_checkout())wc_add_notice( __( 'Sorry, there was an error: ', 'woocommerce-for-paygent-payment-main' ) . $response['responseCode'], $notice_type = 'error' );
		} else {
			// No response or unexpected response
			$order->add_order_note( __( "paygent Payment failed. Some trouble happened.", 'woocommerce-for-paygent-payment-main' ). $response['result'] .':'.$response['responseCode'] .':'. mb_convert_encoding($response['responseDetail'],"UTF-8","SJIS").':'.'wc_'.$order->id );
			if(is_checkout())wc_add_notice( __( 'No response from payment gateway server. Try again later or contact the site administrator.', 'woocommerce-for-paygent-payment-main' ). $response['responseCode'], $notice_type = 'error' );
		}
	}
}
