<?php
/**
 * The template for displaying e-commerce cart pages.
 * For Welcart e-commcer plugin only.
 * @link		http://welcustom.net/
 * @author		Mamekko
 * @copyright	Copyright (c) 2015 welcustom.net
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

	<header id="header" class="font-quicksand" <?php if( is_front_page() ){ echo 'style="border-bottom: none;"'; } ?>>
		<div class="row">
			<div class="columns small-12">
				<h1><a href="<?php echo esc_url( home_url('/') ); ?>"><?php bloginfo('name'); ?></a></h1>
				<p><?php bloginfo('description'); ?></p>
			</div><!-- columns -->
		</div><!-- row -->
	</header>

<div class="row">
	<div id="main" class="columns large-12">

		<?php if( have_posts() ): while( have_posts() ): the_post(); ?>
		<article <?php post_class(); ?> itemscope itemtype="http://schema.org/Article">
			
			<span itemprop="articleBody">
			<?php the_content(); ?>
			</span>

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