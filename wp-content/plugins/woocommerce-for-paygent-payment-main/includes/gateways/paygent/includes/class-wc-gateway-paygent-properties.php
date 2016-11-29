<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Generates Properties to send to Paygent
 */
class WC_Gateway_Paygent_Properties {

	/**
	 * Properties URL Setting
	 * @var WC_Gateway_Paygent
	 */
	public function properties_url($mode, $paygent_testmode){
		if($mode==1){
			$paygent_url = 'https://sandbox.paygent.co.jp/';
		}elseif($paygent_testmode == 1){
			$paygent_url = 'https://mdev2.paygent.co.jp/';
		}else{
			$paygent_url = 'https://module.paygent.co.jp/';
		}
		return $paygent_url;
	}
}
