<?php
/**
 * Custom functions that act independently of the theme templates.
 *
 * @package  saitama
 * @license  GNU General Public License v2.0
 * @since    saitama 1.0
 */

/*
 * return header image
 */
function saitama_get_header_image() {
	$options = get_option( 'saitama_theme_options' );

	$html = '<div class="carousel slide carousel-v1 margin-bottom-40" id="myCarousel-1">'."\n";
	$html .= '<div class="carousel-inner">'."\n";

	$act = "active";
	for ( $i=1; $i<=5; $i++ ) {
		if ( !empty($options['slider'.$i.'_image']) ) {
			$html .= '<div class="item ' .  $act . '">'."\n";

			if ( !empty($options['slider'.$i.'_link_url']) ) {
				$html .= '<a href="' . $options['slider'.$i.'_link_url'] . '" ';

				if ($options['slider'.$i.'_blank']) {
					$html .= 'target="_blank" ';
				}

				$html .= ">\n";
			}
			$alt = !empty($options['slider'.$i.'_alt']) ? $options['slider'.$i.'_alt'] : '';

			$html .= '<img src="' . $options['slider'.$i.'_image'] . '" alt="' . $alt . '">'."\n";

			if ( !empty($options['slider'.$i.'_caption']) ) {
				$html .= '<div class="carousel-caption">'."\n";
				$html .= '<p>' . $options['slider'.$i.'_caption'] . "</p>\n";
				$html .= "</div>\n";
			}

			if ( !empty($options['slider'.$i.'_link_url']) ) {
				$html .= "</a>\n";
			}

			$html .= "</div>\n";
			$act = "";
		}
	}

	if ( !empty($act) ) {
		$html .= '<div class="item active">'."\n";
		$html .= '<img alt="" src="' . esc_url( get_header_image() ) . '">'."\n";
		$html .= "</div>\n";

	} else {
		$html .= "</div>\n";
		$html .= '<div class="carousel-arrow">'."\n";
		$html .= '<a data-slide="prev" href="#myCarousel-1" class="left carousel-control">'."\n";
		$html .= '<i class="fa fa-angle-left"></i>'."\n";
		$html .= "</a>\n";
		$html .= '<a data-slide="next" href="#myCarousel-1" class="right carousel-control">'."\n";
		$html .= '<i class="fa fa-angle-right"></i>'."\n";
		$html .= "</a>\n";
	}

	$html .= "</div>\n</div>\n";

	return $html;

}

/*
 * Filters wp_title to print a neat <title> tag based on what is being viewed.
 */
function saitama_wp_title( $title, $sep ) {
	$title = get_bloginfo('name');

	if ( is_home() || is_front_page() ) {
		$title = $title . '|' . get_bloginfo('description');

	} elseif ( is_404() ) {
		$title = esc_html__( 'Oops! That page can&rsquo;t be found.', 'saitama' ) . '|' . $title;

	} elseif ( is_category() ) {
		$title = single_cat_title( '', false ) . '|' . $title;

	} elseif ( is_tag() ) {
		$title = single_tag_title( '', false ) . '|' . $title;

	} elseif ( is_author() ) {
		$user = get_userdata($author);
		if ( !empty($user->Name) ) {
			$title = $user->Name . '|' . $title;
		} else {
			$title = get_the_author() . '|' . $title;
		} 

	} elseif ( is_date() ) {
		$title = sprintf( __( '%s of the article list', 'saitama' ), get_the_time( __( 'F Y', 'saitama' ) ) ) . '|' . $title;

	} else {
		$title = get_the_title() . '|' . $title;

	}

	return $title;

}
add_filter( 'wp_title', 'saitama_wp_title', 10, 2 );

/*
 * return body class
 */
function saitama_rtn_body_class($classes) {
	if ( is_home() || is_front_page() ) {
		if( ( $key = array_search('blog', $classes) ) !== FALSE ) {
			unset($classes[$key]);
		}
	}
	return $classes;
}
add_filter( 'body_class', 'saitama_rtn_body_class' );

/*
 * return page header
 */
