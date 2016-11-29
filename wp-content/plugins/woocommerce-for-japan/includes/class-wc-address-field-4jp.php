<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
/**
 * Plugin Name: WooCommerce For Japan

 * @package woocommerce-for-japan
 * @category Address for Japan
 * @author Artisan Workshop
 */

class AddressField4jp{
	
	/**
	 * __construct function.
	 *
	 * @access public
	 * @return void
	 */
	function __construct() {
        // MyPage Edit And Checkout fields.
		add_filter( 'woocommerce_default_address_fields',array( &$this,  'address_fields'));
		add_filter( 'woocommerce_billing_fields',array( &$this,  'billing_address_fields'));
		add_filter( 'woocommerce_shipping_fields',array( &$this,  'shipping_address_fields'));
		add_filter( 'woocommerce_formatted_address_replacements', array( &$this, 'address_replacements'),10,2);
		add_filter( 'woocommerce_localisation_address_formats', array( &$this, 'address_formats'));
		//My Account Display for address
		add_filter( 'woocommerce_my_account_my_address_formatted_address', array( &$this, 'formatted_address'),10,3);//template/myaccount/my-address.php
		//Check Out Display for address
		add_filter( 'woocommerce_order_formatted_billing_address', array( &$this, 'wc4jp_billing_address'),10,2);//includes/abstract/abstract-wc-order.php
		add_filter( 'woocommerce_order_formatted_shipping_address', array( &$this, 'wc4jp_shipping_address'),10,2);//includes/abstract/abstract-wc-order.php
		//include get_order function
		add_filter( 'woocommerce_get_order_address', array( &$this, 'wc4jp_get_order_address'),10,3);//includes/abstract/abstract-wc-order.php
		//Admin CSS file 
		add_action( 'admin_enqueue_scripts', array( &$this, 'load_custom_wc4jp_admin_style') ,20);

		// Admin Edit Address
		add_filter( 'woocommerce_admin_billing_fields', array( &$this, 'admin_billing_address_fields'));
		add_filter( 'woocommerce_admin_shipping_fields', array( &$this, 'admin_shipping_address_fields'));
		add_filter( 'woocommerce_customer_meta_fields', array( &$this, 'admin_customer_meta_fields'));
	}
	//Default address fields
    public function address_fields( $fields ) {
		$fields = array(
			'country' => array(
				'type'     => 'country',
				'label'    => __( 'Country', 'woocommerce-for-japan' ),
				'required' => true,
				'class'    => array( 'form-row-wide', 'address-field', 'update_totals_on_change' ),
			),
			'last_name'          => array(
				'label'    => __( 'Last Name', 'woocommerce-for-japan' ),
				'required' => true,
				'class'    => array( 'form-row-first' ),
			),
			'first_name' => array(
				'label'    => __( 'First Name', 'woocommerce-for-japan' ),
				'required' => true,
				'class'    => array( 'form-row-last' ),
				'clear'    => true
			),
			'yomigana_last_name' => array(
				'label'    => __( 'Last Name (Yomigana)', 'woocommerce-for-japan' ),
				'required' => true,
				'class'    => array( 'form-row-first' ),
			),
			'yomigana_first_name' => array(
				'label'    => __( 'First Name (Yomigana)', 'woocommerce-for-japan' ),
				'required' => true,
				'class'    => array( 'form-row-last' ),
				'clear'    => true
			),
			'company' => array(
				'label' => __( 'Company Name', 'woocommerce-for-japan' ),
				'class' => array( 'form-row-wide' ),
			),
			'postcode' => array(
				'label'       => __( 'Postcode / Zip', 'woocommerce-for-japan' ),
				'placeholder' => _x( '123-4567', 'placeholder', 'woocommerce-for-japan' ),
				'required'    => true,
				'class'       => array( 'form-row-first', 'address-field' ),
				'validate'    => array( 'postcode' )
			),
			'state' => array(
				'type'        => 'state',
				'label'       => __( 'Prefecture', 'woocommerce-for-japan' ),
				'required'    => true,
				'class'       => array( 'form-row-last', 'address-field' ),
				'validate'    => array( 'state' ),
				'clear'       => true
			),
			'city' => array(
				'label'       => __( 'Town / City', 'woocommerce-for-japan' ),
				'placeholder' => __( 'Town / City', 'woocommerce-for-japan' ),
				'required'    => true,
				'class'       => array( 'form-row-wide', 'address-field' )
			),
			'address_1' => array(
				'label'       => __( 'Address', 'woocommerce-for-japan' ),
				'placeholder' => _x( 'Street address', 'placeholder', 'woocommerce-for-japan' ),
				'required'    => true,
				'class'       => array( 'form-row-wide', 'address-field' )
			),
			'address_2' => array(
				'placeholder' => _x( 'Apartment, suite, unit etc. (optional)', 'placeholder', 'woocommerce-for-japan' ),
				'class'       => array( 'form-row-wide', 'address-field' ),
				'required'    => false
			),
		);
		if(!get_option( 'wc4jp-yomigana'))unset($fields['yomigana_last_name'],$fields['yomigana_first_name']);
		return $fields;
	}
		// Billing/Shipping Specific
    public function billing_address_fields( $fields ) {
		$address_fields = $fields;
		$address_fields['billing_state'] = array(
			'type'        => 'state',
			'label'       => __( 'Prefecture', 'woocommerce-for-japan' ),
			'required'    => true,
			'class'       => array( 'form-row-last', 'address-field' ),
			'clear'       => true,
			'validate'    => array( 'state' )
		);
		$address_fields['billing_email'] = array(
			'label' 		=> __( 'Email Address', 'woocommerce-for-japan' ),
			'required' 		=> true,
			'class' 		=> array( 'form-row-first' ),
			'validate'		=> array( 'email' ),
		);
		$address_fields['billing_phone'] = array(
			'label' 		=> __( 'Billing Phone', 'woocommerce-for-japan' ),
			'required' 		=> true,
			'class' 		=> array( 'form-row-last' ),
			'clear'			=> true,
			'validate'		=> array( 'phone' ),
		);
		if(!get_option( 'wc4jp-company-name'))unset($address_fields['billing_company']);
		return $address_fields;
	}
    public function shipping_address_fields( $fields ) {
		$address_fields = $fields;

		$address_fields['shipping_state'] = array(
			'type'        => 'state',
			'label'       => __( 'Prefecture', 'woocommerce-for-japan' ),
			'required'    => true,
			'class'       => array( 'form-row-last', 'address-field' ),
			'clear'       => true,
			'validate'    => array( 'state' )
		);
		$address_fields['shipping_phone'] = array(
			'label' 		=> __( 'Shipping Phone', 'woocommerce-for-japan' ),
			'required' 		=> true,
			'class' 		=> array( 'form-row-wide' ),
			'clear'			=> true,
			'validate'		=> array( 'phone' ),
		);
		if(!get_option( 'wc4jp-company-name'))unset($address_fields['shipping_company']);
		return $address_fields;
	}

