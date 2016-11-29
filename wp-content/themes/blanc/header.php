<?php
/**
* The template for displaying Header.
* @link			http://welcustom.net/
* @author		Mamekko
* @copyright	Copyright (c) 2015 welcustom.net
*/
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>

<head>
	<meta charset="<?php bloginfo('charset'); ?>">
	<meta name="viewport" content="width=device-width,user-scalable=no">
	<!--[if lt IE9]>
	<![endif]-->
	<!--[if lt IE8]>
	<![endif]-->
	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>

	<header id="header" class="font-quicksand"<?php if( is_front_page() ){ if( get_header_image() ) { echo ' style="border-bottom: none;"'; } else { echo 'style="margin-bottom: 2rem;"'; } } ?>>
		<div class="row">
			<div class="columns large-5 medium-5<?php if( function_exists('usces_the_item') ){ echo ' small-10'; } ?>">
				<h1><a href="<?php echo esc_url( home_url('/') ); ?>"><?php bloginfo('name'); ?></a></h1>
				<p><?php bloginfo('description'); ?></p>
			</div><!-- columns -->
			
			<?php if( function_exists('usces_the_item') ): ?><!-- menu for Welcart plugin -->
			<div class="columns large-1 large-push-6 medium-2 medium-push-5 small-2 medium-text-center header-cartbutton">
				<a href="<?php echo USCES_CART_URL; ?>" title="<?php _e('Shopping Cart','blanc'); ?>">
					<i class="fa fa-shopping-cart"></i>
					<span class="text-red text-center"><?php usces_totalquantity_in_cart(); ?></span>
				</a>
			</div>

			<dl class="nav columns large-6 medium-5 large-pull-1 medium-pull-2">
				
			<?php else: ?>

			<dl class="nav columns large-7 medium-7">

			<?php endif; ?>

				<dt class="nav-button show-for-small-only"><i class="fa fa-bars fa-fw"></i><?php _e('MENU','blanc'); ?></dt>
				<dd class="menu-wrap">
					<nav class="clearfix">
					<?php wp_nav_menu( array(
						'theme_location'=> 'navigation',
						'menu_class' => 'no-bullet',
					)); ?>
					</nav>
				</dd>
			</dl><!-- columns -->

		</div><!-- row -->
	</header>