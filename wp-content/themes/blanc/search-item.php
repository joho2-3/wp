<?php
/**
 * The template for search result pages for Welcart e-commerce templates.
 * For Welcart e-commcer plugin only.
 * @link		http://welcustom.net/
 * @author		Mamekko
 * @copyright	Copyright (c) 2015 welcustom.net
 */
get_header();
?>

<?php get_template_part('breadcrumbs-item'); ?>

<div class="row">
	<div id="main" class="columns">
		
		<h1 class="archive-title font-quicksand"><?php _e('SEARCH','blanc'); ?>&nbsp;"<?php echo get_search_query(); ?>"</h1>

		<?php if( have_posts() ): ?>
		<ul class="medium-block-grid-4 small-block-grid-2">
			<?php while( have_posts() ): the_post(); ?>
			<?php get_template_part('thumbnail-box'); ?>
			<?php endwhile; ?>
		</ul>
		
		<?php
			the_posts_pagination( array(
				'prev_text' => '&lt;',
				'next_text' => '&gt;',
				'type' => 'list',
			) );
		?>
		
		<?php else: ?>
		
		<div class="panel panel-default">
			<?php $search = get_search_query(); ?>
			<p><?php echo sprintf(__('Your search for "%s" did not match any results.', 'blanc'), $search ); ?></p>
			<p><?php _e('Try different keywords','blanc'); ?></p>
			<form action="<?php echo esc_url( home_url('/') ); ?>" class="searchform" id="searchform_s" method="get" role="search">
				<div class="row">
					<div class="columns large-12">
						<div class="row collapse postfix-radius" style="max-width: 600px;">
							<div class="small-10 columns">
								<input type="search" class="field" name="s" value="<?php esc_attr( get_search_query() ); ?>" id="s_posts" placeholder="<?php _e('Search...','blanc'); ?>">
							</div>
							<div class="small-2 columns">
								<input type="submit" class="submit button postfix black font-awesome" id="searchsubmit_icon" value="&#xf002;">
							</div>
						</div>
					</div>
				</div>
			</form>
		</div>
		
		
		<?php endif; ?>
		
	</div><!-- columns -->
	
</div><!-- row -->

<?php get_footer(); ?>