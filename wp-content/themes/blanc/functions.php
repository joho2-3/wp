<?php
/**
 * This file loads theme Functions and definitions.
 * @link		http://welcustom.net/
 * @author		Mamekko
 * @copyright	Copyright (c) 2015 welcustom.net
 */

//Implement the Custom Header and Custom background
require( get_template_directory() .'/inc/custom-header.php' );
require( get_template_directory() .'/inc/custom-background.php' );

//Welcart e-Commerce Plugin recommendation
require_once dirname( __FILE__ ) . '/inc/class-tgm-plugin-activation.php';
add_action( 'tgmpa_register', 'blanc_register_required_plugins' );
function blanc_register_required_plugins() {
	$plugins = array(
		array(
			'name'      => 'Welcart e-Commerce',
			'slug'      => 'usc-e-shop',
			'required'  => false,
		),
	);
	$config = array(
		'default_path' => '',
		'menu'         => 'tgmpa-install-plugins',
		'has_notices'  => true,
		'dismissable'  => true,
		'dismiss_msg'  => '',
		'is_automatic' => false,
		'message'      => '',
		'strings'      => array(
			'page_title'						=> __( 'Install Recommended Plugins', 'blanc' ),
			'menu_title'						=> __( 'Install Plugins', 'blanc' ),
			'installing'						=> __( 'Installing Plugin: %s', 'blanc' ),
			'oops'								=> __( 'Something went wrong with the plugin API.', 'blanc' ),
			'notice_can_install_recommended'	=> _n_noop( 'This theme recommends the following plugin: %1$s.', 'This theme recommends the following plugins: %1$s.', 'blanc' ),
			'notice_cannot_install'				=> _n_noop( 'Sorry, but you do not have the correct permissions to install the %s plugin. Contact the administrator of this site for help on getting the plugin installed.', 'Sorry, but you do not have the correct permissions to install the %s plugins. Contact the administrator of this site for help on getting the plugins installed.', 'blanc' ),
			'notice_can_activate_recommended'	=> _n_noop( 'The following recommended plugin is currently inactive: %1$s.', 'The following recommended plugins are currently inactive: %1$s.', 'blanc' ),
			'notice_cannot_activate'			=> _n_noop( 'Sorry, but you do not have the correct permissions to activate the %s plugin. Contact the administrator of this site for help on getting the plugin activated.', 'Sorry, but you do not have the correct permissions to activate the %s plugins. Contact the administrator of this site for help on getting the plugins activated.', 'blanc' ),
			'notice_ask_to_update'				=> _n_noop( 'The following plugin needs to be updated to its latest version to ensure maximum compatibility with this theme: %1$s.', 'The following plugins need to be updated to their latest version to ensure maximum compatibility with this theme: %1$s.', 'blanc' ),
			'notice_cannot_update'				=> _n_noop( 'Sorry, but you do not have the correct permissions to update the %s plugin. Contact the administrator of this site for help on getting the plugin updated.', 'Sorry, but you do not have the correct permissions to update the %s plugins. Contact the administrator of this site for help on getting the plugins updated.', 'blanc' ),
			'install_link'						=> _n_noop( 'Begin installing plugin', 'Begin installing plugins', 'blanc' ),
			'activate_link'						=> _n_noop( 'Begin activating plugin', 'Begin activating plugins', 'blanc' ),
			'return'							=> __( 'Return to Recommended Plugins Installer', 'blanc' ),
			'plugin_activated'					=> __( 'Plugin activated successfully.', 'tgmpa' ),
			'complete'							=> __( 'All plugins installed and activated successfully. %s', 'blanc' ),
			'nag_type'							=> 'updated'
		)
	);
	tgmpa( $plugins, $config );
}

function blanc_setup(){
	//Translation
	load_theme_textdomain( 'blanc', get_template_directory() .'/languages' );

	//Switch default core markup to output valid HTML5.
	add_theme_support( 'html5', array('search-form', 'comment-form', 'comment-list', 'gallery', 'caption') );

	//Feed
	add_theme_support( 'automatic-feed-links' );

	//Navigation menu
	register_nav_menu( 'navigation', __( 'Navigation', 'blanc' ) );

	//Featured image
	add_theme_support( 'post-thumbnails' );
	set_post_thumbnail_size( 150, 150, true );

	//Content width
		if ( !isset( $content_width ) ){
	$content_width = 870;
	}
	
	//Add title tag
	add_theme_support( 'title-tag' );
}
add_action( 'after_setup_theme', 'blanc_setup' );

