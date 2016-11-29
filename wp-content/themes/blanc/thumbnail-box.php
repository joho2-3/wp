<?php
/**
 * The template for displaying thumbnail boxes in item archive pages and related item list in single page
 * For Welcart e-commerce plugin only.
 * @link		http://welcustom.net/
 * @author		Mamekko
 * @copyright	Copyright (c) 2015 welcustom.net
 */
?>

<?php if(function_exists('usces_the_item')): ?>
<li>
	<article <?php post_class('thumbnail-box'); ?>>

		<?php usces_the_item(); usces_have_skus(); ?>
		<a href="<?php the_permalink(); ?>">
			<?php if( has_post_thumbnail()) {
				the_post_thumbnail( 'medium' );
			} else {
				usces_the_itemImage($number=0, $width=228, $height=228);
			} ?>
			<h2 class="thumb-title entry-title"><?php usces_the_itemName(); ?></h2>
				<?php usces_the_itemPriceCr(); ?><?php usces_guid_tax(); ?>
			<?php if(!usces_have_zaiko_anyone()): ?>
				<?php
				$status = usces_get_itemZaiko( 'id' );
				switch( $status ){
					case 2:
						echo '<span class="label alert">'.__("Sold Out", "usces").'</span>';
						break;
					case 3:
						echo '<span class="label success">'.__("Out Of Stock", "usces").'</span>';
						break;
				}
				?>
			<?php endif; ?>
		</a>

	</article>
</li>
<?php endif; ?>