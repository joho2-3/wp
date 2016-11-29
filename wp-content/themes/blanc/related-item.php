<?php
/**
 * The template for displaying related items for Welcart e-commerce templates.
 * For Welcart e-commcer plugin only.
 * @link		http://welcustom.net/
 * @author		Mamekko
 * @copyright	Copyright (c) 2015 welcustom.net
 */

global $post, $usces;
$categories = get_the_category($post->ID);
if( $categories ){
	$category_ids = array();
	foreach( $categories as $category){
		$category_id = $category->term_id;
		$category_child = get_term_children($category_id, 'category');
		if($category_child != true){
			$category_ids[] = $category->term_id ;
		}
	}
$args=array(
	'category__in' => $category_ids,
	'post__not_in' => array($post->ID),
	'posts_per_page'=> 4,
	'ignore_sticky_posts'=> 1,
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
);
	$my_query = new WP_Query($args);
	if( $my_query->have_posts() ) {
		echo '<aside class="aside-related-item">
			<div class="row">
			<div class="columns">
			<h1 class="font-quicksand">'.__( "YOU MAY ALSO LIKE...", "blanc" ) .'</h1>
			<ul class="medium-block-grid-4 small-block-grid-2">';
			while ($my_query->have_posts()) {
				$my_query->the_post();
				echo get_template_part('thumbnail-box');
			}
		echo '</ul></div>
			</div>
			</aside>';
		wp_reset_query();
	}
}