//Editor style
function blanc_add_editor_styles() {
	add_editor_style( 'editor-style.css' );
}
add_action( 'init', 'blanc_add_editor_styles' );

//Length of excerpt
function blanc_length($length){
	if( !wp_is_mobile() ){
		return 70;
	} else {
		return 35;
	}
}
add_filter( 'excerpt_mblength', 'blanc_length' );

//Excerpt more
function blanc_more($more){
	return '&hellip;';
}
add_filter( 'excerpt_more', 'blanc_more' );

//Scripts and Style sheets
function blanc_scripts(){
	global $post;
	wp_enqueue_script( 'jquery' );
	wp_enqueue_script( 'modernizr', get_template_directory_uri() .'/js/vendor/modernizr.js', array(), '', true );
	wp_enqueue_script( 'scripts', get_template_directory_uri() .'/js/scripts.js', array(), '1.0', true );

	if( is_singular() && comments_open() ){
		wp_enqueue_script('comment-reply');
	}

	if ( is_front_page() || ( is_single() && ( isset($post->post_mime_type) && $post->post_mime_type == 'item' ) ) ){
		wp_enqueue_style( 'flexslider-css', get_template_directory_uri() .'/css/flexslider.css' );
		wp_enqueue_script( 'flexslider-js', get_template_directory_uri() .'/js/jquery.flexslider-min.js', array(), '2.4.0', true );
		if ( is_front_page() ){
			wp_enqueue_script( 'use-flexslider-frontpage', get_template_directory_uri() .'/js/use-flexslider-frontpage.js', array(), '1.0', true );
		}
	}
	if( is_single() ){
		wp_enqueue_style( 'swipebox-style', get_template_directory_uri() .'/css/swipebox.min.css', 'all' );
		wp_enqueue_script( 'swipebox', get_template_directory_uri() .'/js/jquery.swipebox.min.js', array(), '1.4.1', true );
		wp_enqueue_script( 'use-swipebox', get_template_directory_uri() .'/js/use-swipebox.js', array(), '1.0', true );
		if( isset($post->post_mime_type) && $post->post_mime_type == 'item' ){
			wp_enqueue_script( 'scripts-item', get_template_directory_uri() .'/js/scripts-item.js', array(), '1.1', true);
		}
	}
	//Form Validation for Welcart e-commerce plugin
	if( function_exists('usces_the_item') ) {
		$usces_validation = array( 'newmemberform', 'member', 'customer', 'delivery' );
		global $usces;
		if( in_array( $usces->page, $usces_validation ) ){
			wp_enqueue_style( 'validationEngine-css', get_template_directory_uri() .'/css/validationEngine.jquery.css', 'all' );
			wp_enqueue_script( 'validationEngine', get_template_directory_uri() .'/js/jquery.validationEngine.js', array(), '2.6.2', true );
			$wp_lang = get_bloginfo( 'language' );
			switch( $wp_lang ){
			case 'zh_CN':
			case 'zh_TW':
			case 'pt_BR':
			$wp_lang = $wp_lang;
			break;
			case 'zh':
			$wp_lang = $wp_lang . '_CN';
			break;
			case 'cs_CZ':
			case 'nb_NO':
			case 'nn_NO':
			$wp_lang = substr( $wp_lang, -2 );
			break;
			default:
			$wp_lang = substr( $wp_lang, 0, 2 );
			}
			$jve_lang = '/js/languages/jquery.validationEngine-' . $wp_lang . '.js';
			if( file_exists( get_template_directory(). $jve_lang ) ){
				wp_enqueue_script( 'validationEngine-lang', get_template_directory_uri(). $jve_lang, array(), '', true );
			} else {
				wp_enqueue_script( 'validationEngine-lang', get_template_directory_uri() . '/js/languages/jquery.validationEngine-en.js', array(), '', true );
			}
				wp_enqueue_script( 'use-validationEngine', get_template_directory_uri() . '/js/use-validationEngine.js', array(), '1.0',  true );
			}
	}
	wp_enqueue_style( 'normalize-style', get_template_directory_uri() .'/css/normalize.css' );
	wp_enqueue_style( 'foundation-style', get_template_directory_uri() .'/css/foundation.min.css' );
	wp_enqueue_style( 'blanc-style', get_stylesheet_uri() );
	wp_enqueue_style( 'font-awesome', get_template_directory_uri() .'/css/font-awesome.min.css' );
	if( function_exists('usces_the_item') ){
		wp_enqueue_style( 'welcart-style', get_template_directory_uri() .'/welcart.css' );
	}
}
add_action( 'wp_enqueue_scripts', 'blanc_scripts' );

