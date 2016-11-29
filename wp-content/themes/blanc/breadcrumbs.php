<?php
/**
 * The template for displaying breadcrumbs.
 * @link		http://welcustom.net/
 * @author		Mamekko
 * @copyright	Copyright (c) 2015 welcustom.net
 */
?>

<div class="row">
	<div class="columns">
		<ol class="breadcrumbs">

<?php

global $post;
$front_page = get_option('page_on_front');
$home = get_option('page_for_posts');
$str ='';

if( !is_admin() ){
	if( $front_page != 0 ) { //if there is a frontpage
		if( is_front_page() ) {
			$str .='<li itemscope itemtype="http://data-vocabulary.org/Breadcrumb"><span itemprop="title">' .get_the_title($front_page) .'</span></li>';
		} else {
			$str .= '<li itemscope itemtype="http://data-vocabulary.org/Breadcrumb"><a href="'. home_url('/') .'" itemprop="url"><span itemprop="title">' .get_the_title($front_page) .'</span></a></li>';
			if( is_home() ){
				$str .='<li itemscope itemtype="http://data-vocabulary.org/Breadcrumb"><span itemprop="title">' .get_the_title($home);
				if( is_paged() ){
					$current = ( get_query_var('paged') ) ? get_query_var('paged') : 1;
					$str .= sprintf( __(' - Page %d', 'blanc'), $current );
				}
				$str .= '</span></li>';
			} elseif( !is_page() ) {
				$str .='<li itemscope itemtype="http://data-vocabulary.org/Breadcrumb"><a href="'. get_permalink($home) .'" itemprop="url"><span itemprop="title">' .get_the_title($home) .'</span></a></li>';
			}
		}
	} else { //if there isn't a frontpage
		if( is_home() ) {
			$str .='<li itemscope itemtype="http://data-vocabulary.org/Breadcrumb"><span itemprop="title">' .__( "Home", "blanc" );
			if( is_paged() ){
				$current = ( get_query_var('paged') ) ? get_query_var('paged') : 1;
				$str .= sprintf( __(' - Page %d', 'blanc'), $current );
			}
			$str .= '</span></li>';
		} else {
			$str .='<li itemscope itemtype="http://data-vocabulary.org/Breadcrumb"><a href="'. home_url('/') .'" itemprop="url"><span itemprop="title">' .__( "Home", "blanc" ) .'</span></a></li>';
		}
	}
	if( is_attachment() ){
		if( $post -> post_parent != 0 ){
			$post_parent = $post->post_parent;
			$str.= '<li itemscope itemtype="http://data-vocabulary.org/Breadcrumb"><a href="' .get_permalink( $post->post_parent ).'" itemprop="url"><span itemprop="title">' .get_the_title($post -> post_parent) .'</span></a></li>';
		}
	} elseif( is_page() && !is_front_page() ){
		if( $post->post_parent != 0 ){
			$ancestors = array_reverse( get_post_ancestors( $post->ID ) );
			foreach( $ancestors as $ancestor ){
				$str .='<li itemscope itemtype="http://data-vocabulary.org/Breadcrumb"><a href="'. get_permalink($ancestor) .'" itemprop="url"><span itemprop="title">' .get_the_title($ancestor) .'</span></a></li>';
			}
		}
		$str .= '<li itemscope itemtype="http://data-vocabulary.org/Breadcrumb"><span itemprop="title">' .$post->post_title .'</span></li>';
	} elseif( is_single() ){
		$categories = get_the_category($post->ID);
		$cat = $categories[0];
		if( $cat->parent != 0 ){
			$ancestors = array_reverse(get_ancestors( $cat -> cat_ID, 'category' ));
			foreach($ancestors as $ancestor){
			$str .='<li itemscope itemtype="http://data-vocabulary.org/Breadcrumb"><a href="'. get_category_link($ancestor) .'" itemprop="url"><span itemprop="title">' .get_cat_name($ancestor) .'</span></a></li>';
			}
		}
		$str .='<li itemscope itemtype="http://data-vocabulary.org/Breadcrumb"><a href="' .get_category_link($cat->term_id). '" itemprop="url"><span itemprop="title">' .$cat->cat_name .'</span></a></li>';
		$str .= '<li itemscope itemtype="http://data-vocabulary.org/Breadcrumb"><span itemprop="title">' .$post->post_title .'</span></li>';
	} elseif( is_search() ){
		$s_word = get_search_query();
		$str .='<li itemscope itemtype="http://data-vocabulary.org/Breadcrumb"><span itemprop="title">' .sprintf( __("Results for '%s'", "blanc"), $s_word ) .'</span></li>';
	} elseif( is_404() ){
		$str .='<li itemscope itemtype="http://data-vocabulary.org/Breadcrumb"><span itemprop="title">' .__('404 Not found','blanc') .'</span></li>';
	} elseif( is_category() ){
		$cat = get_queried_object();
			if( $cat->parent != 0 ){
				$ancestors = array_reverse( get_ancestors( $cat->cat_ID, 'category' ) );
				foreach( $ancestors as $ancestor ){
					$str .= '<li itemscope itemtype="http://data-vocabulary.org/Breadcrumb"><a href="'. get_category_link($ancestor) .'" itemprop="url"><span itemprop="title">' .get_cat_name($ancestor) .'</span></a></li>';
				}
			}
			$str .= '<li itemscope itemtype="http://data-vocabulary.org/Breadcrumb" class="current"><span itemprop="title">' .$cat->name;
			if( is_paged() ){
				$current = ( get_query_var('paged') ) ? get_query_var('paged') : 1;
				$str .= sprintf( __(' - Page %d', 'blanc'), $current );
			}
			$str .= '</span></li>';
	} elseif( is_tag() || is_author() ){
		$str.='<li itemscope itemtype="http://data-vocabulary.org/Breadcrumb" class="current"><span itemprop="title">' . get_the_archive_title();
		if( is_paged() ){
				$current = (get_query_var('paged')) ? get_query_var('paged') : 1;
				$str .= sprintf( __(' - Page %d', 'blanc'), $current );
			}
		$str .= '</span></li>';
	} elseif ( is_date() ){
		$date = __('jS', 'blanc');
		$month = __('F', 'blanc');
		$year = __('Y', 'blanc');
		if( is_day() ){
			$str .= '<li itemscope itemtype="http://data-vocabulary.org/Breadcrumb"><a href="' .get_year_link( get_the_time('Y') ) .'" itemprop="url">' .get_the_date($year) .'</a></li>';
			$str .= '<li itemscope itemtype="http://data-vocabulary.org/Breadcrumb"><a href="' .get_month_link( get_the_time('Y'), get_the_time('n') ) .'" itemprop="url"><span itemprop="title">' .get_the_date($month) .'</span></a></li>';
			$str .= '<li itemscope itemtype="http://data-vocabulary.org/Breadcrumb"><span itemprop="title">' .get_the_date($date) .'</span></li>';
		} elseif( is_month() ){
			$str .= '<li itemscope itemtype="http://data-vocabulary.org/Breadcrumb"><a href="' .get_year_link( get_the_time('Y') ) .'" itemprop="url"><span itemprop="title">' .get_the_date($year) .'</span></a></li>';
			$str .= '<li itemscope itemtype="http://data-vocabulary.org/Breadcrumb"><span itemprop="title">' .get_the_date($month) .'</span></li>';
		} else {
			$str .= '<li itemscope itemtype="http://data-vocabulary.org/Breadcrumb"><span itemprop="title">' .get_the_date($year) .'</span></li>';
		}
	}
}
echo $str;

?>
		</ol>
	</div>
</div>