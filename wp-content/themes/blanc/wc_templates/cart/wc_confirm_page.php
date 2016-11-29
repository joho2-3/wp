<?php
/**
 * <meta content="charset=UTF-8">
 * @package Welcart
 * @subpackage Welcart Default Theme
 *
 * @link		http://welcustom.net/
 * @arranged by	Mamekko
 */
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>

<head>
	<meta charset="<?php bloginfo('charset'); ?>">
	<meta name="viewport" content="width=device-width,user-scalable=no">
	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>

	<header id="header" class="font-quicksand">
		<div class="row">
			<div class="columns small-12">
				<h1><a href="<?php echo esc_url( home_url('/') ); ?>"><?php bloginfo('name'); ?></a></h1>
				<p><?php bloginfo('description'); ?></p>
			</div><!-- columns -->
		</div><!-- row -->
	</header>

<div class="row">
	<div id="main" class="columns large-12">
		
		<div class="usccart_navi">
			<ol class="ucart">
			<li class="ucart usccart"><?php _e('Cart','blanc'); ?></li>
			<li class="ucart usccustomer"><?php _e('Customer info','blanc'); ?></li>
			<li class="ucart uscdelivery"><?php _e('Payment','blanc'); ?></li>
			<li class="ucart uscconfirm usccart_confirm"><?php _e('Confirmation','blanc'); ?></li>
			</ol>
		</div>

		<?php if( have_posts() ): usces_remove_filter(); while( have_posts() ): the_post(); ?>
		<article <?php post_class(); ?> itemscope itemtype="http://schema.org/Article">
			
			<p class="header_explanation">
			<?php do_action('usces_action_confirm_page_header'); ?>
			</p>

			<p class="error_message"><?php usces_error_message(); ?></p>
			
			<table id="cart_table">
				<thead>
					<tr>
						<th></th>
						<th><?php _e('Product','blanc'); ?></th>
						<th><?php _e('Price','blanc'); ?></th>
						<th><?php _e('Qty','blanc'); ?></th>
						<th><?php _e('Total','blanc'); ?></th>
					</tr>
				</thead>
				<tbody>
					<?php usces_get_confirm_rows(); ?>
				</tbody>
				<tfoot class="text-right">
					<tr>
						<th colspan="4"><?php _e('Subtotal','blanc'); ?></th>
						<th><?php usces_crform($usces_entries['order']['total_items_price'], true, false); ?></th>
					</tr>
					<?php if( !empty($usces_entries['order']['discount']) ) : ?>
					<tr>
						<td colspan="4"><?php echo apply_filters('usces_confirm_discount_label', __('Campaign disnount', 'usces')); ?></td>
						<td class="text-red"><?php usces_crform($usces_entries['order']['discount'], true, false); ?></td>
					</tr>
					<?php endif; ?>
					<?php if( 0.00 < (float)$usces_entries['order']['tax'] && 'products' == usces_get_tax_target() ) : ?>
					<tr>
						<td colspan="4"><?php usces_tax_label(); ?></td>
						<td><?php usces_tax($usces_entries) ?></td>
					</tr>
					<?php endif; ?>
					<tr>
						<td colspan="4"><?php _e('Shipping', 'usces'); ?></td>
						<td><?php usces_crform($usces_entries['order']['shipping_charge'], true, false); ?></td>
					</tr>
					<?php if( !empty($usces_entries['order']['cod_fee']) ) : ?>
					<tr>
						<td colspan="4"><?php echo apply_filters('usces_filter_cod_label', __('COD fee', 'usces')); ?></td>
						<td><?php usces_crform($usces_entries['order']['cod_fee'], true, false); ?></td>
					</tr>
					<?php endif; ?>
					<?php if( 0.00 < (float)$usces_entries['order']['tax'] && 'all' == usces_get_tax_target() ) : ?>
					<tr>
						<td colspan="4"><?php usces_tax_label(); ?></td>
						<td><?php usces_tax($usces_entries) ?></td>
					</tr>
					<?php endif; ?>
					<?php if( usces_is_member_system() && usces_is_member_system_point() && !empty($usces_entries['order']['usedpoint']) ) : ?>
					<tr>
						<td colspan="4"><?php _e('Used points', 'usces'); ?></td>
						<td class="text-red"><?php echo number_format($usces_entries['order']['usedpoint']); ?></td>
					</tr>
					<?php endif; ?>
					<tr>
						<th colspan="4"><?php _e('Total Amount', 'usces'); ?></th>
						<th><?php usces_crform($usces_entries['order']['total_full_price'], true, false); ?></th>
					</tr>
				</tfoot>
			</table>
			
			<?php if( usces_is_member_system() && usces_is_member_system_point() &&  usces_is_login() ) : ?>
			<form action="<?php usces_url('cart'); ?>" method="post" onKeyDown="if (event.keyCode == 13) {return false;}">
				<p class="error_message text-center"><?php usces_error_message(); ?></p>
				<table id="point_table" class="text-center">
					<tr>
						<th><?php _e('The current point', 'usces'); ?></th>
						<td><b class="font-bigger"><?php echo $usces_members['point']; ?></b>pt</td>
					</tr>
					<tr>
						<th><?php _e('Points you are using here', 'usces'); ?></th>
						<td><input name="offer[usedpoint]" class="used_point text-right" type="text" value="<?php echo esc_attr($usces_entries['order']['usedpoint']); ?>" />pt</td>
					</tr>
					<tr>
						<td colspan="2"><input name="use_point" type="submit" class="use_point_button button black small" value="<?php _e('Use the points', 'usces'); ?>" /></td>
					</tr>
				</table>
				<?php do_action('usces_action_confirm_page_point_inform'); ?>
			</form>
			<?php endif; ?>
			
			<table id="confirm_table">
				<tr class="ttl">
					<td colspan="2"><h3><?php _e('Customer Information', 'usces'); ?></h3></td>
				</tr>
			<tr>
				<th><?php _e('e-mail adress', 'usces'); ?></th>
				<td><?php echo esc_html($usces_entries['customer']['mailaddress1']); ?></td>
			</tr>
			<?php uesces_addressform( 'confirm', $usces_entries, 'echo' ); ?>
			<tr class="ttl">
				<td colspan="2"><h3><?php _e('Others', 'usces'); ?></h3></td>
			</tr>
			<tr>
				<th><?php _e('shipping option', 'usces'); ?></th><td><?php echo esc_html(usces_delivery_method_name( $usces_entries['order']['delivery_method'], 'return' )); ?></td>
			</tr>
			<tr>
				<th><?php _e('Delivery date', 'usces'); ?></th><td><?php echo esc_html($usces_entries['order']['delivery_date']); ?></td>
			</tr>
			<tr class="bdc">
				<th><?php _e('Delivery Time', 'usces'); ?></th><td><?php echo esc_html($usces_entries['order']['delivery_time']); ?></td>
			</tr>
			<tr>
				<th><?php _e('payment method', 'usces'); ?></th><td><?php echo esc_html($usces_entries['order']['payment_name'] . usces_payment_detail($usces_entries)); ?></td>
			</tr>
			<?php usces_custom_field_info($usces_entries, 'order', ''); ?>
			<tr>
				<th><?php _e('Notes', 'usces'); ?></th><td><?php echo nl2br(esc_html($usces_entries['order']['note'])); ?></td>
			</tr>
		</table>

			<?php usces_purchase_button(); ?>
			
			<p class="footer_explanation">
			<?php do_action('usces_action_confirm_page_footer'); ?>
			</p>

		</article>
		<?php endwhile; endif; ?>
		
	</div><!-- columns -->
	
</div><!-- row -->

<footer id="footer">
	<div class="row">
		<div class="columns">
			<p class="font-quicksand text-gray">Copyright&nbsp;&copy;&nbsp;<?php echo date('Y'); ?>&nbsp;<a href="<?php echo home_url('/'); ?>"><?php bloginfo('name'); ?></a>, All rights reserved. Theme by <a href="<?php echo esc_url( 'http://welcustom.net/'); ?>"><?php printf('Mamekko Themes'); ?></a></p>
			<div class="page-top"><a href="#header"><i class="fa fa-arrow-up fa-lg"></i></a></div>
		</div>
	</div><!-- row -->
</footer>

<?php wp_footer(); ?>

</body>
</html>