//Widgets
function blanc_widgets_init(){
	register_sidebar( array(
		'id' => 'column-blog',
		'name' => __( 'Blog sidebar', 'blanc' ),
		'description' => __( 'Place widgets for blog page.', 'blanc' ),
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget' => '</aside>',
		'before_title' => '<h1 class="widgettitle">',
		'after_title' => '</h1>'
		)
	);
	register_sidebar( array(
		'id' => 'column-page',
		'name' => __( 'Page sidebar', 'blanc' ),
		'description' => __( 'Place widgets for pages.', 'blanc' ),
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget' => '</aside>',
		'before_title' => '<h1 class="widgettitle">',
		'after_title' => '</h1>'
		)
	);
	if( function_exists('usces_the_item') ){ //Footer menu and member page sidebar for Welcart e-commerce plugin
		register_sidebar( array(
			'id' => 'column1',
			'name' => __( 'Footer column 1', 'blanc' ),
			'description' => __( 'Place widgets for 1st column.', 'blanc' ),
			'before_widget' => '<aside id="%1$s" class="widget %2$s">',
			'after_widget' => '</aside>',
			'before_title' => '<h1 class="widgettitle">',
			'after_title' => '</h1>'
			)
		);
		register_sidebar( array(
			'id' => 'column2',
			'name' => __( 'Footer column 2', 'blanc' ),
			'description' => __( 'Place widgets for 2nd column.', 'blanc' ),
			'before_widget' => '<aside id="%1$s" class="widget %2$s">',
			'after_widget' => '</aside>',
			'before_title' => '<h1 class="widgettitle">',
			'after_title' => '</h1>'
			)
		);
		register_sidebar( array(
			'id' => 'column3',
			'name' => __( 'Footer column 3', 'blanc' ),
			'description' => __( 'Place widgets for 3rd column.', 'blanc' ),
			'before_widget' => '<aside id="%1$s" class="widget %2$s">',
			'after_widget' => '</aside>',
			'before_title' => '<h1 class="widgettitle">',
			'after_title' => '</h1>'
			)
		);
		register_sidebar( array(
			'id' => 'column4',
			'name' => __( 'Footer column 4', 'blanc' ),
			'description' => __( 'Place widgets for 4th column.', 'blanc' ),
			'before_widget' => '<aside id="%1$s" class="widget %2$s">',
			'after_widget' => '</aside>',
			'before_title' => '<h1 class="widgettitle">',
			'after_title' => '</h1>'
			)
		);
		register_sidebar( array(
			'id' => 'column-member',
			'name' => __( 'Member page sidebar', 'blanc' ),
			'description' => __( 'Place widgets for member page.', 'blanc' ),
			'before_widget' => '<aside id="%1$s" class="widget %2$s">',
			'after_widget' => '</aside>',
			'before_title' => '<h1 class="widgettitle">',
			'after_title' => '</h1>'
			)
		);
	}
}
add_action( 'widgets_init', 'blanc_widgets_init' );

