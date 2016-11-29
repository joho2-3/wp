<?php
/**
 * <meta content="charset=UTF-8">
 * @package Welcart
 * @subpackage Welcart Default Theme
 *
 * @link		http://welcustom.net/
 * @arranged by	Mamekko
 */
get_header();
?>

<?php get_template_part('breadcrumbs-item'); ?>

<div class="row">
	<div id="main" class="columns">
		
		<h1 class="archive-title font-quicksand"><?php _e( 'Multiple Category Search', 'blanc' ); ?></h1>
		
		<?php $uscpaged = isset($_REQUEST['paged']) ? $_REQUEST['paged'] : 1; ?>
		<script type="text/javascript">
			function usces_nextpage() {
				document.getElementById('usces_paged').value = <?php echo ($uscpaged + 1); ?>;
				document.searchindetail.submit();
			}
			function usces_prepage() {
				document.getElementById('usces_paged').value = <?php echo ($uscpaged - 1); ?>;
				document.searchindetail.submit();
			}
			function newsubmit() {
				document.getElementById('usces_paged').value = 1;
			}
		</script>
		
		<?php
		usces_remove_filter();
		if(isset($_REQUEST['usces_search'])) :
			$catresult = usces_search_categories();
			$search_query = array('category__and' => $catresult, 'posts_per_page' => 12, 'paged' => $uscpaged);
			$search_query = apply_filters('usces_filter_search_query', $search_query);
			$my_query = new WP_Query( $search_query );
		?>
		
		<p><?php _e('Search results', 'usces'); ?>  <?php echo number_format($my_query->found_posts); ?><?php _e('cases', 'usces'); ?></p>

		<?php if( $my_query->have_posts() ): ?>

		<?php if( $search_result = apply_filters('usces_filter_search_result', NULL, $my_query)) : ?>
		<?php echo $search_result; ?>
		<?php else : ?>
		<ul class="medium-block-grid-4 small-block-grid-2">
			<?php while( $my_query->have_posts() ): $my_query->the_post(); usces_the_item(); ?>
			<?php get_template_part('thumbnail-box'); ?>
			<?php endwhile; ?>
		</ul>
		
		<div class="text-center">
			<?php if( $uscpaged > 1 ) : ?>
			<a onclick="usces_prepage();" class="button small secondary"><?php _e('&laquo; Previous article', 'usces'); ?></a>
			<?php endif; ?>
			<?php if( $uscpaged < $my_query->max_num_pages ) : ?>
			<a onclick="usces_nextpage();" class="button small secondary"><?php _e('Next article &raquo;', 'usces'); ?></a>
			<?php endif; ?>
		</div>
		
		<?php endif; ?>

		<?php else: ?>
		<p><?php _e('The article was not found.', 'usces'); ?></p>

		<?php endif; wp_reset_query(); ?>
		
		<?php endif; ?>
		
		<section>
			<form name="searchindetail" action="<?php echo USCES_CART_URL . $this->delim; ?>page=search_item" method="post">
				<?php echo usces_categories_checkbox('return'); ?>
				<input name="usces_search_button" class="usces_search_button" type="submit" value="<?php _e('Search', 'usces'); ?>" onclick="newsubmit()" />
				<?php printf( '<input name="paged" id="usces_paged" type="hidden" value="%s" />', esc_attr( $uscpaged )); ?>
				<input name="usces_search" type="hidden" />
				<?php do_action('usces_action_search_item_inform'); ?>
			</form>
		</section>
		
	</div><!-- columns -->
	
</div><!-- row -->

<?php get_footer(); ?>