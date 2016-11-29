<?php
/**
 * The template for displaying date archive pages.
 *
 * @package saitama
 */

get_header(); ?>

	<?php saitama_rtn_page_header(); ?>

	<div class="container content">
		<div class="row blog-page">
			<div class="col-md-9 md-margin-bottom-40">

			<?php if ( have_posts() ) : ?>

				<ul class="timeline-v1">

				<?php while ( have_posts() ) : the_post(); ?>
					<li>
						<div class="timeline-badge primary">
							<i class="glyphicon glyphicon-record<?php if($post_counter % 2 == 1): ?><?php else: ?> invert<?php endif; ?>"></i>
						</div>

						<div class="timeline-panel">

							<div class="timeline-heading">
								<a href="<?php the_permalink(); ?>"><?php the_post_thumbnail( array(800, 600), array('class' => 'img-responsive') ); ?></a>
							</div>

							<div class="timeline-body text-justify">
								<h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
								<?php the_excerpt(); ?>
								<a class="btn-u btn-u-sm" href="<?php the_permalink(); ?>"><?php echo esc_html_e('Read More', 'saitama'); ?></a>
							</div>

							<div class="timeline-footer">
								<ul class="list-unstyled list-inline blog-info">
									<li><i class="fa fa-clock-o"></i>  <?php echo esc_html( get_the_date() ); ?></li>
									<li><i class="fa fa-pencil"></i> <?php the_author_posts_link(); ?></li>
									<li><i class="fa fa-tags"></i> <?php the_category(', '); ?><?php the_tags(', ',', ',''); ?></li>
								</ul>
							</div>

						</div>

					</li>

				<?php endwhile; ?>

				</ul>

				<div class="text-center">
					<?php if ( function_exists('page_navi') ) page_navi( 'elm_class=pagination&current_class=active&current_format=<a href="#">%d</a>' ); ?>
				</div>

			<?php else : ?>
				<div class="well"><p><?php _e('No posts.','saitama');?></p></div>
			<?php endif; // have_post() ?>

			</div>

			<?php get_sidebar(); ?>

		</div>
	</div>

<?php get_footer(); ?>