//Remove archive head title displayed by WordPress 4.1
function blanc_get_the_archive_title() {
	if ( is_category() ) {
		$title = sprintf( __( '<span>CATEGORY</span> %s' , 'blanc' ), single_cat_title( '', false ) );
	} elseif ( is_tag() ) {
		$title = sprintf( __( '<span>TAG</span> %s' , 'blanc' ), single_tag_title( '', false ) );
	} elseif ( is_author() ) {
		$title = sprintf( __( '<span>AUTHOR</span> %s' , 'blanc' ), '<span class="vcard">' . get_the_author() . '</span>' );
	} elseif ( is_year() ) {
		$title = sprintf( __( '<span>ARCHIVES</span> %s' , 'blanc' ), get_the_date( _x( 'Y', 'yearly archives date format', 'default' ) ) );
	} elseif ( is_month() ) {
		$title = sprintf( __( '<span>ARCHIVES</span> %s' , 'blanc' ), get_the_date( _x( 'F Y', 'monthly archives date format', 'default' ) ) );
	} elseif ( is_day() ) {
		$title = sprintf( __( '<span>ARCHIVES</span> %s' , 'blanc' ), get_the_date( _x( 'F j, Y', 'daily archives date format', 'default' ) ) );
	} elseif ( is_tax( 'post_format' ) ) {
		if ( is_tax( 'post_format', 'post-format-aside' ) ) {
			$title = _x( 'Asides', 'post format archive title', 'default' );
		} elseif ( is_tax( 'post_format', 'post-format-gallery' ) ) {
			$title = _x( 'Galleries', 'post format archive title', 'default' );
		} elseif ( is_tax( 'post_format', 'post-format-image' ) ) {
			$title = _x( 'Images', 'post format archive title', 'default' );
		} elseif ( is_tax( 'post_format', 'post-format-video' ) ) {
			$title = _x( 'Videos', 'post format archive title', 'default' );
		} elseif ( is_tax( 'post_format', 'post-format-quote' ) ) {
			$title = _x( 'Quotes', 'post format archive title', 'default' );
		} elseif ( is_tax( 'post_format', 'post-format-link' ) ) {
			$title = _x( 'Links', 'post format archive title', 'default' );
		} elseif ( is_tax( 'post_format', 'post-format-status' ) ) {
			$title = _x( 'Statuses', 'post format archive title', 'default' );
		} elseif ( is_tax( 'post_format', 'post-format-audio' ) ) {
			$title = _x( 'Audio', 'post format archive title', 'default' );
		} elseif ( is_tax( 'post_format', 'post-format-chat' ) ) {
			$title = _x( 'Chats', 'post format archive title', 'default' );
		}
	} elseif ( is_post_type_archive() ) {
		$title = sprintf( __( '<span>ARCHIVES</span> %s' , 'blanc' ), post_type_archive_title( '', false ) );
	} elseif ( is_tax() ) {
		$tax = get_taxonomy( get_queried_object()->taxonomy );
		/* translators: 1: Taxonomy singular name, 2: Current taxonomy term */
		$title = sprintf( __( '%1$s: %2$s', 'default' ), $tax->labels->singular_name, single_term_title( '', false ) );
	} else {
		$title = __( '<span>ARCHIVES</span>', 'blanc' );
	}

	return $title;
}
add_filter('get_the_archive_title', 'blanc_get_the_archive_title', 10);

