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
			<li class="ucart usccart usccart_cart"><?php _e('Cart','blanc'); ?></li>
			<li class="ucart usccustomer"><?php _e('Customer info','blanc'); ?></li>
			<li class="ucart uscdelivery"><?php _e('Payment','blanc'); ?></li>
			<li class="ucart uscconfirm"><?php _e('Confirmation','blanc'); ?></li>
			</ol>
		</div>

		<?php if( have_posts() ): usces_remove_filter(); while( have_posts() ): the_post(); ?>
		<article <?php post_class(); ?> itemscope itemtype="http://schema.org/Article">
			
			<p class="header_explanation">
			<?php do_action('usces_action_cart_page_header'); ?>
			</p>

			<p class="error_message"><?php usces_error_message(); ?></p>
			<form action="<?php usces_url('cart'); ?>" method="post" onKeyDown="if (event.keyCode == 13) {return false;}">
			<?php if( usces_is_cart() ) : ?>

			<div id="cart">
				
				<div class="upbutton medium-text-right"><?php _e('Press the `update` button when you change the amount of items.','usces'); ?><input name="upButton" type="submit" value="<?php _e('Quantity renewal','usces'); ?>" onclick="return uscesCart.upCart()" class="button tiny black"></div>
				
				<table cellspacing="0" id="cart_table">
					<thead>
					<tr>
						<th class="thumbnail"> </th>
						<th><?php _e('item name','usces'); ?></th>
						<th><?php _e('Unit price','usces'); ?></th>
						<th><?php _e('Quantity','usces'); ?></th>
						<th><?php _e('Amount','usces'); ?><?php usces_guid_tax(); ?></th>
						<th></th>
					</tr>
					</thead>
					<tbody>
					<?php usces_get_cart_rows(); ?>
					</tbody>
					<tfoot>
					<tr>
						<th colspan="4" scope="row" class="text-right"><?php _e('total items','usces'); ?><?php usces_guid_tax(); ?></th>
						<th class="text-right"><?php usces_crform(usces_total_price('return'), true, false); ?></th>
						<th></th>
					</tr>
					</tfoot>
				</table>
				
				<p class="currency_code"><?php _e('Currency','usces'); ?> : <?php usces_crcode(); ?></p>
				<?php if( $usces_gp ) : ?>
				<i class="fa fa-tag fa-fw"></i><?php _e('The price with this mark applys to Business pack discount.','usces'); ?>
				<?php endif; ?>
				<?php $num = ( $this->options['postage_privilege'] )- ( $this->get_total_price() );
				if( 0 < $num ): ?>
				<div class="panel panel-postage medium-text-center">
					<?php echo sprintf(__('Get Free Shipping with <b>%s</b> more parchase.','blanc'), usces_crform($num, true, false, 'return')); ?>
				</div>
				<?php endif; ?>
			</div><!-- end of cart -->

			<?php else : ?>
			<div class="panel panel-default">
				<p class="medium-text-center"><?php _e('There are no items in your cart.','usces'); ?></p>
			</div>
			<?php endif; ?>

			<div class="send"><?php usces_get_cart_button(); ?></div>
			<?php do_action('usces_action_cart_page_inform'); ?>
			</form>

			<p class="footer_explanation">
			<?php do_action('usces_action_cart_page_footer'); ?>
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