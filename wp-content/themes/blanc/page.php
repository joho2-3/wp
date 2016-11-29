<?php
/**
 * The template for displaying pages.
 * @link		http://welcustom.net/
 * @author		Mamekko
 * @copyright	Copyright (c) 2015 welcustom.net
 */
get_header();
?>

<?php get_template_part('breadcrumbs'); ?>

<div class="row">
	<div id="main" class="columns large-9">

		<?php if( have_posts() ): while( have_posts() ): the_post(); ?>
		<article <?php post_class(); ?> itemscope itemtype="http://schema.org/Article">
			<h1 itemprop="name" class="entry-title"><?php the_title(); ?></h1>
			
			<span itemprop="articleBody">
			<?php the_content(); ?>
			<?php wp_link_pages('before=<div id="page-links">&after=</div>&pagelink=<span>%</span>'); ?>
			</span>

		</article>
		<?php endwhile; endif; ?>

		<?php comments_template(); ?>
		
	</div><!-- columns -->
	
	<div id="sidebar" class="columns large-3">
		<?php dynamic_sidebar('column-page'); ?>
	</div><!-- columns -->
	
</div><!-- row -->

<?php get_footer(); ?>