//******* Followings are the functions for Welcart e-commerce only *******
if( function_exists('usces_the_item')){
	
	//Change cart button to inquiry button when sku has no stock
	function blanc_single_sku_zaiko_message($inquery_button){
		$inquery_button = '<a href="'. USCES_INQUIRY_URL .'" class="skubutton"><i class="fa fa-envelope"></i>&nbsp;' .__('Inquiry Form', 'blanc') .'</a>';
		return $inquery_button;
	}
	add_filter('usces_filters_single_sku_zaiko_message', 'blanc_single_sku_zaiko_message', 10);
	add_filter('usces_filters_multi_sku_zaiko_message', 'blanc_single_sku_zaiko_message', 10);

	//Specific templates for item archives & item search page
	function blanc_category_item_template($category_item_template) {
		$category_id = get_query_var('cat');
		$parent_ids = get_ancestors($category_id, 'category');
		$parent_slugs = array();
		foreach( $parent_ids as $parent_id ){
			$parent = get_category($parent_id);
			$parent_slugs[] = $parent->slug;
		}
		if( in_array('item', $parent_slugs) || is_category('item') ){
			$category_item_template = dirname( __FILE__ ) . '/archive-item.php';
		}
		return $category_item_template;
	}
	add_filter( 'category_template', 'blanc_category_item_template' );

	function blanc_tag_item_template($tag_item_template) {
		global $post;
		if( isset($post->post_mime_type) && $post->post_mime_type == 'item' ) {
			$tag_item_template = dirname( __FILE__ ) . '/archive-item.php';
		}
		return $tag_item_template;
	}
	add_filter( 'tag_template', 'blanc_tag_item_template' );

	function blanc_search_item_template($search_item_template) {
		if( is_search() && !isset($_GET['searchitem']) ){
			$search_item_template = dirname( __FILE__ ) . '/search-item.php';
		}
		return $search_item_template;
	}
	add_filter( 'search_template', 'blanc_search_item_template' );

	//Change querys for item archives
	if( term_exists('item', 'category') ){
		function blanc_query($query){
			global $usces;
			$item_cat = get_category_by_slug('item');
			$item_cat_id = $item_cat->cat_ID;
			if ( is_admin() || ! $query->is_main_query() ){
				return;
			} elseif( $query->is_home() || $query->is_author() || $query->is_date() ){
				$query->set('category__not_in', $item_cat_id);
			} elseif( $query->is_category() ){
				$category_id = get_query_var('cat');
				$parent_ids = get_ancestors($category_id, 'category');
				$parent_slugs = array();
				foreach ($parent_ids as $parent_id){
					$parent = get_category($parent_id);
					$parent_slugs[] = $parent->slug;
				}
				if( in_array('item', $parent_slugs) || is_category('item') ){
					$query->set('posts_per_page', '12');
				}
			} elseif ( $query->is_search && isset($_GET['searchitem']) ) {
				$query->set('category__not_in', $item_cat_id);
			} elseif ( $query->is_search && !isset($_GET['searchitem']) ){
				$query->set('posts_per_page','12');
				$query->set('category_name','item');
			}
		}
		add_action('pre_get_posts', 'blanc_query');
	}

	//Welcart cart page row  *removing unused cells
	function blanc_cart_row($row, $cart, $materials){
		$args = compact('cart', 'i', 'cart_row', 'post_id', 'sku' );
		extract($materials);
		$row = '';
		if ( empty($options) ) {
			$optstr =  '';
			$options =  array();
		}
		$row .= '<tr>
			<td>';
			$cart_thumbnail = '<a href="' . get_permalink($post_id) . '">' . wp_get_attachment_image( $pictid, array(60, 60), true ) . '</a>';
			$row .= apply_filters('usces_filter_cart_thumbnail', $cart_thumbnail, $post_id, $pictid, $i,$cart_row);
			$row .= '</td><td>' . esc_html($cartItemName) . '<br />';
		if( is_array($options) && count($options) > 0 ){
			$optstr = '';
			foreach($options as $key => $value){
				if( !empty($key) ) {
					$key = urldecode($key);
					if(is_array($value)) {
						$c = '';
						$optstr .= esc_html($key) . ' : ';
						foreach($value as $v) {
							$optstr .= $c.nl2br(esc_html(urldecode($v)));
							$c = ', ';
						}
						$optstr .= "<br />\n";
					} else {
						$optstr .= esc_html($key) . ' : ' . nl2br(esc_html(urldecode($value))) . "<br />\n";
					}
				}
			}
			$row .= apply_filters( 'usces_filter_option_cart', $optstr, $options);
		}
		$row .= apply_filters( 'usces_filter_option_info_cart', '', $cart_row, $args );
		$row .= '</td>
			<td class="text-right">';
		if( usces_is_gptekiyo($post_id, $sku_code, $quantity) ) {
			$usces_gp = 1;
			$Business_pack_mark = '<img src="' . get_template_directory_uri() . '/images/gp.gif" alt="' . __('Business package discount','usces') . '" /><br />';
			$row .= apply_filters('usces_filter_itemGpExp_cart_mark', $Business_pack_mark);
		}
		$row .= usces_crform($skuPrice, true, false, 'return') . '
			</td>
			<td>';
		$row_quant = '<input name="quant[' . $i . '][' . $post_id . '][' . $sku . ']" class="quantity" type="text" value="' . esc_attr($cart_row['quantity']) . '" />';
		$row .= apply_filters( 'usces_filter_cart_rows_quant', $row_quant, $args );
		$row .= '</td>
			<td class="text-right">' . usces_crform(($skuPrice * $cart_row['quantity']), true, false, 'return') . '</td>
			<td class="text-center">';
		foreach($options as $key => $value){
			if(is_array($value)) {
				foreach($value as $v) {
					$row .= '<input name="itemOption[' . $i . '][' . $post_id . '][' . $sku . '][' . $key . '][' . $v . ']" type="hidden" value="' . $v . '" />'."\n";
				}
			} else {
				$row .= '<input name="itemOption[' . $i . '][' . $post_id . '][' . $sku . '][' . $key . ']" type="hidden" value="' . $value . '" />'."\n";
			}
		}
		$row .= '<input name="itemRestriction[' . $i . ']" type="hidden" value="' . $itemRestriction . '" />
			<input name="stockid[' . $i . ']" type="hidden" value="' . $stockid . '" />
			<input name="itempostid[' . $i . ']" type="hidden" value="' . $post_id . '" />
			<input name="itemsku[' . $i . ']" type="hidden" value="' . $sku . '" />
			<input name="zaikonum[' . $i . '][' . $post_id . '][' . $sku . ']" type="hidden" value="' . esc_attr($skuZaikonum) . '" />
			<input name="skuPrice[' . $i . '][' . $post_id . '][' . $sku . ']" type="hidden" value="' . esc_attr($skuPrice) . '" />
			<input name="advance[' . $i . '][' . $post_id . '][' . $sku . ']" type="hidden" value="' . esc_attr($advance) . '" />
			<input name="delButton[' . $i . '][' . $post_id . '][' . $sku . ']" class="delButton font-awesome" type="submit" value="&#xf00d" title="' .__('Delete this item','blanc') .'" />
			</td>
		</tr>';
		return $row;
	}
	add_filter('usces_filter_cart_row', 'blanc_cart_row', 10, 3);

	//Remove unused cell in the Table on confirmation page
	function blanc_filter_confirm_row($row, $cart, $materials){
			extract($materials);
			$row = '';
			if (empty($options)) {
				$optstr =  '';
				$options =  array();
			}
			$row .= '<tr>
				<td>';
			$cart_thumbnail = wp_get_attachment_image( $pictid, array(60, 60), true );
			$row .= apply_filters('usces_filter_cart_thumbnail', $cart_thumbnail, $post_id, $pictid, $i, $cart_row);
			$row .= '</td><td>' . $cartItemName . '<br />';
			if( is_array($options) && count($options) > 0 ){
				$optstr = '';
				foreach($options as $key => $value){
					if( !empty($key) ) {
						$key = urldecode($key);
						if(is_array($value)) {
							$c = '';
							$optstr .= esc_html($key) . ' : ';
							foreach($value as $v) {
								$optstr .= $c.nl2br(esc_html(urldecode($v)));
								$c = ', ';
							}
							$optstr .= "<br />\n";
						} else {
							$optstr .= esc_html($key) . ' : ' . nl2br(esc_html(urldecode($value))) . "<br />\n";
						}
					}
				}
				$row .= apply_filters( 'usces_filter_option_confirm', $optstr, $options);
			}
			$row .= '</td>
				<td class="text-right">' . usces_crform($skuPrice, true, false, 'return') . '</td>
				<td class="text-center">' . $cart_row['quantity'] . '</td>
				<td class="text-right">' . usces_crform(($skuPrice * $cart_row['quantity']), true, false, 'return') . '</td>
			</tr>';
			return $row;
	}
	add_filter('usces_filter_confirm_row', 'blanc_filter_confirm_row', 10, 3);

	//SSL error fix
	//source from http://www.seshop.com/product/detail/15639/
		if( $usces->options['use_ssl'] ){
			add_action('init', 'usces_ob_start');
			function usces_ob_start(){
				global $usces;
				if( $usces->use_ssl && ($usces->is_cart_or_member_page($_SERVER['REQUEST_URI']) || $usces->is_inquiry_page($_SERVER['REQUEST_URI'])) )
				ob_start('usces_ob_callback');
			}
			if ( ! function_exists( 'usces_ob_callback' ) ) {
				function usces_ob_callback($buffer){
				global $usces;
				$pattern = array(
					'|(<[^<]*)href=\"'.get_option('siteurl').'([^>]*)\.css([^>]*>)|',
					'|(<[^<]*)src=\"'.get_option('siteurl').'([^>]*>)|'
					);
				$replacement = array(
					'${1}href="'.USCES_SSL_URL_ADMIN.'${2}.css${3}',
					'${1}src="'.USCES_SSL_URL_ADMIN.'${2}'
					);
				$buffer = preg_replace($pattern, $replacement, $buffer);
				return $buffer;
				}
			}
		}

}