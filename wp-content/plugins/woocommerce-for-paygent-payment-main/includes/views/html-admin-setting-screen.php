<?php global $woocommerce; ?>
<form id="check-test-sha2-form" method="post" action="" enctype="multipart/form-data">
<br />
<?php wp_nonce_field( 'my-nonce-key','check-test-sha2');?>
	<input type="hidden" name="check-test" value="sha2" />
	<button type="submit"><big><?php echo __( ' Check SHA-2 TEST ', 'woocommerce-for-paygent-payment-main' );?></big></button>
</form>
<form id="wc-paygent-setting-form" method="post" action=""  enctype="multipart/form-data">
<?php wp_nonce_field( 'my-nonce-key','wc-paygent-setting');?>
<h3><?php echo __( 'Paygent Initial Setting', 'woocommerce-for-paygent-payment-main' );?></h3>
<p style="border:1px solid #666; width:50%; padding:10px;"><b><?php echo __( 'IP Address : ', 'woocommerce-for-paygent-payment-main' );?></b><?php echo $_SERVER['SERVER_ADDR'];?><br />
<b><?php echo __( 'libcurl Version : ', 'woocommerce-for-paygent-payment-main' );?></b><?php $version = curl_version(); echo $version["version"];?><br />
<?php echo __( '※In the case of PHP 5.0.0 or later, you need libcurl 7.10.5 or later.', 'woocommerce-for-paygent-payment-main' );?>
</p>
<table class="form-table">
<tr><th colspan="2"><?php echo __( 'Production environment', 'woocommerce-for-paygent-payment-main' );?></th></tr>
<tr valign="top">
    <th scope="row" class="titledesc">
        <label for="woocommerce_paygent_mid"><?php echo __( 'Merchant ID', 'woocommerce-for-paygent-payment-main' );?></label>
    </th>
    <td class="forminp"><input type="text" name="paygent_mid" value="<?php echo get_option('wc-paygent-mid');?>" >
    <p class="description"><?php echo __( 'Please input Merchant ID from Paygent documents', 'woocommerce-for-paygent-payment-main' );?></p></td>
</tr>
<tr valign="top">
    <th scope="row" class="titledesc">
        <label for="woocommerce_input_company"><?php echo __( 'Connect ID', 'woocommerce-for-paygent-payment-main' );?></label>
    </th>
    <td class="forminp"><input type="text" name="paygent_cid" value="<?php echo get_option('wc-paygent-cid');?>" >
    <p class="description"><?php echo __( 'Please input Connect ID from Paygent documents', 'woocommerce-for-paygent-payment-main' );?></p></td>
</tr>
<tr valign="top">
    <th scope="row" class="titledesc">
        <label for="woocommerce_input_company"><?php echo __( 'Connect Password', 'woocommerce-for-paygent-payment-main' );?></label>
    </th>
    <td class="forminp"><input type="text" name="paygent_cpass" value="<?php echo get_option('wc-paygent-cpass');?>" >
    <p class="description"><?php echo __( 'Please input Connect Password from Paygent documents', 'woocommerce-for-paygent-payment-main' );?></p></td>
</tr>
<tr><th colspan="2"><?php echo __( 'Test environment', 'woocommerce-for-paygent-payment-main' );?></th></tr>
<tr valign="top">
    <th scope="row" class="titledesc">
        <label for="woocommerce_paygent_test_mid"><?php echo __( 'Merchant ID', 'woocommerce-for-paygent-payment-main' );?></label>
    </th>
    <td class="forminp"><input type="text" name="paygent_test_mid" value="<?php echo get_option('wc-paygent-test-mid');?>" >
    <p class="description"><?php echo __( 'Please input Merchant ID from Paygent documents', 'woocommerce-for-paygent-payment-main' );?></p></td>
</tr>
<tr valign="top">
    <th scope="row" class="titledesc">
        <label for="woocommerce_input_test_cid"><?php echo __( 'Connect ID', 'woocommerce-for-paygent-payment-main' );?></label>
    </th>
    <td class="forminp"><input type="text" name="paygent_test_cid" value="<?php echo get_option('wc-paygent-test-cid');?>" >
    <p class="description"><?php echo __( 'Please input Connect ID from Paygent documents', 'woocommerce-for-paygent-payment-main' );?></p></td>
</tr>
<tr valign="top">
    <th scope="row" class="titledesc">
        <label for="woocommerce_input_test_cpass"><?php echo __( 'Connect Password', 'woocommerce-for-paygent-payment-main' );?></label>
    </th>
    <td class="forminp"><input type="text" name="paygent_test_cpass" value="<?php echo get_option('wc-paygent-test-cpass');?>" >
    <p class="description"><?php echo __( 'Please input Connect Password from Paygent documents', 'woocommerce-for-paygent-payment-main' );?></p></td>
