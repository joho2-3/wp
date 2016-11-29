<?php
/**
 * The template for displaying archive pages for Welcart e-commerce templates.
 * For Welcart e-commcer plugin only.
 * @link		http://welcustom.net/
 * @author		Mamekko
 * @copyright	Copyright (c) 2015 welcustom.net
 */
get_header(); ?>

<?php get_template_part('breadcrumbs-item'); ?>

<div class="row">
	<div id="main" class="columns">
		
		<h1 class="archive-title font-quicksand"><?php the_archive_title(); ?></h1>
		<?php the_archive_description(); ?>

		<?php if( have_posts() ): ?>
		<ul class="medium-block-grid-4 small-block-grid-2">
			<?php while( have_posts() ): the_post(); ?>
			<?php get_template_part('thumbnail-box'); ?>
			<?php endwhile; ?>
		</ul>
		<?php endif; ?>
		
		<?php
			the_posts_pagination( array(
				'prev_text' => '&lt;',
				'next_text' => '&gt;',
				'type' => 'list',
			) );
		?>
		
	</div><!-- columns -->
	
</div><!-- row -->

<?php get_footer(); ?>