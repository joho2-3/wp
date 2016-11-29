<?php
/**
 * The template for displaying breadcrumbs for Welcart e-commerce templates.
 * For Welcart e-commcer plugin only
 * @link		http://welcustom.net/
 * @author		Mamekko
 * @copyright	Copyright (c) 2015 welcustom.net
 */
?>

<div class="row">
	<div class="columns">
		<ol class="breadcrumbs">

<?php

global $post, $usces;
$front_page = get_option('page_on_front');
$home = get_option('page_for_posts');
$item_name = get_category_by_slug('item')->name;
$item_id = get_cat_ID($item_name);
$str ='';

if( !is_admin() ){
	if( $front_page != 0 ) { //if there is a frontpage
		$str .= '<li itemscope itemtype="http://data-vocabulary.org/Breadcrumb"><a href="'. home_url('/') .'" itemprop="url"><span itemprop="title">' .get_the_title($front_page) .'</span></a></li>';
		if( !is_page() && !is_search() && !is_category($item_id) ) {
			$str .='<li itemscope itemtype="http://data-vocabulary.org/Breadcrumb"><a href="'. get_category_link($item_id) .'" itemprop="url"><span itemprop="title">' .$item_name .'</span></a></li>';
		}
	} else { //if there isn't a frontpage
		$str .='<li itemscope itemtype="http://data-vocabulary.org/Breadcrumb"><a href="'. home_url('/') .'" itemprop="url"><span itemprop="title">' .__( "Home", "blanc" ) .'</span></a></li>';
	}
	if( is_single() ){
		$str .= '<li itemscope itemtype="http://data-vocabulary.org/Breadcrumb">';
		$cats = '';
        $categories = get_the_category();
        foreach( $categories as $category ){
            $category_id = $category->term_id;
            $category_child = get_term_children($category_id, 'category');
            if( $category_child != true ){
                $cats .= '<a href="'. get_category_link($category_id) .'" itemprop="url"><span itemprop="title">' . get_cat_name($category_id) . '</span></a>,&nbsp;';
            }
        }
		$str.= rtrim( $cats, ',&nbsp;' );
		$str.= '</li><li itemscope itemtype="http://data-vocabulary.org/Breadcrumb"><span itemprop="title">'. $post -> post_title .'</span></li>';
	} elseif( is_category() ){
		$cat = get_queried_object();
		$str.='<li itemscope itemtype="http://data-vocabulary.org/Breadcrumb"><span itemprop="title">'. $cat -> name;
		if( is_paged()){
			$current = ( get_query_var('paged') ) ? get_query_var('paged') : 1;
			$str.= sprintf(__(" - Page %d", "blanc"), $current );
		}
		$str.='</span></li>';
	} elseif( is_tag() || is_author() ){
		$str.='<li itemscope itemtype="http://data-vocabulary.org/Breadcrumb" class="current"><span itemprop="title">' . get_the_archive_title();
		if( is_paged() ){
				$current = (get_query_var('paged')) ? get_query_var('paged') : 1;
				$str .= sprintf( __(' - Page %d', 'blanc'), $current );
			}
		$str .= '</span></li>';
	} elseif( is_search() ) {
		$s_word = get_search_query();
		$str .='<li itemscope itemtype="http://data-vocabulary.org/Breadcrumb"><span itemprop="title">' .sprintf( __("Results for '%s'", "blanc"), $s_word ) .'</span></li>';
	} elseif( $usces->page == 'search_item' ) {
		$str.='<li itemscope itemtype="http://data-vocabulary.org/Breadcrumb" class="current"><span itemprop="title">' . __( 'Multiple Category Search', 'blanc' ) .'</span></li>';
	}
}
echo $str;

?>
		</ol>
	</div>
</div>