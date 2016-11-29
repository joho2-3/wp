<?php
/**
 * The template for displaying front page.
 * @link		http://welcustom.net/
 * @author		Mamekko
 * @copyright	Copyright (c) 2015 welcustom.net
 */
get_header(); ?>

<?php if( !is_paged() && get_header_image() ): ?>
<section class="flexslider-section">
	<div class="row">
		<div class="columns">
			<div class="flexslider">
				<?php $headers = get_uploaded_header_images(); ?>
				<?php if( $headers ): ?>
				<ul class="slides text-center font-quicksand clearfix">
					<?php foreach ($headers as $key => $value): ?>
					<?php
					//this code is refered to: http://frankiejarrett.com/get-an-attachment-id-by-url-in-wordpress/
					//in order to get attachment id from image url.
					$parse_url  = explode( parse_url( WP_CONTENT_URL, PHP_URL_PATH ), $value['url'] );
					$this_host = str_ireplace( 'www.', '', parse_url( home_url(), PHP_URL_HOST ) );
					$file_host = str_ireplace( 'www.', '', parse_url( $value['url'], PHP_URL_HOST ) );
					if ( ! isset( $parse_url[1] ) || empty( $parse_url[1] ) || ( $this_host != $file_host ) ) {
						return;
					}
					global $wpdb;
					$img_id = $wpdb->get_col( $wpdb->prepare( "SELECT ID FROM {$wpdb->prefix}posts WHERE guid RLIKE %s;", $parse_url[1] ) );
					?>
					<?php $img_meta = get_post( $img_id[0] ); ?>
					<li style="background-image:url(<?php echo $value['url']; ?>);">
						<?php if($img_meta->post_content && (strpos($img_meta->post_content, 'jpg')===false)): ?>
						<a href="<?php echo esc_html($img_meta->post_content); ?>">
						<?php endif; ?>
							<?php if($img_meta->post_title && (strpos($img_meta->post_title, 'jpg')===false)): ?>
							<p class="flex-title"><?php echo esc_html($img_meta->post_title); ?></p>
							<?php endif; ?>
							<?php if($img_meta->post_excerpt): ?>
							<p class="flex-caption"><?php echo esc_html($img_meta->post_excerpt); ?></p>
							<?php endif; ?>
						<?php if($img_meta->post_content && (strpos($img_meta->post_content, 'jpg')===false)): ?>
						</a>
						<?php endif; ?>
					</li>
					<?php endforeach; ?>
				</ul>
				<?php else: ?>
				<ul class="slides clearfix">
					<li><img src="<?php header_image(); ?>" alt="*" width="<?php echo get_custom_header()->width; ?>" height="<?php echo get_custom_header()->height; ?>"></li>
				</ul>
				<?php endif; ?>
			</div>
		</div>
	</div>
</section>
<?php endif; ?>

