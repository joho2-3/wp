<?php
/**
 * The template for displaying search results pages.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#search-result
 *
 * @package saitama
 */

get_header(); ?>

	<?php saitama_rtn_page_header(); ?>

	<div class="container content">
		<div class="row blog-page">
			<div class="col-md-9 md-margin-bottom-40">

				<?php if ( have_posts() ) : ?>

					<?php while ( have_posts() ) : the_post(); ?>
						<div class="row blog blog-medium margin-bottom-40">
							<div class="col-md-5">
								<a href="<?php the_permalink(); ?>"><?php the_post_thumbnail( array(800, 600), array('class' => 'img-responsive') ); ?></a>
							</div>

							<div class="col-md-7">
								<h2 class="topictitle"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
								<ul class="list-unstyled list-inline blog-info">
									<li><i class="fa fa-clock-o"></i> <?php echo esc_html( get_the_date() ); ?></li>
									<li><i class="fa fa-pencil"></i> <?php the_author_posts_link(); ?></li>
								</ul>
								<ul class="list-unstyled list-inline blog-info">
									<li><i class="fa fa-tags"></i> <?php the_category(', '); ?><?php the_tags(', ',', ',''); ?></li>
								</ul>

								<?php the_excerpt(); ?>

								<p><a class="btn-u btn-u-smaill" href="<?php the_permalink(); ?>"><i class="fa fa-location-arrow"></i> <?php echo esc_html_e('Read More', 'saitama'); ?></a></p>

							</div>

						</div>
						<hr class="margin-bottom-40">

					<?php endwhile; ?>

					<div class="text-center">
						<?php if ( function_exists('page_navi') ) page_navi( 'elm_class=pagination&current_class=active&current_format=<a href="#">%d</a>' ); ?>
					</div>

				<?php else : ?>

				  <div class="well"><p><?php _e('No posts.','saitama');?></p></div>

				<?php endif; ?>

			</div>

			<?php get_sidebar(); ?>

		</div>
	</div>

<?php get_footer(); ?>