function saitama_rtn_page_header() {
	$html = '<div class="breadcrumbs"><div class="container">';
	$html .= '<h1 class="pull-left">';

	if ( is_singular() ) {
		$html .= get_the_title();
		if ( get_edit_post_link() ) $html .= '&nbsp;&nbsp;(<a href="' . get_edit_post_link() . '">' . esc_html__('Edit', 'saitama') . '</a>)';
	} elseif( is_archive() ) {
		$html .= get_the_archive_title();
	} elseif( is_category() ) {
		$html .= single_cat_title( '', false );
	} elseif( is_tag() ) {
		$html .= single_tag_title( '', false );
	} elseif( is_author() ) {
		$html .= get_the_author() . ' ' . esc_html__( 'Post list', 'saitama' );
	} elseif ( is_search() ) {
		$html .= sprintf( esc_html__( 'Search Results for: %s', 'saitama' ), '<span>' . get_search_query() . '</span>' );
	} else {
		$html .= get_the_title();
	}

	$html .= '</h1>';

	if ( function_exists('bread_crumb') ) $html .= bread_crumb( 'home_label=HOME&elm_class=pull-right breadcrumb&echo=false' );

	$html .= '</div></div>';

	echo $html;
}

/*
 * return header text
 */
function saitama_rtn_head_text() {
	$html = '';
	$html = apply_filters( 'cc_header_text', $html );
	echo $html;
}

/*
 * change the end of the excerpt
 */
function saitama_custom_excerpt_more( $more ) {
	return ' ...';
}
add_filter( 'excerpt_more', 'saitama_custom_excerpt_more' );

/*
 * Increase the number of characters in the excerpt 
 */
function saitama_custom_excerpt_mblength( $length ) {
	return 100;
}
add_filter( 'excerpt_mblength', 'saitama_custom_excerpt_mblength' );

/*
 * Custom Search Form
 */
function saitama_custom_search_form( $form ) {
	$form = '<div class="input-group margin-bottom-30">';
	$form .= '<form method="get" action="' . home_url() . '">';
	$form .= '<input type="text" name="s" class="form-control" placeholder="' . __( 'Site Search', 'saitama' ) . '" value="">';
	$form .= '<span class="input-group-btn">';
	$form .= '<input type="submit" class="btn-u" value="' . __( 'Search', 'saitama' ) . '" />';
	$form .= '</span>';
	$form .= '</form></div>';
	return $form;
}
add_filter( 'get_search_form', 'saitama_custom_search_form' );

/**
 * Custom Comment List
 */
function saitama_custom_comment( $comment, $args, $depth ) {
	$GLOBALS['comment'] = $comment;
	extract($args, EXTR_SKIP);

	if ( 'div' == $args['style'] ) {
		$add_below = 'comment';
	} else {
		$add_below = 'div-comment';
	}

	$comment_reply = comment_reply_link( array_merge( $args, array(
							'reply_text' => __( 'Reply', 'saitama' ),
							'add_below' => $add_below,
							'depth' => $depth,
							'max_depth' => $args['max_depth']
							)
						)
					);

	$html =  '<div class="media">';
	$html .= '<div class="pull-left">' . get_avatar( $comment, $args['avatar_size'] ) . '</div>';
	$html .= '<div class="media-body">';
	$html .= '<h4 class="media-heading">' . get_comment_author_link();
	$html .= '<span>' . printf( ('%1$s %2$s'), get_comment_date(), get_comment_time() ). '/' . $comment_reply . '</span>';
	$html .= '</h4>' . get_comment_text() . '</div></div><hr>';

	echo $html;
}

/**
 * Footer CopyRight
 */
function saitama_footerCopyRight() {
	$copyright = '<div>Copyright &copy; ' . get_bloginfo('name') . ' All Rights Reserved.</div>';

	if ( 'ja' != get_locale() ) {
		$wpUrl = 'https://wordpress.org/';
	} else {
		$wpUrl = 'https://ja.wordpress.org/';
	}

	$themeUrl = 'https://www.communitycom.jp/saitama';

	$powered = '<div id="powered">Powered by <a href="' . $wpUrl . '" target="_blank">WordPress</a> &amp; ';
	$powered .= '<a href="' . $themeUrl . '" target="_blank">saitama Theme</a> by Commnitycom,Inc.</div>';
	$powered = apply_filters( 'custom_powered', $powered );

	$html = '<div class="copyright"><div class="container"><div class="row"><div class="col-md-12">';

	$html .= apply_filters( 'cc_copyright_custom', $copyright );
	$html .= apply_filters( 'cc_powered_custom', $powered );

	$html .= '</div></div></div></div>';
	echo $html;
}

/**
 * Default Thumbnail Image
 */
function saitama_default_thumbnail_image( $html, $post_id, $post_thumbnail_id, $size, $attr) {
	if ( !empty($html) ) return $html;

	$html = '<img class="img-responsive" src="' . get_template_directory_uri() . '/assets/img/no-image.gif" alt="No Image">';
	return $html;
}
add_filter( 'post_thumbnail_html', 'saitama_default_thumbnail_image', 10, 5 );