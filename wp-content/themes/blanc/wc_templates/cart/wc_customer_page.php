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
			<li class="ucart usccustomer usccart_customer"><?php _e('Customer info','blanc'); ?></li>
			<li class="ucart uscdelivery"><?php _e('Payment','blanc'); ?></li>
			<li class="ucart uscconfirm"><?php _e('Confirmation','blanc'); ?></li>
			</ol>
		</div>

		<?php if( have_posts() ): usces_remove_filter(); while( have_posts() ): the_post(); ?>
		<article id="customer-info" <?php post_class(); ?> itemscope itemtype="http://schema.org/Article">
			
			<p class="header_explanation">
			<?php do_action('usces_action_customer_page_header'); ?>
			</p>

			<p class="error_message"><?php usces_error_message(); ?></p>

			<?php if( usces_is_membersystem_state() ) : ?>
			<h5><?php _e('The member please enter at here.','usces'); ?></h5>
			<form action="<?php usces_url('cart'); ?>" method="post" name="customer_loginform" onKeyDown="if (event.keyCode == 13) {return false;}">
				<table width="100%" border="0" cellpadding="0" cellspacing="0" class="customer_form">
					<tr>
						<th scope="row"><?php _e('e-mail adress', 'usces'); ?></th>
						<td><input name="loginmail" id="mailaddress" type="text" value="<?php echo esc_attr($usces_entries['customer']['mailaddress1']); ?>" style="ime-mode: inactive" /></td>
					</tr>
					<tr>
						<th scope="row"><?php _e('password', 'usces'); ?></th>
						<td><input class="hide" value=" " /><input name="loginpass" id="loginpass" type="password" value="" autocomplete="off" /><a href="<?php usces_url('lostmemberpassword'); ?>" title="<?php _e('Did you forget your password?', 'usces'); ?>"><?php _e('Did you forget your password?', 'usces'); ?></a></td>
					</tr>
				</table>
			<div class="send"><input name="customerlogin" type="submit" value="<?php _e(' Next ', 'usces'); ?>" /></div>
			<?php do_action('usces_action_customer_page_member_inform'); ?>
			</form>
			<h5><?php _e('The nonmember please enter at here.','usces'); ?></h5>
			<?php endif; ?>
			
			<form action="<?php echo USCES_CART_URL; ?>" method="post" name="customer_form" onKeyDown="if (event.keyCode == 13) {return false;}">
			<table border="0" cellpadding="0" cellspacing="0" class="customer_form">
				<tr>
					<th scope="row"><em><?php _e('*', 'usces'); ?></em><?php _e('e-mail adress', 'usces'); ?></th>
					<td colspan="2"><input name="customer[mailaddress1]" id="mailaddress1" type="text" value="<?php echo esc_attr($usces_entries['customer']['mailaddress1']); ?>" style="ime-mode: inactive" /></td>
				</tr>
				<tr>
					<th scope="row"><em><?php _e('*', 'usces'); ?></em><?php _e('e-mail adress', 'usces'); ?>(<?php _e('Re-input', 'usces'); ?>)</th>
					<td colspan="2"><input name="customer[mailaddress2]" id="mailaddress2" type="text" value="<?php echo esc_attr($usces_entries['customer']['mailaddress2']); ?>" style="ime-mode: inactive" /></td>
				</tr>
				<?php if( usces_is_membersystem_state() ) : ?>
				<tr>
					<th scope="row"><?php if( $member_regmode == 'editmemberfromcart' ) : ?><em><?php _e('*', 'usces'); ?></em><?php endif; ?><?php _e('password', 'usces'); ?></th>
					<td colspan="2"><input class="hide" value=" " /><input name="customer[password1]" style="width:100px" type="password" value="<?php echo esc_attr($usces_entries['customer']['password1']); ?>" autocomplete="off" /><?php if( $member_regmode != 'editmemberfromcart' ) _e('When you enroll newly, please fill it out.', 'usces'); ?>	</td>
				</tr>
				<tr>
					<th scope="row"><?php if( $member_regmode == 'editmemberfromcart' ) : ?><em><?php _e('*', 'usces'); ?></em><?php endif; ?><?php _e('Password (confirm)', 'usces'); ?></th>
					<td colspan="2"><input name="customer[password2]" style="width:100px" type="password" value="<?php echo esc_attr($usces_entries['customer']['password2']); ?>" /><?php if( $member_regmode != 'editmemberfromcart' ) _e('When you enroll newly, please fill it out.', 'usces'); ?></td>
				</tr>
				<?php endif; ?>

				<?php uesces_addressform( 'customer', $usces_entries, 'echo' ); ?>
			</table>
			<input name="member_regmode" type="hidden" value="<?php echo $member_regmode; ?>" />
			<div class="send">
			<?php usces_get_customer_button(); ?>
			</div>
			<?php do_action('usces_action_customer_page_inform'); ?>
			</form>
			
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