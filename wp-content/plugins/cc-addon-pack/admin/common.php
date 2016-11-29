<?php
function ccAddonPack_options_register() {
	register_setting( 'ccAddonPack_options_fields', 'ccAddonPack_options', 'ccAddonPack_options_sanitize' );
}
add_action( 'admin_init', 'ccAddonPack_options_register' );


function ccAddonPack_get_options_default(){
	$default_options = array(
		'disabled_emoji' => false,
		'active_bootstrap' => false,
		'active_fontawesome' => false,
		'favicon' => '',
		'default_thumbnail' => '',
		'topic1_title' => '',
		'topic1_subtitle' => '',
		'topic1_desc' => '',
		'topic1_link' => '',
		'topic1_img' => '',
		'topic2_title' => '',
		'topic2_subtitle' => '',
		'topic2_desc' => '',
		'topic2_link' => '',
		'topic2_img' => '',
		'contact_address' => '',
		'contact_tel' => '',
		'contact_fax' => '',
		'contact_email' => '',
		'active_meta_keyword' => false,
		'common_keywords' => '',
		'active_meta_description' => false,
		'common_description' => '',
		'gaId' => '',
		'fburl' => '',
		'twId' => '',
		'active_og' => false,
		'ogimg' => '',
	);

	return $default_options;
}


function ccAddonPack_get_option() {
	return get_option( 'ccAddonPack_options', ccAddonPack_get_options_default() );
}


function ccAddonPack_options_sanitize( $input ) {
	$input['disabled_emoji'] = (isset( $input['disabled_emoji'] )) ? true : false;
	$input['active_bootstrap'] = (isset( $input['active_bootstrap'] )) ? true : false;
	$input['active_fontawesome'] = (isset( $input['active_fontawesome'] )) ? true : false;
	$input['favicon'] = esc_url_raw( $input['favicon'] );
	$input['default_thumbnail'] = esc_html( $input['default_thumbnail'] );	
	$input['topic1_title'] = esc_html( $input['topic1_title'] );
	$input['topic1_subtitle'] = esc_html( $input['topic1_subtitle'] );
	$input['topic1_desc'] = esc_textarea( $input['topic1_desc'] );
	$input['topic1_link'] = esc_url_raw( $input['topic1_link'] );
	$input['topic1_img'] = esc_url_raw( $input['topic1_img'] );
	$input['topic2_title'] = esc_html( $input['topic2_title'] );
	$input['topic2_subtitle'] = esc_html( $input['topic2_subtitle'] );
	$input['topic2_desc'] = esc_textarea( $input['topic2_desc'] );
	$input['topic2_link'] = esc_url_raw( $input['topic2_link'] );
	$input['topic2_img'] = esc_url_raw( $input['topic2_img'] );
	$input['contact_address'] = esc_html( $input['contact_address'] );
	$input['contact_tel'] = esc_html( $input['contact_tel'] );
	$input['contact_fax'] = esc_html( $input['contact_fax'] );
	$input['contact_email'] = sanitize_email( $input['contact_email'] );
	$input['active_meta_keyword'] = (isset( $input['active_meta_keyword'] )) ? true : false;
	$input['common_keywords'] = esc_html( $input['common_keywords'] );
	$input['active_meta_description'] = (isset( $input['active_meta_description'] )) ? true : false;
	$input['common_description'] = esc_html( $input['common_description'] );
	$input['gaId'] = esc_html( $input['gaId'] );
	$input['fburl'] = esc_url_raw( $input['fburl'] );
	$input['twId'] = esc_html( $input['twId'] );
	$input['active_og'] = (isset( $input['active_og'] )) ? true : false;
	$input['ogimg'] = esc_url_raw( $input['ogimg'] );

	return $input;
}