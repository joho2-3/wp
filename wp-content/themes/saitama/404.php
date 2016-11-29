<?php
/**
 * The template for displaying 404 pages (not found).
 *
 * @package  saitama
 * @license  GNU General Public License v2.0
 * @since    saitama 1.0
 */

get_header(); ?>

	<div class="breadcrumbs">
		<div class="container">
			<h1 class="pull-left"><?php esc_html_e( 'Oops! That page can&rsquo;t be found.', 'saitama' ); ?></h1>
			<?php if ( function_exists( 'bread_crumb' ) ) bread_crumb( 'home_label=HOME&elm_class=pull-right breadcrumb' ); ?>
		</div>
	</div>

	<div class="container content">
		<div class="row">
			<div class="col-md-8 col-md-offset-2">
				<div class="error-v1">
					<span class="error-v1-title">404</span>
					<span><?php esc_html_e( 'It looks like nothing was found at this location. Maybe try one of the links below or a search?', 'saitama' ); ?></span>
					<p><a class="btn-u btn-bordered" href="<?php echo esc_html( home_url() ); ?>"><?php esc_html_e( 'Go to Top Page', 'saitama' ); ?></a></p>
				</div>
			</div>
		</div>
	</div>

<?php get_footer(); ?>