    public function address_replacements( $fields, $args ) {
		$fields['{name}'] = $args['last_name'] . ' ' . $args['first_name'];
		$fields['{name_upper}'] = strtoupper( $args['last_name'] . ' ' . $args['first_name'] );
		if(get_option( 'wc4jp-yomigana')){
			$fields['{yomigana_last_name}'] = $args['yomigana_last_name'];
			$fields['{yomigana_first_name}'] = $args['yomigana_first_name'];
		}
		$fields['{phone}'] = $args['phone'];

		return $fields;
	}
	public function address_formats( $fields ) {
		
		if(get_option( 'wc4jp-company-name') and get_option( 'wc4jp-yomigana')){
			$fields['JP'] = "〒{postcode}\n{state}{city}{address_1}\n{address_2}\n{company}\n{yomigana_last_name} {yomigana_first_name}\n{last_name} {first_name}\n {phone}\n {country}";
		}
		if(!get_option( 'wc4jp-company-name') and get_option( 'wc4jp-yomigana')){
			$fields['JP'] = "〒{postcode}\n{state}{city}{address_1}\n{address_2}\n{yomigana_last_name} {yomigana_first_name}\n{last_name} {first_name}\n {phone}\n {country}";
		}
		if(!get_option( 'wc4jp-company-name') and !get_option( 'wc4jp-yomigana')){
			$fields['JP'] = "〒{postcode}\n{state}{city}{address_1}\n{address_2}\n{last_name} {first_name}\n {phone}\n {country}";
		}
		return $fields;
	}
	public function formatted_address( $fields, $customer_id, $name) {
		$fields['yomigana_first_name']  = get_user_meta( $customer_id, $name . '_yomigana_first_name', true );
		$fields['yomigana_last_name']  = get_user_meta( $customer_id, $name . '_yomigana_last_name', true );
		$fields['phone']  = get_user_meta( $customer_id, $name . '_phone', true );

		return $fields;
	}
	public function wc4jp_billing_address( $fields, $args) {
		$fields['yomigana_first_name'] = $args->billing_yomigana_first_name;
		$fields['yomigana_last_name'] = $args->billing_yomigana_last_name;
		$fields['phone'] = $args->billing_phone;

		return $fields;
	}
	public function wc4jp_shipping_address( $fields, $args) {
		$fields['yomigana_first_name'] = $args->shipping_yomigana_first_name;
		$fields['yomigana_last_name'] = $args->shipping_yomigana_last_name;
		$fields['phone'] = $args->shipping_phone;

		return $fields;
	}
	public function wc4jp_get_order_address( $address, $type, $args ){
		if ( 'billing' === $type ) {
			$address['yomigana_first_name'] =$args->billing_yomigana_first_name;
			$address['yomigana_last_name'] =$args->billing_yomigana_last_name;
		} else {
			$address['yomigana_first_name'] =$args->shipping_yomigana_first_name;
			$address['yomigana_last_name'] =$args->shipping_yomigana_last_name;
			$address['phone'] = $args->shipping_phone;
		}
		return $address;
	}

