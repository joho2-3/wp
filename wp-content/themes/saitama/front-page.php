<?php
/**
 * The Front Page template file.
 *
 * @package  saitama
 * @license  GNU General Public License v2.0
 * @since    saitama 1.0
 */
?>
<?php $options = get_option( 'saitama_theme_options' ); ?>
<?php get_header(); ?>

	<div class="container content">
		<div class="row magazine-page">
			<div class="col-md-9">
				<?php echo saitama_get_header_image(); ?>

				<?php if ( is_active_sidebar('front-page-top') ) : ?>
				<?php dynamic_sidebar('front-page-top'); ?>
				<?php endif; ?>

				<div class="magazine-news">
					<div class="headline"><h2><?php esc_html_e( 'What`s New', 'saitama' ); ?></h2></div>
					<div class="row" id="front-news">
					<?php
						$posts_cnt = 0;
						$max_posts_cnt = $wp_query->post_count;
						while( have_posts() ) : the_post();
							$posts_cnt++;
					?>
							<?php if ( $posts_cnt % 3 === 1 ) : ?>
							<div class="col-md-12">
							<?php endif; ?>
								<div class="col-md-4 frontbox">
									<div class="magazine-news-img">
										<a href="<?php the_permalink(); ?>"><?php the_post_thumbnail( array( 800, 600 ), array( 'class' => 'img-responsive' ) ); ?></a>
									</div>

									<h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
									<div class="by-author">
										<i class="fa fa-clock-o"></i> <?php echo esc_html( get_the_date() ); ?> / 
										<i class="fa fa-pencil"></i> <?php the_author_posts_link(); ?><br />
										<i class="fa fa-tags"></i> <?php the_category(', '); ?><?php the_tags(', ', ', ', ''); ?><br/>
									</div>

									<?php the_excerpt(); ?>

									<a class="btn-u btn-u-sm" href="<?php the_permalink(); ?>"><?php esc_html_e( 'Read More', 'saitama' ); ?></a>
								</div>
							<?php if ( $posts_cnt % 3 === 0 || $posts_cnt == $max_posts_cnt ) : ?>
							</div>
							<?php endif; ?>
						<?php endwhile; ?>
						<?php wp_reset_postdata(); ?>
					</div><!-- .row -->
				</div><!-- .magazine-news -->

			</div><!-- .col-md-9 -->

			<?php get_sidebar(); ?>

		</div><!-- .magazine-page -->
	</div><!-- container -->

<?php get_footer(); ?>
