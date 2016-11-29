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

<article <?php post_class(); ?>>
	<?php if( have_posts() ): the_post(); ?>
	<?php usces_remove_filter(); ?>
	<?php usces_the_item(); ?>
	
	<div class="row" itemscope itemtype="http://schema.org/Product">
		<div class="columns medium-7">
			<div id="slider" class="flexslider slider-item">
				<ul class="slides">
					<li><a href="<?php usces_the_itemImageURL(0); ?>" <?php echo apply_filters('usces_itemimg_anchor_rel', NULL); ?>><?php usces_the_itemImage(0, 640, 640, $post); ?></a></li>
					<?php $imageid = usces_get_itemSubImageNums(); if($imageid): foreach ( $imageid as $id ) : ?>
					<li><a href="<?php usces_the_itemImageURL($id); ?>" <?php echo apply_filters('usces_itemimg_anchor_rel', NULL); ?>><?php usces_the_itemImage($id, 640, 640, $post); ?></a></li>
					<?php endforeach; endif; ?>
				</ul>
			</div>
			<?php if( $imageid ): ?>
			<div id="carousel" class="flexslider">
				<ul class="slides">
					<li><?php usces_the_itemImage(0, 200, 200, $post); ?></li>
					<?php foreach ( $imageid as $id ) : ?>
					<li><?php usces_the_itemImage($id, 200, 200, $post); ?></li>
					<?php endforeach; ?>
				</ul>
			</div>
			<?php endif; ?>
		</div><!-- columns -->
		<div class="columns medium-5">
			<h1 class="entry-title item-title" itemprop="name"><?php usces_the_itemName(); ?></h1>
			<time datetime="<?php echo get_the_date('c'); ?>" class="updated hide"><?php echo get_the_date(); ?></time>
					
			<form action="<?php echo USCES_CART_URL; ?>" method="post" class="item-form">
				<div itemprop="offers" itemscope itemtype="http://schema.org/Offer">
					<meta itemprop="availability" href="http://schema.org/InStock" content="<?php usces_the_itemZaiko(); ?>">
					<?php if( usces_sku_num() === 1 ): usces_have_skus(); ?><!--1SKU-->
						
						<div class="item-price text-gray">
							<?php if( usces_the_itemCprice('return') > 0 ) : ?>
								<span class="item-cprice"><?php usces_the_itemCpriceCr(); ?><?php usces_guid_tax(); ?></span>
							<?php endif; ?>

							<span itemprop="price">
								<b class="font-bigger"><?php usces_the_itemPriceCr(); ?></b><?php usces_guid_tax(); ?>
							</span>
						</div>
					
						<?php usces_the_itemGpExp(); ?>

						<?php if( usces_have_zaiko() ): ?>
							<?php if (usces_is_options()): while (usces_have_options()) : ?>
								<table class="item-option">
									<tbody>
										<tr><td><?php usces_the_itemOptName(); ?></td><td><?php usces_the_itemOption(usces_getItemOptName(),''); ?></td></tr>
									</tbody>
								</table>
							<?php endwhile; endif; ?>
							<span class="item-quant"><?php usces_the_itemQuant(); ?></span><?php usces_the_itemSkuUnit(); ?>
							<?php usces_the_itemSkuButton('&#xf07a;&nbsp;' .__( 'Add to Cart', 'blanc' ), 0); ?>
					
						<?php else: ?>
					
							<?php $stock = usces_get_itemZaiko( 'name' ); ?>
							<?php echo sprintf(__('<p class="item-stock">This item is currently %s.</p>', 'blanc'), $stock ); ?>
							<?php echo apply_filters('usces_filters_single_sku_zaiko_message', esc_html(usces_get_itemZaiko( 'name' ))); ?>
						<?php endif; ?>
						<?php echo apply_filters('single_item_single_sku_after_field', NULL); ?>
				
					<?php elseif( usces_sku_num() > 1 ): usces_have_skus(); ?><!--some SKU-->
					
						<?php do { ?>
						<div class="item-multisku">
							<div class="item-price text-gray">
								<b class="text-gray" style="margin-right: .25rem;"><?php usces_the_itemSkuDisp(); ?></b>
								<?php if( usces_the_itemCprice('return') > 0 ) : ?>
									<span class="item-cprice"><?php usces_the_itemCpriceCr(); ?><?php usces_guid_tax(); ?></span>
								<?php endif; ?>
								<span itemprop="price">
									<b class="font-bigger"><?php usces_the_itemPriceCr(); ?></b><?php usces_guid_tax(); ?>
								</span>
							</div>
							<?php usces_the_itemGpExp(); ?>
					
							<?php if( usces_have_zaiko() ): ?>
								<?php if (usces_is_options()): while (usces_have_options()) : ?>
									<table class="item-option">
										<tbody>
											<tr><td><?php usces_the_itemOptName(); ?></td><td><?php usces_the_itemOption(usces_getItemOptName(),''); ?></td></tr>
										</tbody>
									</table>
								<?php endwhile; endif; ?>
								<span class="item-quant"><?php usces_the_itemQuant(); ?></span><?php usces_the_itemSkuUnit(); ?>
								<?php usces_the_itemSkuButton('&#xf07a;&nbsp;' .__( 'Add to Cart', 'blanc' ), 0); ?>
							<?php else: ?>
								<?php $stock = usces_get_itemZaiko( 'name' ); ?>
								<?php echo sprintf(__('<p class="item-stock">This item is currently %s.</p>', 'blanc'), $stock ); ?>
								<?php echo apply_filters('usces_filters_multi_sku_zaiko_message', esc_html(usces_get_itemZaiko( 'name' ))); ?>

							<?php endif; ?>
						</div>
						<?php } while (usces_have_skus()); ?>
						<?php echo apply_filters('single_item_multi_sku_after_field', NULL); ?>
					
					<?php endif; ?>
				</div>
				
				<?php do_action('usces_action_single_item_inform'); ?>
			</form>
			
			<?php do_action('usces_action_single_item_outform'); ?>
			<?php usces_singleitem_error_message($post->ID, usces_the_itemSku('return')); ?>
			
			<?php if( $item_custom = usces_get_item_custom( $post->ID, 'table', 'return' ) ){ echo $item_custom; } ?>
			
			<div itemprop="description">
				<?php the_content(); ?>
				<?php the_tags('<p class="text-gray"><i class="fa fa-tag"></i> ', ',', '</p>'); ?>
			</div>
			
		</div><!-- columns -->
	</div><!-- row -->
	
	<?php get_template_part('related-item'); ?>
	
	<div class="row">
		<div class="columns">
			<?php comments_template('/comments-item.php'); ?>
		</div>
	</div>
	
	<?php endif; ?>
</article>

<?php get_footer(); ?>