	//Admin CSS file function
	public function load_custom_wc4jp_admin_style() {
		wp_register_style( 'custom_wc4jp_admin_css', plugins_url() . '/woocommerce-for-japan/includes/views/css/admin-wc4jp.css', false, '1.0.0' );
		wp_enqueue_style( 'custom_wc4jp_admin_css' );
	}

    public function admin_billing_address_fields( $fields ) {
	    $billing_address_fields = array(
		    'country' => $fields['country'],
		    'postcode' => $fields['postcode'],
		    'city' => $fields['city'],
		    'state' => $fields['state'],
		    'address_1' => $fields['address_1'],
		    'address_2' => $fields['address_2'],
		    'company' => $fields['company'],
		    'last_name' => $fields['last_name'],
		    'first_name' => $fields['first_name'],
		    'yomigana_last_name' => array(
				'label' => __( 'Last Name Yomigana', 'woocommerce-for-japan' ),
				'show'	=> false
			),
			'yomigana_first_name' => array(
				'label' => __( 'First Name Yomigana', 'woocommerce-for-japan' ),
				'show'	=> false
			),
		    'email' => $fields['email'],
			'phone' => array(
				'label' => __( 'Phone', 'woocommerce-for-japan' ),
				'show'	=> false
			),
	    );

		if(!get_option( 'wc4jp-company-name'))unset($billing_address_fields['company']);
		if(!get_option( 'wc4jp-yomigana'))unset($billing_address_fields['yomigana_last_name'],$billing_address_fields['yomigana_first_name']);

		return $billing_address_fields;
	}
    public function admin_shipping_address_fields( $fields ) {
	    $shipping_address_fields = array(
		    'country' => $fields['country'],
		    'postcode' => $fields['postcode'],
		    'city' => $fields['city'],
		    'state' => $fields['state'],
		    'address_1' => $fields['address_1'],
		    'address_2' => $fields['address_2'],
		    'company' => $fields['company'],
		    'last_name' => $fields['last_name'],
		    'first_name' => $fields['first_name'],
		    'yomigana_last_name' => array(
				'label' => __( 'Last Name Yomigana', 'woocommerce-for-japan' ),
				'show'	=> false
			),
			'yomigana_first_name' => array(
				'label' => __( 'First Name Yomigana', 'woocommerce-for-japan' ),
				'show'	=> false
			),
			'phone' => array(
				'label' => __( 'Phone', 'woocommerce-for-japan' ),
				'show'	=> false
			),
	    );

		if(!get_option( 'wc4jp-company-name'))unset($shipping_address_fields['company']);
		if(!get_option( 'wc4jp-yomigana'))unset($shipping_address_fields['yomigana_last_name'],$shipping_address_fields['yomigana_first_name']);

		return $shipping_address_fields;
	}
	public function admin_customer_meta_fields( $fields ){
		$customer_meta_fields = $fields;
		//Billing fields
		$billing_fields = $fields['billing']['fields'];
		$customer_meta_fields['billing']['fields'] = array(
			'billing_last_name' => $billing_fields['billing_last_name'],
			'billing_first_name' => $billing_fields['billing_first_name'],
			'billing_yomigana_last_name' => array(
				'label' => __( 'Last Name Yomigana', 'woocommerce-for-japan' ),
				'description' => '',
			),
			'billing_yomigana_first_name' => array(
				'label' => __( 'First Name Yomigana', 'woocommerce-for-japan' ),
				'description' => '',
			),
			'billing_company'  => $billing_fields['billing_company'],
			'billing_country'  => $billing_fields['billing_country'],
			'billing_postcode' => $billing_fields['billing_postcode'],
			'billing_state'  => $billing_fields['billing_state'],
			'billing_city'  => $billing_fields['billing_city'],
			'billing_address_1'  => $billing_fields['billing_address_1'],
			'billing_address_2'  => $billing_fields['billing_address_2'],
			'billing_phone'  => $billing_fields['billing_phone'],
			'billing_email'  => $billing_fields['billing_email'],
		);
		//Shipping fields
		$shipping_fields = $fields['shipping']['fields'];
		$customer_meta_fields['shipping']['fields'] = array(
			'shipping_last_name' => $shipping_fields['shipping_last_name'],
			'shipping_first_name' => $shipping_fields['shipping_first_name'],
			'shipping_yomigana_last_name' => array(
				'label' => __( 'Last Name Yomigana', 'woocommerce-for-japan' ),
				'description' => '',
			),
			'shipping_yomigana_first_name' => array(
				'label' => __( 'First Name Yomigana', 'woocommerce-for-japan' ),
				'description' => '',
			),
			'shipping_company'  => $shipping_fields['shipping_company'],
			'shipping_country'  => $shipping_fields['shipping_country'],
			'shipping_postcode' => $shipping_fields['shipping_postcode'],
			'shipping_state'  => $shipping_fields['shipping_state'],
			'shipping_city'  => $shipping_fields['shipping_city'],
			'shipping_address_1'  => $shipping_fields['shipping_address_1'],
			'shipping_address_2'  => $shipping_fields['shipping_address_2'],
			'shipping_phone'  => array(
				'label' => __( 'Phone', 'woocommerce-for-japan' ),
				'description' => '',
			),
		);
		if(!get_option( 'wc4jp-company-name'))unset($customer_meta_fields['billing']['fields']['billing_company'], $customer_meta_fields['shipping']['fields']['shipping_company']);
		if(!get_option( 'wc4jp-yomigana'))unset($customer_meta_fields['billing']['fields']['billing_yomigana_last_name'], $customer_meta_fields['billing']['fields']['billing_yomigana_first_name'], $customer_meta_fields['shipping']['fields']['shipping_yomigana_last_name'], $customer_meta_fields['shipping']['fields']['shipping_yomigana_first_name']);
		return $customer_meta_fields;
	}
}