</tr>
<tr valign="top">
    <th scope="row" class="titledesc">
        <label for="woocommerce_input_company"><?php echo __( 'Site ID', 'woocommerce-for-paygent-payment-main' );?></label>
    </th>
    <td class="forminp"><input type="text" name="paygent_sid" value="<?php echo get_option('wc-paygent-sid');?>" >
    <p class="description"><?php echo __( 'Please input Site ID from Paygent documents', 'woocommerce-for-paygent-payment-main' );?></p></td>
</tr>
<tr valign="top">
    <th scope="row" class="titledesc">
        <label for="woocommerce_paygent_pcf"><?php echo __( 'Client Cert File', 'woocommerce-for-paygent-payment-main' );?></label>
    </th>
    <td class="forminp"><input type="file" name="clientc_file" size="30" >
    <p class="description"><?php if(!file_exists(CLIENT_FILE_PATH)){ echo __( 'Please select Client Cert File (pem) from local.', 'woocommerce-for-paygent-payment-main' );}else{echo __( 'If you want to change Client Cert File, please select New Client Cert File (pem) from local.', 'woocommerce-for-paygent-payment-main' );}?></p></td>
</tr>
<tr valign="top">
    <th scope="row" class="titledesc">
        <label for="woocommerce_paygent_pcf"><?php echo __( 'Client Test Cert File', 'woocommerce-for-paygent-payment-main' );?></label>
    </th>
    <td class="forminp"><input type="file" name="clientc_test_file" size="30" >
    <p class="description"><?php if(!file_exists(CLIENT_TEST_FILE_PATH)){ echo __( 'Please select Client Cert Test File (pem) from local.', 'woocommerce-for-paygent-payment-main' );}else{echo __( 'If you want to change Client Cert Test File, please select New Client Test Cert File (pem) from local.', 'woocommerce-for-paygent-payment-main' );}?></p></td>
</tr>
<tr valign="top">
    <th scope="row" class="titledesc">
        <label for="woocommerce_paygent_ccf"><?php echo __( 'CA Cert File', 'woocommerce-for-paygent-payment-main' );?></label>
    </th>
    <td class="forminp"><input type="file" name="cac_file" size="30" >
    <p class="description"><?php if(!file_exists(CA_FILE_PATH)){echo __( 'Please select CA Cert(crt) File from local.', 'woocommerce-for-paygent-payment-main' );}else{echo __( 'If you want to change CA Cert File, please select New CA Cert(crt) File from local.', 'woocommerce-for-paygent-payment-main' );}?></p></td>
</tr>
</table>
<h3><?php echo __( 'Paygent Payment Method', 'woocommerce-for-paygent-payment-main' );?></h3>
<table class="form-table">
<?php
	$paygent_paymethod = array(
		'cc' => __( 'Credit Card', 'woocommerce-for-paygent-payment-main' ),
		'cs' => __( 'Convenience store', 'woocommerce-for-paygent-payment-main' ),
		'mccc' => __( 'Multi-currency Credit Card', 'woocommerce-for-paygent-payment-main' ),
		'bn' => __( 'Bank Net', 'woocommerce-for-paygent-payment-main' ),
		'atm' => __( 'ATM Payment', 'woocommerce-for-paygent-payment-main' ),
		'mb' => __( 'Carrier Payment', 'woocommerce-for-paygent-payment-main' ),
	);
	foreach($paygent_paymethod as $key => $value){
		$payment_detail = sprintf( __( 'Please check it if you want to use the payment method of %s', 'woocommerce-for-paygent-payment-main' ),$value);
		$payment_str = 'paygent_'.$key;
		$payment_option = 'wc-paygent-'.$key;
		$options = get_option($payment_option);
		echo '
<tr valign="top">
    <th scope="row" class="titledesc">
        <label for="woocommerce_input_'.$key.'">'.$value.'</label>
    </th>
    <td class="forminp"><input type="checkbox" name="'.$payment_str.'" value="1" ';
    checked( $options, 1 );
    echo '>'.$value.'
    <p class="description">'.$payment_detail.'</p></td>
</tr>';
	}
?>
</table>

<h3><?php echo __( 'Test Mode', 'woocommerce-for-paygent-payment-main' );?></h3>
<table class="form-table">
<tr valign="top">
    <th scope="row" class="titledesc">
        <label for="woocommerce_input_testmode"><?php echo __( 'Test Mode', 'woocommerce-for-paygent-payment-main' );?></label>
    </th>
    <td class="forminp"><input type="checkbox" name="paygent_testmode" value="1" <?php $test_mode = get_option('wc-paygent-testmode') ;checked( $test_mode, 1 ); ?>><?php echo __( 'Test Mode', 'woocommerce-for-paygent-payment-main' );?>
    <p class="description"><?php echo __( 'Please check it if you want to use Testmode', 'woocommerce-for-paygent-payment-main' );?></p></td>
</tr>
</table>

<p class="submit">
   <input name="save" class="button-primary" type="submit" value="<?php echo __( 'Save changes', 'woocommerce-for-paygent-payment-main' );?>">
</p>
</form>
