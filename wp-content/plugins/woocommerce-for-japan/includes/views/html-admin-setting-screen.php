<?php global $woocommerce; ?>
<form id="wc4jp-setting-form" method="post" action="">
<?php wp_nonce_field( 'my-nonce-key','wc4jp-setting');?>
<h3><?php echo __( 'Address Display Setting', 'woocommerce-for-japan' );?></h3>
<table class="form-table">
<?php
	$options = array();

	$add_methods = array(
	'yomigana' => __( 'Name Yomigana', 'woocommerce-for-japan' ),
	'company-name' => __( 'Company Name', 'woocommerce-for-japan' ),
	'free-shipping' => __( 'Free Shipping Display', 'woocommerce-for-japan' )
	);
	foreach($add_methods as $add_method => $value ){?>
<tr valign="top">
    <th scope="row" class="titledesc">
        <label for="woocommerce_input_<?php echo $add_method;?>"><?php echo $value?></label>
    </th>
    <td class="forminp"><input type="checkbox" name="<?php echo $add_method;?>" value="1" <?php $options[$add_method] =get_option('wc4jp-'.$add_method) ;checked( $options[$add_method], 1 ); ?>><?php echo $value;?>
    <?php if( $add_method == 'free-shipping'){?>
    <p class="description"><?php echo sprintf(__( 'Please check it if you want to show %s only, when free shipping exist.', 'woocommerce-for-japan' ), $value);?></p></td>
    <?php }else{?>
    <p class="description"><?php echo sprintf(__( 'Please check it if you want to use input field for %s', 'woocommerce-for-japan' ), $value);?></p></td>
    <?php }?>
</tr>
<?php	}?>
</table>
<h3><?php echo __( 'Payment Method', 'woocommerce-for-japan' );?></h3>
<table class="form-table">
<?php
	$payment_methods = array(
	'bankjp' => __( 'BANK PAYMENT IN JAPAN', 'woocommerce-for-japan' ),
	'postofficebank' => __( 'Postal transfer', 'woocommerce-for-japan' ),
	'atstore' => __( 'Pay at store', 'woocommerce-for-japan' )
	);
	foreach($payment_methods as $payment_method => $value ){?>
<tr valign="top">
    <th scope="row" class="titledesc">
        <label for="woocommerce_input_<?php echo $payment_method;?>"><?php echo $value;?></label>
    </th>
    <td class="forminp"><input type="checkbox" name="<?php echo $payment_method;?>" value="1" <?php $options[$payment_method] = get_option('wc4jp-'.$payment_method) ;checked( $options[$payment_method], 1 ); ?>><?php echo $value;?>
    <p class="description"><?php echo sprintf( __( 'Please check it if you want to use the payment method of %s', 'woocommerce-for-japan' ), $value );?></p></td>
</tr>
<?php	}?>
</table>
<p class="submit">
   <input name="save" class="button-primary" type="submit" value="<?php echo __( 'Save changes', 'woocommerce' );?>">
</p>
</form>
