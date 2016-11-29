<?php
/**
 * The template for displaying all single posts.
 *
 * @package saitama
 */

get_header(); ?>
<?php while ( have_posts() ) : the_post(); ?>

	<?php saitama_rtn_page_header(); ?>

	<div class="container content">
		<div class="row blog-page blog-item">
			<div class="col-md-9 md-margin-bottom-60">
				<div class="blog margin-bottom-bottom-40">
					<div class="blog-post-tags">
						<ul class="list-unstyled list-inline blog-info">
							<li><i class="fa fa-cloc-o"></i> <?php echo esc_html( get_the_date() ); ?></li>
							<li><i class="fa fa-pencil"></i> <?php the_author_posts_link(); ?></li>
						</ul>
						<ul class="list-unstyled list-inline blog-tags">
							<li><?php the_category(' '); ?><?php the_tags('', ' ', ''); ?></li>
						</ul>
					</div>

					<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
						<?php the_content(); ?>
					</div>

					<?php
						$args = array(
							'before'           => '<nav class="page-link"><dl><dt>Pages :</dt><dd>',
							'after'            => '</dd></dl></nav>',
							'link_before'      => '<span class="page-numbers">',
							'link_after'       => '</span>',
							'echo'             => 1 );
						wp_link_pages( $args );
					?>

				</div>
				<hr>

				<?php comments_template(); ?>

			</div>

			<?php get_sidebar(); ?>

		</div>
	</div>

<?php endwhile; ?>

<?php get_footer(); ?>