<div class="row">
	<div id="main" class="columns">
		
		<?php if( function_exists('usces_the_item') ): ?><!--New items for Welcart-->
		<?php
			function blanc_filter_where( $where = '' ) {
			global $wpdb;
			$where .= $wpdb->prepare( " AND post_date > %s", date( 'Y-m-d', strtotime('-15 days') ) );
			return $where;
			}
			add_filter( 'posts_where', 'blanc_filter_where' );
			$new_items = get_posts( array(
				'post_type' => 'post',
				'category_name' => 'item',
				'posts_per_page' => '4',
				'orderby' => 'rand',
				'suppress_filters' => false,
				'meta_query' => array(
					array(
						'key' => '_isku_',
						'value' => '"stocknum";s:1:"0"',
						'compare' => 'NOT LIKE',
					),
					array(
						'key' => '_isku_',
						'value' => '"stocknum";i:0',
						'compare' => 'NOT LIKE',
					)
				)
			));
			remove_filter( 'posts_where', 'blanc_filter_where' );
		?>
			<?php if( $new_items ): ?>
			<section class="section-frontpage section-newitems">
				<div class="row">
					<div class="columns">
						<h1 class="font-quicksand text-center"><?php _e("WHAT'S NEW","blanc"); ?><i class="fa fa-bookmark fa-fw"></i></h1>
						<ul class="medium-block-grid-4 small-block-grid-2">
						<?php foreach( $new_items as $post ): setup_postdata( $post ); ?>
						<?php get_template_part( 'thumbnail-box' ); ?>
						<?php endforeach; wp_reset_postdata(); ?>
						</ul>
					</div>
				</div>
			</section>
			<?php endif; ?>
		<?php endif; ?><!--/New items for Welcart-->

		<?php if( is_home() ): ?>
		<div class="row">
			<div id="main" class="columns large-9">
				<?php if( have_posts() ): while( have_posts() ): the_post(); ?>
				<article <?php post_class('clearfix archive'); ?> itemscope itemtype="http://schema.org/Article">

					<a href="<?php the_permalink(); ?>">
						<?php
						preg_match( '/wp-image-(\d+)/s', $post->post_content, $thumb );
						preg_match( '/< *img[^>]*src *= *["\']?([^"\']*)/i', $post->post_content, $thumb_link );
						if( has_post_thumbnail() ) {
							the_post_thumbnail( 'thumbnail' );
						} elseif( $thumb ){
							if( wp_get_attachment_image($thumb[1]) ){
								echo wp_get_attachment_image( $thumb[1], 'thumbnail' );
							} else {
								echo '<img src="' .$thumb_link[1]. '" alt="'. get_the_title() .'" width="150" height="150" class="attachment-thumbnail">';
							}
						} else {
							echo '<img src="' . get_template_directory_uri() . '/img/no-image.jpg" alt="No Image" width="150" height="150" class="attachment-thumbnail">';
						}; ?>
					</a>

					<a href="<?php the_permalink(); ?>"><h1 itemprop="name" class="entry-title"><?php the_title(); ?></h1></a>

					<ul class="inline-list inline-postmeta">
						<li>
							<span class="fa-stack text-red"><i class="fa fa-circle fa-stack-2x fa-red"></i><i class="fa fa-calendar fa-stack-1x fa-inverse"></i></span>
							<time datetime="<?php echo get_the_date('c'); ?>" itemprop="datePublished">
								<?php echo get_the_date(); ?>
							</time>
						</li>
						<?php if( has_category() ): ?>
						<li>
							<span class="fa-stack text-green"><i class="fa fa-circle fa-stack-2x fa-green"></i><i class="fa fa-folder fa-stack-1x fa-inverse"></i></span>
							<?php the_category(','); ?>
						</li>
						<li>
							<span class="fa-stack text-blue"><i class="fa fa-circle fa-stack-2x fa-blue"></i><i class="fa fa-comment fa-stack-1x fa-inverse"></i></span>
							<a href="<?php  comments_link(); ?>"><?php comments_number(); ?></a>
						</li>
						<?php endif; ?>
					</ul>

					<?php the_excerpt(); ?>
					<p class="medium-text-right more"><a href="<?php the_permalink(); ?>" class="button tiny alert"><?php _e('READ MORE','blanc'); ?>&nbsp;<i class="fa fa-angle-right"></i></a></p>

				</article>
				<?php endwhile; endif; ?>
				<?php
					the_posts_pagination( array(
						'prev_text' => '&lt;',
						'next_text' => '&gt;',
						'type' => 'list',
					) );
				?>
			</div>
			<div id="sidebar" class="columns large-3">
				<?php dynamic_sidebar('column-blog'); ?>
			</div><!-- columns -->
		</div>
		<?php else: ?>
		<?php if( have_posts() ): while( have_posts() ): the_post(); ?>
		<article <?php post_class(); ?> itemscope itemtype="http://schema.org/Article">

			<span itemprop="articleBody">
				<?php the_content(); ?>
			</span>
			
		</article>
		<?php endwhile; endif; ?>
		<?php endif; ?>
		
		<?php if(function_exists('usces_the_item') && term_exists('itemreco')): ?><!--Recommended items for Welcart-->
			<?php
				$itemreco = get_category_by_slug('itemreco');
				$itemreco_data = get_category($itemreco);
				if ( $itemreco_data->count != 0 ):
			?>
			<section class="section-frontpage section-recitems">
				<div class="row">
					<div class="columns">
						<?php $recommend_items = get_posts( array(
							'post_type' => 'post',
							'category_name' => 'itemreco',
							'posts_per_page' => '4',
							'orderby' => 'rand',
							'meta_query' => array(
								array(
									'key' => '_isku_',
									'value' => '"stocknum";s:1:"0"',
									'compare' => 'NOT LIKE',
								),
								array(
									'key' => '_isku_',
									'value' => '"stocknum";i:0',
									'compare' => 'NOT LIKE',
								)
							)
						)); ?>
						<?php if( $recommend_items ): ?>
						<h1 class="font-quicksand text-center"><?php _e('RECOMMENDS','blanc'); ?><i class="fa fa-star fa-fw"></i></h1>
						<ul class="medium-block-grid-4 small-block-grid-2">
						<?php foreach( $recommend_items as $post ): setup_postdata( $post ); ?>
						<?php get_template_part( 'thumbnail-box' ); ?>
						<?php endforeach; wp_reset_postdata(); ?>
						</ul>
						<?php endif; ?>
					</div>
				</div>
			</section>
			<?php endif; ?>
		<?php endif; ?><!--/Recommended items for Welcart-->
		
	</div><!-- columns -->

</div><!-- row -->

<?php get_footer(); ?>