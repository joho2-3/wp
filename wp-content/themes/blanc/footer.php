<?php
/**
 * The template for displaying Footer.
 * @link		http://welcustom.net/
 * @author		Mamekko
 * @copyright	Copyright (c) 2015 welcustom.net
 */
?>

<footer id="footer">

	<?php if( function_exists('usces_the_item') ): ?>
	<div class="row">
		<div class="columns medium-4 large-3">
			<?php dynamic_sidebar('column1'); ?>
		</div>

		<div class="columns medium-4 large-3">
			<?php dynamic_sidebar('column2'); ?>
		</div>

		<div class="columns medium-4 large-3">
			<?php dynamic_sidebar('column3'); ?>
		</div>

		<div class="columns medium-4 large-3">
			<?php dynamic_sidebar('column4'); ?>
		</div>
	</div><!-- row -->
	<?php endif; ?>

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